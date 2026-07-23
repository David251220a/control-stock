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
        Schema::create('orden_compra_pagos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('orden_compra_id')->constrained('orden_compras');
            // Factura presentada para realizar este pago
            $table->string('numero_factura', 30);
            $table->string('timbrado', 20)->nullable();
            $table->date('fecha_factura');
            $table->date('fecha_pago');
            $table->decimal('exento', 18, 0)->default(0);
            $table->decimal('gravado_5', 18, 0)->default(0);
            $table->decimal('iva_5', 18, 0)->default(0);
            $table->decimal('gravado_10', 18, 0)->default(0);
            $table->decimal('iva_10', 18, 0)->default(0);
            $table->decimal('monto', 18, 0);
            $table->foreignId('forma_cobro_id')->constrained('forma_cobros');
            $table->foreignId('banco_id')->nullable()->constrained('bancos');
            $table->unsignedSmallInteger('anio');
            $table->unsignedInteger('numero_recibo');
            $table->text('observacion')->nullable();
            $table->unsignedTinyInteger('estado_id')->default(1);
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('usuario_modificacion')->nullable()->constrained('users');
            $table->timestamps();

            $table->unique(
                ['orden_compra_id', 'timbrado', 'numero_factura'],
                'orden_compra_factura_unique'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orden_compra_pagos');
    }
};
