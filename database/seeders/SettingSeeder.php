<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // General
            [
                'key' => 'app_name',
                'value' => 'Gestion de Inventario',
                'display_name' => 'Nombre de la Aplicación',
                'group' => 'general',
                'type' => 'text',
            ],
            [
                'key' => 'sidebar_title',
                'value' => 'Administración de Inventario',
                'display_name' => 'Título Lateral',
                'group' => 'general',
                'type' => 'text',
            ],
            [
                'key' => 'app_logo',
                'value' => null,
                'display_name' => 'Logo de la Aplicación',
                'group' => 'general',
                'type' => 'file',
            ],
            // Empresa / Facturación
            [
                'key' => 'company_name',
                'value' => 'Mi Empresa S.A.S',
                'display_name' => 'Nombre de la Empresa',
                'group' => 'empresa',
                'type' => 'text',
            ],
            [
                'key' => 'company_id',
                'value' => '900.000.000-1',
                'display_name' => 'NIT / RUC / RFC',
                'group' => 'empresa',
                'type' => 'text',
            ],
            [
                'key' => 'company_address',
                'value' => 'Calle 123 # 45 - 67, Ciudad',
                'display_name' => 'Dirección',
                'group' => 'empresa',
                'type' => 'text',
            ],
            [
                'key' => 'company_phone',
                'value' => '+57 300 000 0000',
                'display_name' => 'Teléfono de Contacto',
                'group' => 'empresa',
                'type' => 'text',
            ],
            [
                'key' => 'company_email',
                'value' => 'contacto@empresa.com',
                'display_name' => 'Correo Electrónico',
                'group' => 'empresa',
                'type' => 'text',
            ],
            [
                'key' => 'currency_symbol',
                'value' => '$',
                'display_name' => 'Símbolo de Moneda',
                'group' => 'empresa',
                'type' => 'text',
            ],
            [
                'key' => 'invoice_footer',
                'value' => 'Gracias por su preferencia. Esta cotización tiene una validez de 15 días.',
                'display_name' => 'Pie de página (Cotizaciones/Facturas)',
                'group' => 'empresa',
                'type' => 'textarea',
            ],
        ];

        foreach ($settings as $setting) {
            \App\Models\Setting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
