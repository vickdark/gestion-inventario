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
        Schema::table('paquetes', function (Blueprint $table) {
            $table->decimal('precio_dia', 12, 2)->default(0)->after('descripcion');
            $table->decimal('precio_hora', 12, 2)->default(0)->after('precio_dia');
            $table->dropColumn('precio_total');
        });
    }

    public function down(): void
    {
        Schema::table('paquetes', function (Blueprint $table) {
            $table->decimal('precio_total', 12, 2)->default(0)->after('descripcion');
            $table->dropColumn(['precio_dia', 'precio_hora']);
        });
    }
};
