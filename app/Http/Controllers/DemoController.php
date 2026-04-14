<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DemoController extends Controller
{
    public function dashboard()
    {
        return view('eventos.dashboard', [
            'eventos_dia' => 5,
            'eventos_proximos' => 12,
            'equipos_ocupados' => 45,
            'equipos_disponibles' => 120,
            'cotizaciones_pendientes' => 8,
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
        return view('eventos.agenda.index');
    }

    public function cotizaciones()
    {
        return view('eventos.cotizaciones.index');
    }

    public function logistica()
    {
        return view('eventos.logistica.index');
    }
}
