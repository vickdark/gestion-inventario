<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cotizaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained('clientes')->onDelete('cascade');
            $table->string('numero_cotizacion')->unique();
            $table->date('fecha_emision');
            $table->date('fecha_vencimiento');
            $table->dateTime('fecha_evento_inicio')->nullable();
            $table->dateTime('fecha_evento_fin')->nullable();
            $table->enum('tipo_alquiler', ['dia', 'hora'])->default('dia');
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('impuesto', 15, 2)->default(0);
            $table->decimal('total', 15, 2)->default(0);
            $table->enum('estado', ['pendiente', 'aprobada', 'rechazada', 'convertida', 'vencida'])->default('pendiente');
            $table->text('notas')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cotizaciones');
    }
};
