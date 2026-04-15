<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Paquete;
use App\Models\Equipo;

class PaqueteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Paquete Fiesta Básica
        $paqueteFesta = Paquete::updateOrCreate(
            ['nombre' => 'Combo Fiesta Básica'],
            [
                'descripcion' => 'La solución perfecta para reuniones en casa o salones pequeños. Incluye sonido JBL, luces ambientales y silletería.',
                'precio_dia' => 150000,
                'precio_hora' => 30000,
                'imagen' => 'https://images.unsplash.com/photo-1492684223066-81342ee5ff30?q=80&w=800&auto=format&fit=crop',
                'activo' => true
            ]
        );

        $equipoSonido = Equipo::where('nombre', 'Cabina Activa JBL 15"')->first();
        $equipoLuces = Equipo::where('nombre', 'Par LED 18x12 RGBW')->first();
        $equipoSillas = Equipo::where('nombre', 'Silla Tiffany Blanca')->first();
        $equipoMicro = Equipo::where('nombre', 'Micrófono Inalámbrico Shure SM58')->first();

        if ($equipoSonido) $paqueteFesta->equipos()->syncWithoutDetaching([$equipoSonido->id => ['cantidad' => 1]]);
        if ($equipoLuces) $paqueteFesta->equipos()->syncWithoutDetaching([$equipoLuces->id => ['cantidad' => 2]]);
        if ($equipoSillas) $paqueteFesta->equipos()->syncWithoutDetaching([$equipoSillas->id => ['cantidad' => 50]]);
        if ($equipoMicro) $paqueteFesta->equipos()->syncWithoutDetaching([$equipoMicro->id => ['cantidad' => 1]]);

        // 2. Paquete Corporativo VIP
        $paqueteCorp = Paquete::updateOrCreate(
            ['nombre' => 'Combo Corporativo VIP'],
            [
                'descripcion' => 'Diseñado para lanzamientos de marca y conferencias. Equipamiento de gama alta con iluminación robótica y carpa estructural.',
                'precio_dia' => 600000,
                'precio_hora' => 120000,
                'imagen' => 'https://images.unsplash.com/photo-1505373877841-8d25f7d46678?q=80&w=800&auto=format&fit=crop',
                'activo' => true
            ]
        );

        $equipoConsola = Equipo::where('nombre', 'Consola Behringer 12 Canales')->first();
        $equipoCabezas = Equipo::where('nombre', 'Cabeza Móvil LED 90W')->first();
        $equipoCarpa = Equipo::where('nombre', 'Carpa 6x6 Metros')->first();

        if ($equipoSonido) $paqueteCorp->equipos()->syncWithoutDetaching([$equipoSonido->id => ['cantidad' => 2]]);
        if ($equipoConsola) $paqueteCorp->equipos()->syncWithoutDetaching([$equipoConsola->id => ['cantidad' => 1]]);
        if ($equipoCabezas) $paqueteCorp->equipos()->syncWithoutDetaching([$equipoCabezas->id => ['cantidad' => 2]]);
        if ($equipoCarpa) $paqueteCorp->equipos()->syncWithoutDetaching([$equipoCarpa->id => ['cantidad' => 1]]);
        if ($equipoMicro) $paqueteCorp->equipos()->syncWithoutDetaching([$equipoMicro->id => ['cantidad' => 2]]);

        // 3. Paquete Infantil
        $paqueteKids = Paquete::updateOrCreate(
            ['nombre' => 'Plan Diversión Infantil'],
            [
                'descripcion' => 'Diversión garantizada para los más pequeños con nuestro castillo de princesas y sistema de audio para juegos y música.',
                'precio_dia' => 180000,
                'precio_hora' => 40000,
                'imagen' => 'https://images.unsplash.com/photo-1533777857889-4be7c70b33f7?q=80&w=800&auto=format&fit=crop',
                'activo' => true
            ]
        );

        $equipoInflable = Equipo::where('nombre', 'Inflable Castillo de Princesas')->first();

        if ($equipoInflable) $paqueteKids->equipos()->syncWithoutDetaching([$equipoInflable->id => ['cantidad' => 1]]);
        if ($equipoSonido) $paqueteKids->equipos()->syncWithoutDetaching([$equipoSonido->id => ['cantidad' => 1]]);
    }
}
