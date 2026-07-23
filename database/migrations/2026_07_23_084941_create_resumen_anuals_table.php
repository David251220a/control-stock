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
        Schema::create('resumen_anuals', function (Blueprint $table) {
            $table->id();
            $table->integer('anio');
            $table->decimal('saldo_inicial', 18, 0)->default(0);
            $table->decimal('total_egreso', 18, 0)->default(0);
            $table->decimal('total_ingreso', 18, 0)->default(0);
            $table->decimal('saldo_final', 18, 0)->default(0);
            $table->date('fecha_calculo')->nullable();
            $table->unsignedBigInteger('usuario_calculo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resumen_anuals');
    }
};
