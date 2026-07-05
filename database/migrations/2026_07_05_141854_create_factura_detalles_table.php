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
        Schema::create('factura_detalles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('factura_id')->constrained();
            $table->foreignId('producto_id')->constrained();
            $table->foreignId('producto_variante_id')->constrained();
            $table->decimal('precio', 18, 2)->default(0);
            $table->integer('cantidad')->default(0);
            $table->decimal('precio_total', 18, 2)->default(0);
            $table->tinyInteger('afecta_iva')->default(0)->comment('0=EXENTO, 5=IVA 5%, 10=IVA 10%');
            $table->decimal('grabado_10', 18, 2)->default(0);
            $table->decimal('grabado_5', 18, 2)->default(0);
            $table->decimal('iva_10', 18, 2)->default(0);
            $table->decimal('iva_5', 18, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('factura_detalles');
    }
};
