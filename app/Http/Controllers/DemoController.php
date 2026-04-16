<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Venta;

class DemoController extends Controller
{
    public function dashboard()
    {
        return view('eventos.dashboard', [
            'eventos_dia' => Venta::whereDate('fecha_evento_inicio', now())->count(),
            'eventos_proximos' => Venta::where('fecha_evento_inicio', '>', now())->count(),
            'equipos_ocupados' => 45,
            'equipos_disponibles' => 120,
            'cotizaciones_pendientes' => \App\Models\Cotizacion::where('estado', 'pendiente')->count(),
            'alertas' => 3
        ]);
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
        $eventos = Venta::with('cliente')
            ->whereNotNull('fecha_evento_inicio')
            ->get()
            ->map(function($venta) {
                return [
                    'id' => $venta->id,
                    'title' => $venta->cliente->nombre . " - " . ($venta->direccion_evento ?? 'Sin dirección'),
                    'start' => $venta->fecha_evento_inicio->format('Y-m-d H:i:s'),
                    'end' => $venta->fecha_evento_fin ? $venta->fecha_evento_fin->format('Y-m-d H:i:s') : null,
                    'url' => route('eventos.ventas.show', $venta->id),
                    'color' => $this->getColorByEstadoLogistica($venta->estado_logistica),
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
