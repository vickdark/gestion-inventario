<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Venta;

class DemoController extends Controller
{
    public function dashboard()
    {
        $hoy = now()->toDateString();
        
        // Conteo de eventos
        $eventos_dia = Venta::whereDate('fecha_evento_inicio', $hoy)->count();
        $eventos_proximos = Venta::where('fecha_evento_inicio', '>', $hoy)
            ->where('estado_logistica', '!=', 'finalizado')
            ->count();
            
        // Cotizaciones pendientes
        $cotizaciones_pendientes = \App\Models\Cotizacion::where('estado', 'pendiente')->count();
        
        // Cálculo de ocupación de equipos (Simplicado: ratio de equipos en ventas activas vs total)
        $total_equipos = \App\Models\Equipo::sum('cantidad_total') ?: 1;
        $equipos_alquilados = \App\Models\VentaItem::whereHas('venta', function($q) {
                $q->where('estado_logistica', '!=', 'finalizado');
            })->where('itemable_type', \App\Models\Equipo::class)
            ->sum('cantidad');
            
        $equipos_disponibles = $total_equipos - $equipos_alquilados;
        
        // Alertas: Eventos en las próximas 48h con logística pendiente
        $alertas = Venta::where('fecha_evento_inicio', '<=', now()->addHours(48))
            ->where('fecha_evento_inicio', '>=', now())
            ->where('estado_logistica', 'pendiente')
            ->count();

        // Próximos eventos para la lista
        $proximos_eventos = Venta::with('cliente')
            ->where('fecha_evento_inicio', '>=', $hoy)
            ->where('estado_logistica', '!=', 'finalizado')
            ->orderBy('fecha_evento_inicio', 'asc')
            ->take(5)
            ->get();

        // Equipos críticos (Top 3 con menor porcentaje de disponibilidad)
        $equipos_criticos = \App\Models\Equipo::all()->map(function($e) {
            $ocupados = \App\Models\VentaItem::whereHas('venta', function($q) {
                $q->where('estado_logistica', '!=', 'finalizado');
            })->where('itemable_id', $e->id)
              ->where('itemable_type', \App\Models\Equipo::class)
              ->sum('cantidad');
            
            $e->ocupados = $ocupados;
            $e->disponibles = max(0, $e->cantidad_total - $ocupados);
            $e->porcentaje_uso = $e->cantidad_total > 0 ? ($ocupados / $e->cantidad_total) * 100 : 0;
            return $e;
        })->sortByDesc('porcentaje_uso')->take(3);

        return view('eventos.dashboard', compact(
            'eventos_dia', 'eventos_proximos', 'equipos_alquilados', 
            'equipos_disponibles', 'cotizaciones_pendientes', 'alertas', 
            'proximos_eventos', 'equipos_criticos', 'total_equipos'
        ));
    }

    public function clientes()
    {
        return view('eventos.clientes.index', [
            'clientes' => [
                ['nombre' => 'Juan Pérez', 'telefono' => '3001234567', 'direccion' => 'Calle 123 #45-67', 'tipo' => 'Persona', 'notas' => 'Cliente VIP'],
                ['nombre' => 'Empresa ABC', 'telefono' => '3109876543', 'direccion' => 'Av. Principal #10-20', 'tipo' => 'Empresa', 'notas' => 'Requiere cotización detallada'],
                ['nombre' => 'María García', 'telefono' => '3151112233', 'direccion' => 'Carrera 50 #12-34', 'tipo' => 'Persona', 'notas' => 'Cliente recurrente'],
            ]
        ]);
    }

    public function equipos()
    {
        return view('eventos.equipos.index', [
            'categorias' => ['Sonido', 'Inflables', 'Iluminación', 'Mobiliario', 'Carpas', 'Tarimas', 'Generadores', 'Accesorios'],
            'equipos' => [
                ['nombre' => 'Sonido Profesional 5000W', 'categoria' => 'Sonido', 'precio' => 150000, 'cantidad' => 2, 'estado' => 'Disponible'],
                ['nombre' => 'Inflable Castillo Gigante', 'categoria' => 'Inflables', 'precio' => 200000, 'cantidad' => 1, 'estado' => 'Alquilado'],
                ['nombre' => 'Set Luces LED RGB', 'categoria' => 'Iluminación', 'precio' => 80000, 'cantidad' => 10, 'estado' => 'Disponible'],
                ['nombre' => 'Silla Tiffany Blanca', 'categoria' => 'Mobiliario', 'precio' => 2500, 'cantidad' => 200, 'estado' => 'Disponible'],
            ]
        ]);
    }

