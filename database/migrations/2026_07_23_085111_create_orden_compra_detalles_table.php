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
        Schema::create('orden_compra_detalles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('orden_compra_id')->constrained('orden_compras');
            $table->foreignId('producto_variante_id')->constrained('producto_variantes');
            $table->unsignedInteger('cantidad_solicitada');
            $table->unsignedInteger('cantidad_recibida')->default(0);
            $table->unsignedInteger('cantidad_cancelada')->default(0);
            $table->decimal('precio_unitario', 18, 0)->default(0);
            $table->decimal('subtotal', 18, 0)->default(0);
            $table->decimal('descuento', 18, 0)->default(0);
            $table->decimal('monto_total', 18, 0)->default(0);
            $table->text('observacion')->nullable();
            $table->unsignedTinyInteger('estado_id')->default(1);
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('usuario_modificacion')->nullable()->constrained('users');
            $table->timestamps();
            // Evita cargar dos veces la misma variante en una orden
            $table->unique(
                ['orden_compra_id', 'producto_variante_id'],
                'orden_compra_variante_unique'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orden_compra_detalles');
    }
};
