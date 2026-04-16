<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\VentaItem;
use App\Models\Cliente;
use App\Models\Equipo;
use App\Models\Paquete;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class VentaController extends Controller
{
    public function index()
    {
        $ventas = Venta::with('cliente')->orderBy('created_at', 'desc')->get();
        return view('eventos.ventas.index', compact('ventas'));
    }

    public function create(Request $request)
    {
        $clientes = Cliente::all();
        $equipos = Equipo::where('estado', 'Disponible')->get();
        $paquetes = Paquete::where('activo', true)->get();
        
        // Si viene un cliente por parámetro (desde la ficha del cliente)
        $cliente_seleccionado = null;
        if ($request->has('cliente_id')) {
            $cliente_seleccionado = Cliente::find($request->cliente_id);
        }

        return view('eventos.ventas.create', compact('clientes', 'equipos', 'paquetes', 'cliente_seleccionado'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'fecha_venta' => 'required|date',
            'tipo_alquiler' => 'required|in:dia,hora',
            'items' => 'required|array|min:1',
            'items.*.id' => 'required',
            'items.*.type' => 'required|in:equipo,paquete',
            'items.*.cantidad' => 'required|integer|min:1',
            'items.*.precio' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            // Generar número de factura correlativo
            $ultimo = Venta::orderBy('id', 'desc')->first();
            $numero = $ultimo ? (int) str_replace('FAC-', '', $ultimo->numero_factura) + 1 : 1;
            $numero_factura = 'FAC-' . str_pad($numero, 5, '0', STR_PAD_LEFT);

            $venta = Venta::create([
                'cliente_id' => $request->cliente_id,
                'numero_factura' => $numero_factura,
                'fecha_venta' => $request->fecha_venta,
                'fecha_evento_inicio' => $request->fecha_evento_inicio,
                'fecha_evento_fin' => $request->fecha_evento_fin,
                'tipo_alquiler' => $request->tipo_alquiler,
                'notas' => $request->notas,
                'estado_pago' => 'pendiente', // Estado inicial de la venta
                'created_by' => Auth::id(),
            ]);

            // Calcular duración basada en las fechas
            $duracion = 1;
            if ($request->fecha_evento_inicio && $request->fecha_evento_fin) {
                $inicio = Carbon::parse($request->fecha_evento_inicio);
                $fin = Carbon::parse($request->fecha_evento_fin);
                $diffSeg = $inicio->diffInSeconds($fin, false);
                if ($diffSeg > 0) {
                    if ($request->tipo_alquiler === 'dia') {
                        $duracion = ceil($diffSeg / (60 * 60 * 24)) ?: 1;
                    } else {
                        $duracion = ceil($diffSeg / (60 * 60)) ?: 1;
                    }
                }
            }

            $total = 0;
            foreach ($request->items as $item) {
                $itemable_type = $item['type'] === 'equipo' ? Equipo::class : Paquete::class;
                $subtotal = $item['cantidad'] * $item['precio'] * $duracion;
                
                VentaItem::create([
                    'venta_id' => $venta->id,
                    'itemable_id' => $item['id'],
                    'itemable_type' => $itemable_type,
                    'nombre' => $item['nombre'],
                    'cantidad' => $item['cantidad'],
                    'precio_unitario' => $item['precio'],
                    'subtotal' => $subtotal,
                ]);

                $total += $subtotal;
            }

            // TODO: Manejar impuestos si es necesario
            $venta->update([
                'subtotal' => $total,
                'total' => $total,
            ]);

            DB::commit();

            return redirect()->route('eventos.ventas.index')
                ->with('success', "Venta $numero_factura creada con éxito.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al crear la venta: ' . $e->getMessage())->withInput();
        }
    }

    public function show(Venta $venta)
    {
        $venta->load(['cliente', 'items.itemable']);
        return view('eventos.ventas.show', compact('venta'));
    }

    public function edit(Venta $venta)
    {
        $venta->load(['items.itemable']);
        $clientes = Cliente::all();
        $equipos = Equipo::where('estado', 'Disponible')->get();
        $paquetes = Paquete::where('activo', true)->get();
        
        return view('eventos.ventas.edit', compact('venta', 'clientes', 'equipos', 'paquetes'));
    }

    public function update(Request $request, Venta $venta)
    {
        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'fecha_venta' => 'required|date',
            'tipo_alquiler' => 'required|in:dia,hora',
            'items' => 'required|array|min:1',
            'items.*.id' => 'required',
            'items.*.type' => 'required|in:equipo,paquete',
            'items.*.cantidad' => 'required|integer|min:1',
            'items.*.precio' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $venta->update([
                'cliente_id' => $request->cliente_id,
                'fecha_venta' => $request->fecha_venta,
                'fecha_evento_inicio' => $request->fecha_evento_inicio,
                'fecha_evento_fin' => $request->fecha_evento_fin,
                'tipo_alquiler' => $request->tipo_alquiler,
                'notas' => $request->notas,
            ]);

            // Eliminar items anteriores y recrear (o sincronizar)
            $venta->items()->delete();

            // Calcular duración basada en las fechas
            $duracion = 1;
            if ($request->fecha_evento_inicio && $request->fecha_evento_fin) {
                $inicio = Carbon::parse($request->fecha_evento_inicio);
                $fin = Carbon::parse($request->fecha_evento_fin);
                $diffSeg = $inicio->diffInSeconds($fin, false);
                if ($diffSeg > 0) {
                    if ($request->tipo_alquiler === 'dia') {
                        $duracion = ceil($diffSeg / (60 * 60 * 24)) ?: 1;
                    } else {
                        $duracion = ceil($diffSeg / (60 * 60)) ?: 1;
                    }
                }
            }

            $total = 0;
            foreach ($request->items as $item) {
                $itemable_type = $item['type'] === 'equipo' ? Equipo::class : Paquete::class;
                $subtotal = $item['cantidad'] * $item['precio'] * $duracion;
                
                VentaItem::create([
                    'venta_id' => $venta->id,
                    'itemable_id' => $item['id'],
                    'itemable_type' => $itemable_type,
                    'nombre' => $item['nombre'],
                    'cantidad' => $item['cantidad'],
                    'precio_unitario' => $item['precio'],
                    'subtotal' => $subtotal,
                ]);

                $total += $subtotal;
            }

            $venta->update([
                'subtotal' => $total,
                'total' => $total,
            ]);

            DB::commit();

            return redirect()->route('eventos.ventas.index')
                ->with('success', "Venta {$venta->numero_factura} actualizada con éxito.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al actualizar la venta: ' . $e->getMessage())->withInput();
        }
    }

    public function factura(Venta $venta)
    {
        $venta->load(['cliente', 'items.itemable']);
        
        // Obtener datos de configuración para la factura
        $config = [
            'nombre_app' => Setting::getByKey('app_name', 'Gestión Inventario'),
            'empresa_nombre' => Setting::getByKey('company_name', 'Mi Empresa S.A.S'),
            'empresa_nit' => Setting::getByKey('company_id', '900.000.000-1'),
            'empresa_direccion' => Setting::getByKey('company_address', 'Calle Falsa 123'),
            'empresa_telefono' => Setting::getByKey('company_phone', '300 000 0000'),
            'empresa_email' => Setting::getByKey('company_email', 'contacto@empresa.com'),
            'logo' => Setting::getByKey('app_logo'),
        ];

        return view('eventos.ventas.factura', compact('venta', 'config'));
    }

    public function destroy(Venta $venta)
    {
        $venta->delete();
        return redirect()->route('eventos.ventas.index')
            ->with('success', 'Venta eliminada correctamente.');
    }
}