    public function paquetes()
    {
        return view('eventos.paquetes.index', [
            'paquetes' => [
                ['nombre' => 'Paquete Cumpleaños Infantil', 'precio' => 350000, 'equipos' => 'Inflable, Sonido básico, 20 sillas, Mesa'],
                ['nombre' => 'Combo Fiesta Básica', 'precio' => 250000, 'equipos' => 'Sonido, 10 sillas, Mesa'],
            ]
        ]);
    }

    public function agenda()
    {
        $eventos = Venta::with(['cliente', 'items.itemable'])
            ->whereNotNull('fecha_evento_inicio')
            ->get()
            ->map(function($venta) {
                $items = $venta->items->map(function($item) {
                    return [
                        'nombre' => $item->itemable->nombre ?? 'Producto',
                        'cantidad' => $item->cantidad,
                        'precio' => '$' . number_format($item->precio_unitario, 0),
                    ];
                });

                return [
                    'id' => $venta->id,
                    'title' => $venta->cliente->nombre . " (" . $venta->numero_factura . ")",
                    'start' => $venta->fecha_evento_inicio->format('Y-m-d H:i:s'),
                    'end' => $venta->fecha_evento_fin ? $venta->fecha_evento_fin->format('Y-m-d H:i:s') : null,
                    'color' => $this->getColorByEstadoLogistica($venta->estado_logistica),
                    'extendedProps' => [
                        'factura' => $venta->numero_factura,
                        'cliente' => $venta->cliente->nombre,
                        'telefono' => $venta->cliente->telefono,
                        'direccion' => $venta->direccion_evento ?? 'Sin dirección',
                        'logistica' => ucfirst(str_replace('_', ' ', $venta->estado_logistica)),
                        'monto' => '$' . number_format($venta->monto_total, 0),
                        'saldo' => '$' . number_format($venta->monto_total - $venta->pagos()->sum('monto'), 0),
                        'items_count' => $venta->items->count(),
                        'items' => $items,
                        'vehiculo' => $venta->vehiculo ?? 'No asignado',
                        'personal' => $venta->personal_asignado ?? 'No asignado',
                        'ubicacion_link' => $venta->ubicacion_link,
                        'notas_logistica' => $venta->notas_logistica ?? 'Sin notas adicionales',
                        'url_show' => route('eventos.ventas.show', $venta->id)
                    ]
                ];
            });

        return view('eventos.agenda.index', compact('eventos'));
    }

    private function getColorByEstadoLogistica($estado)
    {
        return match($estado) {
            'pendiente' => '#6c757d',
            'en_montaje' => '#0dcaf0',
            'montado' => '#198754',
            'recogiendo' => '#ffc107',
            'finalizado' => '#0d6efd',
            default => '#6c757d',
        };
    }

    public function cotizaciones()
    {
        return view('eventos.cotizaciones.index');
    }

    public function logistica(Request $request)
    {
        $query = Venta::with(['cliente', 'gastos'])
            ->whereNotNull('fecha_evento_inicio');

        // Filtrar por estado
        if ($request->filled('estado')) {
            $query->where('estado_logistica', $request->estado);
        }

        // Filtrar por rango de fechas
        if ($request->filled('fecha_inicio')) {
            $query->whereDate('fecha_evento_inicio', '>=', $request->fecha_inicio);
        }
        if ($request->filled('fecha_fin')) {
            $query->whereDate('fecha_evento_inicio', '<=', $request->fecha_fin);
        }

        // Búsqueda por cliente o factura
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('numero_factura', 'like', "%$search%")
                  ->orWhereHas('cliente', function($cq) use ($search) {
                      $cq->where('nombre', 'like', "%$search%");
                  });
            });
        }

        $ventas = $query->orderBy('fecha_evento_inicio', 'asc')->get();

        return view('eventos.logistica.index', compact('ventas'));
    }

    public function checklistGeneral()
    {
        $activeSalesIds = Venta::where('estado_logistica', '!=', 'finalizado')
            ->whereNotNull('fecha_evento_inicio')
            ->pluck('id');

        $checklist = \App\Models\VentaItem::whereIn('venta_id', $activeSalesIds)
            ->select('nombre', \Illuminate\Support\Facades\DB::raw('SUM(cantidad) as total'))
            ->groupBy('nombre')
            ->get();

        $eventos_pendientes = Venta::with('cliente')
            ->where('estado_logistica', '!=', 'finalizado')
            ->whereNotNull('fecha_evento_inicio')
            ->get();

        return view('eventos.logistica.checklist', compact('checklist', 'eventos_pendientes'));
    }
}
