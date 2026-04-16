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
        Schema::table('ventas', function (Blueprint $table) {
            $table->string('direccion_evento')->nullable()->after('fecha_evento_fin');
            $table->string('ubicacion_link')->nullable()->after('direccion_evento');
            $table->string('vehiculo')->nullable()->after('ubicacion_link');
            $table->text('personal_asignado')->nullable()->after('vehiculo');
            $table->enum('estado_logistica', ['pendiente', 'en_montaje', 'montado', 'recogiendo', 'finalizado'])->default('pendiente')->after('personal_asignado');
            $table->integer('progreso_logistica')->default(0)->after('estado_logistica');
            $table->text('notas_logistica')->nullable()->after('progreso_logistica');
        });
    }

    public function down(): void
    {
        Schema::table('ventas', function (Blueprint $table) {
            $table->dropColumn([
                'direccion_evento',
                'ubicacion_link',
                'vehiculo',
                'personal_asignado',
                'estado_logistica',
                'progreso_logistica',
                'notas_logistica'
            ]);
        });
    }
};
