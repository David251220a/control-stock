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
        Schema::create('resumen_mensuals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tipo_ingreso_id')->nullable()->constrained();
            $table->foreignId('tipo_egreso_id')->nullable()->constrained();
            $table->string('tipo_movimiento', 2);
            $table->decimal('egreso', 18, 0)->default(0);
            $table->decimal('ingreso', 18, 0)->default(0);
            $table->integer('anio');
            $table->tinyInteger('mes');
            $table->date('fecha_calculo')->nullable();
            $table->unsignedBigInteger('usuario_calculo')->nullable();
            $table->string('observacion', 250);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resumen_mensuals');
    }
};
