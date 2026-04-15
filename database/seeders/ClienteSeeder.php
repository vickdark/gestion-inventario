<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cliente;

class ClienteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clientes = [
            // Personas Naturales
            [
                'nombre' => 'Juan Alberto Pérez',
                'documento' => '1010202030',
                'email' => 'juan.perez@email.com',
                'telefono' => '3001234567',
                'direccion' => 'Calle 45 # 12-34, Bogotá',
                'tipo' => 'Persona',
                'notas' => 'Cliente frecuente para eventos familiares.',
            ],
            [
                'nombre' => 'María Camila García',
                'documento' => '1020304050',
                'email' => 'maria.garcia@email.com',
                'telefono' => '3109876543',
                'direccion' => 'Carrera 15 # 85-20, Medellín',
                'tipo' => 'Persona',
                'notas' => 'Prefiere contacto por WhatsApp.',
            ],
            [
                'nombre' => 'Carlos Andrés López',
                'documento' => '1030405060',
                'email' => 'carlos.lopez@email.com',
                'telefono' => '3151112233',
                'direccion' => 'Calle 100 # 11-45, Bogotá',
                'tipo' => 'Persona',
                'notas' => 'Interesado en paquetes de sonido.',
            ],
            [
                'nombre' => 'Ana Sofía Rodríguez',
                'documento' => '1040506070',
                'email' => 'ana.rodriguez@email.com',
                'telefono' => '3204445566',
                'direccion' => 'Av. El Poblado # 10-10, Medellín',
                'tipo' => 'Persona',
                'notas' => null,
            ],
            [
                'nombre' => 'Diego Fernando Torres',
                'documento' => '1050607080',
                'email' => 'diego.torres@email.com',
                'telefono' => '3017778899',
                'direccion' => 'Calle 72 # 50-12, Barranquilla',
                'tipo' => 'Persona',
                'notas' => 'Referido por Juan Pérez.',
            ],
            // Empresas
            [
                'nombre' => 'Eventos y Logística S.A.S',
                'documento' => '900123456-1',
                'email' => 'contacto@eventoslogistica.com',
                'telefono' => '6014445566',
                'direccion' => 'Zona Industrial, Bogotá',
                'tipo' => 'Empresa',
                'notas' => 'Aliado estratégico para eventos corporativos.',
            ],
            [
                'nombre' => 'Tecnología Global Ltda',
                'documento' => '800987654-2',
                'email' => 'gerencia@tecglobal.com',
                'telefono' => '6043332211',
                'direccion' => 'Parque Tecnológico, Medellín',
                'tipo' => 'Empresa',
                'notas' => 'Requiere facturación electrónica detallada.',
            ],
            [
                'nombre' => 'Hoteles del Sol S.A.',
                'documento' => '890111222-3',
                'email' => 'reservas@hotelessol.com',
                'telefono' => '6056667788',
                'direccion' => 'Sector Turístico, Cartagena',
                'tipo' => 'Empresa',
                'notas' => 'Alquiler mensual de mobiliario.',
            ],
            [
                'nombre' => 'Constructora Horizonte',
                'documento' => '901222333-4',
                'email' => 'proyectos@horizonte.com',
                'telefono' => '6018889900',
                'direccion' => 'Av. 68 # 20-30, Bogotá',
                'tipo' => 'Empresa',
                'notas' => 'Eventos de inauguración de proyectos.',
            ],
            [
                'nombre' => 'Colegio San Ignacio',
                'documento' => '860444555-5',
                'email' => 'administrativo@sanignacio.edu.co',
                'telefono' => '6042223344',
                'direccion' => 'Barrio Laureles, Medellín',
                'tipo' => 'Empresa',
                'notas' => 'Alquiler de carpas y tarimas para grados.',
            ],
        ];

        foreach ($clientes as $cliente) {
            Cliente::updateOrCreate(['documento' => $cliente['documento']], $cliente);
        }
    }
}
