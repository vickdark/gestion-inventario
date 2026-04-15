<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CategoriaEquipo;
use App\Models\Equipo;
use Illuminate\Support\Str;

class EquipoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categorias = [
            ['nombre' => 'Sonido', 'descripcion' => 'Equipos de audio profesional, bafles y consolas.'],
            ['nombre' => 'Iluminación', 'descripcion' => 'Luces LED, cabezas móviles y efectos.'],
            ['nombre' => 'Mobiliario', 'descripcion' => 'Sillas, mesas y decoración.'],
            ['nombre' => 'Inflables', 'descripcion' => 'Castillos, saltarines y juegos inflables.'],
            ['nombre' => 'Carpas y Tarimas', 'descripcion' => 'Estructuras para eventos al aire libre.'],
        ];

        foreach ($categorias as $cat) {
            $categoria = CategoriaEquipo::updateOrCreate(
                ['slug' => Str::slug($cat['nombre'])],
                ['nombre' => $cat['nombre'], 'descripcion' => $cat['descripcion']]
            );
        }

        $equipos = [
            [
                'nombre' => 'Cabina Activa JBL 15"',
                'categoria' => 'Sonido',
                'descripcion' => 'Cabina de sonido profesional con Bluetooth y trípode.',
                'precio_dia' => 80000,
                'precio_hora' => 15000,
                'cantidad_total' => 6,
                'estado' => 'Disponible',
                'imagen' => 'https://images.unsplash.com/photo-1545127398-14699f92334b?q=80&w=400&auto=format&fit=crop'
            ],
            [
                'nombre' => 'Consola Behringer 12 Canales',
                'categoria' => 'Sonido',
                'descripcion' => 'Mezcladora de audio con efectos digitales para eventos en vivo.',
                'precio_dia' => 60000,
                'precio_hora' => 10000,
                'cantidad_total' => 2,
                'estado' => 'Disponible',
                'imagen' => 'https://images.unsplash.com/photo-1598488035139-bdbb2231ce04?q=80&w=400&auto=format&fit=crop'
            ],
            [
                'nombre' => 'Cabeza Móvil LED 90W',
                'categoria' => 'Iluminación',
                'descripcion' => 'Luz robótica para efectos de escenario y fiestas.',
                'precio_dia' => 45000,
                'precio_hora' => 8000,
                'cantidad_total' => 8,
                'estado' => 'Disponible',
                'imagen' => 'https://images.unsplash.com/photo-1516280440614-37939bbacd81?q=80&w=400&auto=format&fit=crop'
            ],
            [
                'nombre' => 'Par LED 18x12 RGBW',
                'categoria' => 'Iluminación',
                'descripcion' => 'Reflector LED rítmico para ambientación de espacios.',
                'precio_dia' => 15000,
                'precio_hora' => 3000,
                'cantidad_total' => 20,
                'estado' => 'Disponible',
                'imagen' => 'https://images.unsplash.com/photo-1492684223066-81342ee5ff30?q=80&w=400&auto=format&fit=crop'
            ],
            [
                'nombre' => 'Silla Tiffany Blanca',
                'categoria' => 'Mobiliario',
                'descripcion' => 'Silla elegante con cojín para eventos sociales.',
                'precio_dia' => 2500,
                'precio_hora' => 500,
                'cantidad_total' => 300,
                'estado' => 'Disponible',
                'imagen' => 'https://images.unsplash.com/photo-1519167758481-83f550bb49b3?q=80&w=400&auto=format&fit=crop'
            ],
            [
                'nombre' => 'Inflable Castillo de Princesas',
                'categoria' => 'Inflables',
                'descripcion' => 'Inflable temático de 3x3 metros para niñas.',
                'precio_dia' => 120000,
                'precio_hora' => 25000,
                'cantidad_total' => 1,
                'estado' => 'Disponible',
                'imagen' => 'https://images.unsplash.com/photo-1520110120835-c96534a4c984?q=80&w=400&auto=format&fit=crop'
            ],
            [
                'nombre' => 'Planta Eléctrica 3000W',
                'categoria' => 'Sonido',
                'descripcion' => 'Generador a gasolina silencioso para eventos exteriores.',
                'precio_dia' => 180000,
                'precio_hora' => 35000,
                'cantidad_total' => 2,
                'estado' => 'Disponible',
                'imagen' => 'https://images.unsplash.com/photo-1620912189865-1e8a33da4c5e?q=80&w=400&auto=format&fit=crop'
            ],
            [
                'nombre' => 'Carpa 6x6 Metros',
                'categoria' => 'Carpas y Tarimas',
                'descripcion' => 'Carpa estructural blanca con paredes laterales opcionales.',
                'precio_dia' => 250000,
                'precio_hora' => 50000,
                'cantidad_total' => 4,
                'estado' => 'Disponible',
                'imagen' => 'https://images.unsplash.com/photo-1533174072545-7a4b6ad7a6c3?q=80&w=400&auto=format&fit=crop'
            ],
        ];

        foreach ($equipos as $eq) {
            $catId = CategoriaEquipo::where('nombre', $eq['categoria'])->first()->id;
            Equipo::updateOrCreate(
                ['nombre' => $eq['nombre']],
                [
                    'categoria_id' => $catId,
                    'descripcion' => $eq['descripcion'],
                    'precio_dia' => $eq['precio_dia'],
                    'precio_hora' => $eq['precio_hora'],
                    'cantidad_total' => $eq['cantidad_total'],
                    'cantidad_disponible' => $eq['cantidad_total'],
                    'estado' => $eq['estado'],
                    'imagen' => $eq['imagen']
                ]
            );
        }
    }
}
