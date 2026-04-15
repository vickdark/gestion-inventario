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
        Schema::create('equipos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('categoria_id')->constrained('categoria_equipos')->onDelete('cascade');
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->decimal('precio_dia', 12, 2)->default(0);
            $table->integer('cantidad_total')->default(0);
            $table->integer('cantidad_disponible')->default(0);
            $table->string('imagen')->nullable();
            $table->enum('estado', ['Disponible', 'Mantenimiento', 'Baja'])->default('Disponible');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipos');
    }
};
