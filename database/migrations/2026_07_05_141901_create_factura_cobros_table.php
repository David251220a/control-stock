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
        Schema::create('factura_cobros', function (Blueprint $table) {
            $table->id();
            $table->foreignId('factura_id')->constrained();
            $table->foreignId('forma_cobro_id')->constrained();
            $table->date('fecha')->default(now());
            $table->foreignId('banco_id')->constrained();
            $table->string('numero_comprobante', 100)->nullable();
            $table->decimal('monto', 12 ,0)->default(0);
            $table->foreignId('estado_id')->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('factura_cobros');
    }
};
