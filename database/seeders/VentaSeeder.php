<?php

namespace Database\Seeders;

use App\Models\Venta;
use App\Models\VentaItem;
use App\Models\Cliente;
use App\Models\Equipo;
use App\Models\Paquete;
use App\Models\Usuarios\Usuario as User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class VentaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clientes = Cliente::all();
        $equipos = Equipo::all();
        $paquetes = Paquete::all();
        $user = User::first();

        if ($clientes->isEmpty() || ($equipos->isEmpty() && $paquetes->isEmpty())) {
            return;
        }

        $estados_pago = ['pendiente', 'pagado', 'parcial', 'anulado'];

        // Obtener el último número de factura existente
        $ultimaVenta = Venta::orderBy('id', 'desc')->first();
        $contador = $ultimaVenta ? (int) str_replace('FAC-', '', $ultimaVenta->numero_factura) + 1 : 1;

        for ($i = 0; $i < 10; $i++) {
            $cliente = $clientes->random();
            $tipo_alquiler = $i % 2 == 0 ? 'dia' : 'hora';
            $fecha_venta = Carbon::now()->subDays(rand(1, 30));
            
            $numero_factura = 'FAC-' . str_pad($contador, 5, '0', STR_PAD_LEFT);

            $venta = Venta::create([
                'cliente_id' => $cliente->id,
                'numero_factura' => $numero_factura,
                'fecha_venta' => $fecha_venta,
                'fecha_evento_inicio' => (clone $fecha_venta)->addDays(20)->setHour(10),
                'fecha_evento_fin' => (clone $fecha_venta)->addDays(20)->setHour(22),
                'tipo_alquiler' => $tipo_alquiler,
                'estado_pago' => $estados_pago[array_rand($estados_pago)],
                'notas' => 'Esta es una venta de ejemplo generada por el seeder.',
                'created_by' => $user->id ?? null,
                'subtotal' => 0,
                'impuesto' => 0,
                'total' => 0,
            ]);

            $total = 0;
            
            // Agregar 2-3 equipos aleatorios
            $randomEquipos = $equipos->random(rand(1, 3));
            foreach ($randomEquipos as $equipo) {
                $cantidad = rand(1, 5);
                $precio = $tipo_alquiler === 'dia' ? $equipo->precio_dia : $equipo->precio_hora;
                $subtotal = $cantidad * $precio;
                
                VentaItem::create([
                    'venta_id' => $venta->id,
                    'itemable_id' => $equipo->id,
                    'itemable_type' => Equipo::class,
                    'nombre' => $equipo->nombre,
                    'cantidad' => $cantidad,
                    'precio_unitario' => $precio,
                    'subtotal' => $subtotal,
                ]);
                $total += $subtotal;
            }

            // Agregar 1 paquete aleatorio ocasionalmente
            if (rand(0, 1) && $paquetes->isNotEmpty()) {
                $paquete = $paquetes->random();
                $cantidad = 1;
                $precio = $tipo_alquiler === 'dia' ? $paquete->precio_dia : $paquete->precio_hora;
                $subtotal = $cantidad * $precio;

                VentaItem::create([
                    'venta_id' => $venta->id,
                    'itemable_id' => $paquete->id,
                    'itemable_type' => Paquete::class,
                    'nombre' => $paquete->nombre,
                    'cantidad' => $cantidad,
                    'precio_unitario' => $precio,
                    'subtotal' => $subtotal,
                ]);
                $total += $subtotal;
            }

            $venta->update([
                'subtotal' => $total,
                'total' => $total,
            ]);

            // Generar algunos gastos
            if ($venta->estado_pago !== 'anulado') {
                $numGastos = rand(1, 3);
                $tipos = ['Combustible', 'Peajes', 'Alimentación', 'Otros'];
                for ($j = 0; $j < $numGastos; $j++) {
                    $venta->gastos()->create([
                        'tipo_gasto' => $tipos[array_rand($tipos)],
                        'monto' => rand(20000, 100000),
                        'descripcion' => 'Gasto de seeder',
                        'fecha' => $venta->fecha_venta,
                        'created_by' => $user->id ?? null,
                    ]);
                }

                // Generar pagos según el estado
                if ($venta->estado_pago === 'pagado') {
                    $venta->pagos()->create([
                        'monto' => $venta->total,
                        'metodo_pago' => 'Transferencia',
                        'fecha' => $venta->fecha_venta,
                        'created_by' => $user->id ?? null,
                    ]);
                } elseif ($venta->estado_pago === 'parcial') {
                    $venta->pagos()->create([
                        'monto' => $venta->total * 0.5,
                        'metodo_pago' => 'Efectivo',
                        'fecha' => $venta->fecha_venta,
                        'created_by' => $user->id ?? null,
                    ]);
                }
            }

            $contador++;
        }
    }
}
