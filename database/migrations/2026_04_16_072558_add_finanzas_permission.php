<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $id = \DB::table('permissions')->insertGetId([
            'nombre' => 'Finanzas',
            'slug' => 'eventos.finanzas.index',
            'parent_id' => null,
            'icon' => 'fa-solid fa-coins',
            'is_menu' => true,
            'order' => 10,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $adminRole = \DB::table('roles')->where('nombre', 'Administrador')->first();
        if ($adminRole) {
            \DB::table('permission_role')->insert([
                'role_id' => $adminRole->id,
                'permission_id' => $id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
