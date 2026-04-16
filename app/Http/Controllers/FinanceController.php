<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Venta;
use App\Models\Pago;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class FinanceController extends Controller
{
    public function index(Request $request)
    {
        $query = Venta::with(['cliente', 'gastos', 'pagos']);

        // Filtros
        if ($request->filled('fecha_inicio')) {
            $query->whereDate('fecha_venta', '>=', $request->fecha_inicio);
        }
        if ($request->filled('fecha_fin')) {
            $query->whereDate('fecha_venta', '<=', $request->fecha_fin);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('numero_factura', 'like', "%$search%")
                  ->orWhereHas('cliente', function($cq) use ($search) {
                      $cq->where('nombre', 'like', "%$search%");
                  });
            });
        }

        $ventas = $query->orderBy('fecha_venta', 'desc')->get();

        // Totales consolidados
        $total_ventas = $ventas->sum('total');
        $total_gastos = $ventas->sum(function($v) { return $v->gastos->sum('monto'); });
        $total_cobrado = $ventas->sum(function($v) { return $v->pagos->sum('monto'); });
        $utilidad_neta = $total_cobrado - $total_gastos;
        $total_pendiente = $total_ventas - $total_cobrado;

        return view('eventos.finanzas.index', compact(
            'ventas', 'total_ventas', 'total_gastos', 'total_cobrado', 'utilidad_neta', 'total_pendiente'
        ));
    }

    public function storePago(Request $request, Venta $venta)
    {
        $request->validate([
            'monto' => 'required|numeric|min:0',
            'metodo_pago' => 'required|string',
            'fecha' => 'required|date',
            'referencia' => 'nullable|string|max:255',
        ]);

        $pagado_previamente = $venta->pagos->sum('monto');
        $saldo_restante = $venta->total - $pagado_previamente;

        if ($request->monto > $saldo_restante + 0.01) { // Tolerancia decimal
            return back()->with('error', 'El monto ingresado excede el saldo restante.');
        }

        $venta->pagos()->create([
            'monto' => $request->monto,
            'metodo_pago' => $request->metodo_pago,
            'fecha' => $request->fecha,
            'referencia' => $request->referencia,
            'created_by' => Auth::id(),
        ]);

        // Actualizar estado_pago de la venta
        $total_ahora = $pagado_previamente + $request->monto;
        if ($total_ahora >= $venta->total - 0.01) {
            $venta->update(['estado_pago' => 'pagado']);
        } else {
            $venta->update(['estado_pago' => 'parcial']);
        }

        return back()->with('success', 'Pago registrado correctamente.');
    }
}
