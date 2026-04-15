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
                'descripcion' => 'Cabina de sonido profesional de alta fidelidad, ideal para eventos de hasta 100 personas.',
                'precio_dia' => 80000,
                'precio_hora' => 15000,
                'cantidad_total' => 6,
                'estado' => 'Disponible',
                'imagen' => 'https://images.unsplash.com/photo-1589156229687-496a31ad1d1f?q=80&w=800&auto=format&fit=crop'
            ],
            [
                'nombre' => 'Consola Behringer 12 Canales',
                'categoria' => 'Sonido',
                'descripcion' => 'Mezcladora de audio con efectos digitales y conectividad USB para grabación.',
                'precio_dia' => 60000,
                'precio_hora' => 10000,
                'cantidad_total' => 2,
                'estado' => 'Disponible',
                'imagen' => 'https://images.unsplash.com/photo-1598488035139-bdbb2231ce04?q=80&w=800&auto=format&fit=crop'
            ],
            [
                'nombre' => 'Micrófono Inalámbrico Shure SM58',
                'categoria' => 'Sonido',
                'descripcion' => 'Micrófono de mano inalámbrico de alta calidad, ideal para conferencias y presentaciones.',
                'precio_dia' => 35000,
                'precio_hora' => 7000,
                'cantidad_total' => 10,
                'estado' => 'Disponible',
                'imagen' => 'https://images.unsplash.com/photo-1558317374-067fb5f30001?q=80&w=800&auto=format&fit=crop'
            ],
            [
                'nombre' => 'Cabeza Móvil LED 90W',
                'categoria' => 'Iluminación',
                'descripcion' => 'Luz robótica profesional con gobos y colores para shows en vivo y fiestas.',
                'precio_dia' => 45000,
                'precio_hora' => 8000,
                'cantidad_total' => 8,
                'estado' => 'Disponible',
                'imagen' => 'https://images.unsplash.com/photo-1516280440614-37939bbacd81?q=80&w=800&auto=format&fit=crop'
            ],
            [
                'nombre' => 'Par LED 18x12 RGBW',
                'categoria' => 'Iluminación',
                'descripcion' => 'Reflector LED de alta potencia para iluminación arquitectónica o rítmica.',
                'precio_dia' => 15000,
                'precio_hora' => 3000,
                'cantidad_total' => 20,
                'estado' => 'Disponible',
                'imagen' => 'https://images.unsplash.com/photo-1574391884720-bbe3740057d3?q=80&w=800&auto=format&fit=crop'
            ],
            [
                'nombre' => 'Silla Tiffany Blanca',
                'categoria' => 'Mobiliario',
                'descripcion' => 'Silla clásica de resina blanca con cojín, perfecta para bodas y recepciones.',
                'precio_dia' => 2500,
                'precio_hora' => 500,
                'cantidad_total' => 300,
                'estado' => 'Disponible',
                'imagen' => 'https://images.unsplash.com/photo-1519225421980-715cb0215aed?q=80&w=800&auto=format&fit=crop'
            ],
            [
                'nombre' => 'Mesa Redonda 10 Puestos',
                'categoria' => 'Mobiliario',
                'descripcion' => 'Mesa de madera con patas plegables, ideal para banquetes y eventos sociales.',
                'precio_dia' => 15000,
                'precio_hora' => 3000,
                'cantidad_total' => 30,
                'estado' => 'Disponible',
                'imagen' => 'https://images.unsplash.com/photo-1467453162181-6a814324ef26?q=80&w=800&auto=format&fit=crop'
            ],
            [
                'nombre' => 'Inflable Castillo de Princesas',
                'categoria' => 'Inflables',
                'descripcion' => 'Castillo inflable temático rosa y lila con zona de salto y obstáculos.',
                'precio_dia' => 120000,
                'precio_hora' => 25000,
                'cantidad_total' => 1,
                'estado' => 'Disponible',
                'imagen' => 'https://images.unsplash.com/photo-1572942850446-291d2ef96885?q=80&w=800&auto=format&fit=crop'
            ],
            [
                'nombre' => 'Planta Eléctrica 3000W',
                'categoria' => 'Sonido',
                'descripcion' => 'Generador de energía a gasolina, portátil y silencioso para eventos en exteriores.',
                'precio_dia' => 180000,
                'precio_hora' => 35000,
                'cantidad_total' => 2,
                'estado' => 'Disponible',
                'imagen' => 'https://images.unsplash.com/photo-1620912189865-1e8a33da4c5e?q=80&w=800&auto=format&fit=crop'
            ],
            [
                'nombre' => 'Carpa 6x6 Metros',
                'categoria' => 'Carpas y Tarimas',
                'descripcion' => 'Carpa estructural de lona blanca, resistente a la lluvia y al sol.',
                'precio_dia' => 250000,
                'precio_hora' => 50000,
                'cantidad_total' => 4,
                'estado' => 'Disponible',
                'imagen' => 'https://images.unsplash.com/photo-1464366400600-7168b8af9bc3?q=80&w=800&auto=format&fit=crop'
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
