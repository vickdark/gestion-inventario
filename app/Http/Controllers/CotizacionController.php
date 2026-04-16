<?php

namespace App\Http\Controllers;

use App\Models\Cotizacion;
use App\Models\CotizacionItem;
use App\Models\Cliente;
use App\Models\Equipo;
use App\Models\Paquete;
use App\Models\Venta;
use App\Models\VentaItem;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CotizacionController extends Controller
{
    public function index()
    {
        $cotizaciones = Cotizacion::with('cliente')->orderBy('created_at', 'desc')->get();
        return view('eventos.cotizaciones.index', compact('cotizaciones'));
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

        return view('eventos.cotizaciones.create', compact('clientes', 'equipos', 'paquetes', 'cliente_seleccionado'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'fecha_emision' => 'required|date',
            'fecha_vencimiento' => 'required|date|after_or_equal:fecha_emision',
            'tipo_alquiler' => 'required|in:dia,hora',
            'items' => 'required|array|min:1',
            'items.*.id' => 'required',
            'items.*.type' => 'required|in:equipo,paquete',
            'items.*.cantidad' => 'required|integer|min:1',
            'items.*.precio' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            // Generar número de cotización correlativo
            $ultimo = Cotizacion::orderBy('id', 'desc')->first();
            $numero = $ultimo ? (int) str_replace('COT-', '', $ultimo->numero_cotizacion) + 1 : 1;
            $numero_cotizacion = 'COT-' . str_pad($numero, 5, '0', STR_PAD_LEFT);

            $cotizacion = Cotizacion::create([
                'cliente_id' => $request->cliente_id,
                'numero_cotizacion' => $numero_cotizacion,
                'fecha_emision' => $request->fecha_emision,
                'fecha_vencimiento' => $request->fecha_vencimiento,
                'fecha_evento_inicio' => $request->fecha_evento_inicio,
                'fecha_evento_fin' => $request->fecha_evento_fin,
                'tipo_alquiler' => $request->tipo_alquiler,
                'notas' => $request->notas,
                'estado' => 'pendiente',
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
                
                CotizacionItem::create([
                    'cotizacion_id' => $cotizacion->id,
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
            $cotizacion->update([
                'subtotal' => $total,
                'total' => $total,
            ]);

            DB::commit();

            return redirect()->route('eventos.cotizaciones.index')
                ->with('success', "Cotización $numero_cotizacion creada con éxito.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al crear la cotización: ' . $e->getMessage())->withInput();
        }
    }

    public function show(Cotizacion $cotizacion)
    {
        $cotizacion->load(['cliente', 'items.itemable']);
        return view('eventos.cotizaciones.show', compact('cotizacion'));
    }

    public function edit(Cotizacion $cotizacion)
    {
        $cotizacion->load(['items.itemable']);
        $clientes = Cliente::all();
        $equipos = Equipo::where('estado', 'Disponible')->get();
        $paquetes = Paquete::where('activo', true)->get();
        
        return view('eventos.cotizaciones.edit', compact('cotizacion', 'clientes', 'equipos', 'paquetes'));
    }

    public function update(Request $request, Cotizacion $cotizacion)
    {
        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'fecha_emision' => 'required|date',
            'fecha_vencimiento' => 'required|date|after_or_equal:fecha_emision',
            'tipo_alquiler' => 'required|in:dia,hora',
            'items' => 'required|array|min:1',
            'items.*.id' => 'required',
            'items.*.type' => 'required|in:equipo,paquete',
            'items.*.cantidad' => 'required|integer|min:1',
            'items.*.precio' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $cotizacion->update([
                'cliente_id' => $request->cliente_id,
                'fecha_emision' => $request->fecha_emision,
                'fecha_vencimiento' => $request->fecha_vencimiento,
                'fecha_evento_inicio' => $request->fecha_evento_inicio,
                'fecha_evento_fin' => $request->fecha_evento_fin,
                'tipo_alquiler' => $request->tipo_alquiler,
                'notas' => $request->notas,
            ]);

            // Eliminar items anteriores y recrear (o sincronizar)
            $cotizacion->items()->delete();

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
                
                CotizacionItem::create([
                    'cotizacion_id' => $cotizacion->id,
                    'itemable_id' => $item['id'],
                    'itemable_type' => $itemable_type,
                    'nombre' => $item['nombre'],
                    'cantidad' => $item['cantidad'],
                    'precio_unitario' => $item['precio'],
                    'subtotal' => $subtotal,
                ]);

                $total += $subtotal;
            }

            $cotizacion->update([
                'subtotal' => $total,
                'total' => $total,
            ]);

            DB::commit();

            return redirect()->route('eventos.cotizaciones.index')
                ->with('success', "Cotización {$cotizacion->numero_cotizacion} actualizada con éxito.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al actualizar la cotización: ' . $e->getMessage())->withInput();
        }
    }

    public function comprobante(Cotizacion $cotizacion)
    {
        $cotizacion->load(['cliente', 'items.itemable']);
        
        $config = [
            'app_name' => Setting::getByKey('app_name', 'Gestión Inventario'),
            'company_name' => Setting::getByKey('company_name', 'Mi Empresa S.A.S'),
            'company_id' => Setting::getByKey('company_id', '900.000.000-1'),
            'company_address' => Setting::getByKey('company_address', 'Calle Falsa 123'),
            'company_phone' => Setting::getByKey('company_phone', '300 000 0000'),
            'company_email' => Setting::getByKey('company_email', 'contacto@empresa.com'),
            'app_logo' => Setting::getByKey('app_logo'),
            'invoice_footer' => Setting::getByKey('invoice_footer', 'Gracias por su preferencia.'),
        ];

        return view('eventos.cotizaciones.comprobante', compact('cotizacion', 'config'));
    }

    public function convertir(Cotizacion $cotizacion)
    {
        if ($cotizacion->estado === 'convertida') {
            return back()->with('error', 'Esta cotización ya fue convertida a venta.');
        }

        try {
            DB::beginTransaction();

            // Generar número de factura correlativo
            $ultimo = Venta::orderBy('id', 'desc')->first();
            $numero = $ultimo ? (int) str_replace('FAC-', '', $ultimo->numero_factura) + 1 : 1;
            $numero_factura = 'FAC-' . str_pad($numero, 5, '0', STR_PAD_LEFT);

            $venta = Venta::create([
                'cotizacion_id' => $cotizacion->id,
                'cliente_id' => $cotizacion->cliente_id,
                'numero_factura' => $numero_factura,
                'fecha_venta' => now(),
                'fecha_evento_inicio' => $cotizacion->fecha_evento_inicio,
                'fecha_evento_fin' => $cotizacion->fecha_evento_fin,
                'tipo_alquiler' => $cotizacion->tipo_alquiler,
                'subtotal' => $cotizacion->subtotal,
                'impuesto' => $cotizacion->impuesto,
                'total' => $cotizacion->total,
                'estado_pago' => 'pendiente',
                'notas' => $cotizacion->notas,
                'created_by' => Auth::id(),
            ]);

            foreach ($cotizacion->items as $item) {
                VentaItem::create([
                    'venta_id' => $venta->id,
                    'itemable_id' => $item->itemable_id,
                    'itemable_type' => $item->itemable_type,
                    'nombre' => $item->nombre,
                    'cantidad' => $item->cantidad,
                    'precio_unitario' => $item->precio_unitario,
                    'subtotal' => $item->subtotal,
                ]);
            }

            $cotizacion->update(['estado' => 'convertida']);

            DB::commit();

            return redirect()->route('eventos.ventas.show', $venta->id)
                ->with('success', "Cotización convertida a venta exitosamente. Factura: $numero_factura");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al convertir cotización: ' . $e->getMessage());
        }
    }

    public function rechazar(Cotizacion $cotizacion)
    {
        if ($cotizacion->estado !== 'pendiente') {
            return back()->with('error', 'Solo las cotizaciones pendientes pueden ser rechazadas.');
        }

        $cotizacion->update(['estado' => 'rechazada']);

        return back()->with('success', 'La cotización ha sido marcada como rechazada.');
    }

    public function reabrir(Cotizacion $cotizacion)
    {
        if ($cotizacion->estado !== 'rechazada') {
            return back()->with('error', 'Solo las cotizaciones rechazadas pueden ser reabiertas.');
        }

        $cotizacion->update(['estado' => 'pendiente']);

        return back()->with('success', 'La cotización ha sido reabierta exitosamente.');
    }

    public function destroy(Cotizacion $cotizacion)
    {
        $cotizacion->delete();
        return redirect()->route('eventos.cotizaciones.index')
            ->with('success', 'Cotización eliminada correctamente.');
    }
}
