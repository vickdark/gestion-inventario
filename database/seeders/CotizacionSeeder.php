<?php

namespace Database\Seeders;

use App\Models\Cotizacion;
use App\Models\CotizacionItem;
use App\Models\Cliente;
use App\Models\Equipo;
use App\Models\Paquete;
use App\Models\Usuarios\Usuario as User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class CotizacionSeeder extends Seeder
{
    public function run(): void
    {
        $clientes = Cliente::all();
        $equipos = Equipo::all();
        $paquetes = Paquete::all();
        $user = User::first();

        if ($clientes->isEmpty() || $equipos->isEmpty()) {
            return;
        }

        $estados = ['pendiente', 'aprobada', 'rechazada', 'convertida'];

        // Obtener último número de cotización existente
        $ultimaCotizacion = Cotizacion::orderBy('id', 'desc')->first();
        $contador = $ultimaCotizacion ? (int) str_replace('COT-', '', $ultimaCotizacion->numero_cotizacion) + 1 : 1;

        for ($i = 0; $i < 10; $i++) {
            $cliente = $clientes->random();
            $tipo_alquiler = $i % 2 == 0 ? 'dia' : 'hora';
            $fecha_emision = Carbon::now()->subDays(rand(1, 30));
            $fecha_vencimiento = (clone $fecha_emision)->addDays(15);
            
            $numero_cotizacion = 'COT-' . str_pad($contador, 5, '0', STR_PAD_LEFT);

            $cotizacion = Cotizacion::create([
                'cliente_id' => $cliente->id,
                'numero_cotizacion' => $numero_cotizacion,
                'fecha_emision' => $fecha_emision,
                'fecha_vencimiento' => $fecha_vencimiento,
                'fecha_evento_inicio' => (clone $fecha_emision)->addDays(20)->setHour(10),
                'fecha_evento_fin' => (clone $fecha_emision)->addDays(20)->setHour(22),
                'tipo_alquiler' => $tipo_alquiler,
                'estado' => $estados[array_rand($estados)],
                'notas' => 'Esta es una cotización de ejemplo generada por el seeder.',
                'created_by' => $user->id ?? null,
                'subtotal' => 0,
                'total' => 0,
            ]);

            $total = 0;
            
            // Agregar 2-3 equipos aleatorios
            $randomEquipos = $equipos->random(rand(1, 3));
            foreach ($randomEquipos as $equipo) {
                $cantidad = rand(1, 5);
                $precio = $tipo_alquiler === 'dia' ? $equipo->precio_dia : $equipo->precio_hora;
                $subtotal = $cantidad * $precio;
                
                CotizacionItem::create([
                    'cotizacion_id' => $cotizacion->id,
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

                CotizacionItem::create([
                    'cotizacion_id' => $cotizacion->id,
                    'itemable_id' => $paquete->id,
                    'itemable_type' => Paquete::class,
                    'nombre' => $paquete->nombre,
                    'cantidad' => $cantidad,
                    'precio_unitario' => $precio,
                    'subtotal' => $subtotal,
                ]);
                $total += $subtotal;
            }

            $cotizacion->update([
                'subtotal' => $total,
                'total' => $total,
            ]);

            $contador++;
        }
    }
}
