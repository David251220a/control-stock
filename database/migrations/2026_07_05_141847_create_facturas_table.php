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
        Schema::create('facturas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('persona_id')->constrained();
            $table->foreignId('timbrado_id')->constrained();
            $table->foreignId('establecimiento_id')->constrained();
            $table->integer('registro_id')->default(0);
            $table->string('factura_sucursal', 3)->default('000');
            $table->string('factura_general', 3)->default('000');
            $table->integer('factura_numero')->default(0);
            $table->date('fecha_factura')->nullable();
            $table->foreignId('tipo_documento_id')->constrained()->default(1);
            $table->foreignId('tipo_transaccion_id')->constrained()->default(2);
            $table->integer('condicion_pago')->default(1)->comment('1-CONTADO 2-CREDITO');
            $table->tinyInteger('estado_pago')->default(0)->comment('0-REGISTRADO 1-PAGADO 2-ANULADO');
            $table->string('concepto');
            $table->integer('plazo')->default(0);
            $table->decimal('monto_total', 12, 0)->default(0);
            $table->decimal('monto_abonado', 12, 0)->default(0);
            $table->decimal('monto_devuelto', 12, 0)->default(0);
            $table->foreignId('estado_id')->constrained();
            $table->tinyInteger('generado_sifen')->default(0);
            $table->date('fecha_anulado')->nullable();
            $table->text('observacion')->nullable();
            $table->unsignedBigInteger('usuario_pago')->nullable();
            $table->foreignId('user_id')->constrained();
            $table->unsignedBigInteger('usuario_anulacion')->nullable();
            $table->string('motivo_anulacion', 250)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facturas');
    }
};
