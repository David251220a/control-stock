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
        Schema::create('recepcion_compra_detalles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recepcion_compra_id')->constrained('recepcion_compras');
            $table->foreignId('orden_compra_detalle_id')->constrained('orden_compra_detalles');
            $table->unsignedInteger('cantidad_recibida');
            $table->text('observacion')->nullable();
            $table->unsignedTinyInteger('estado_id')->default(1);
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('usuario_modificacion')->nullable()->constrained('users');
            $table->timestamps();

            $table->unique(
                ['recepcion_compra_id', 'orden_compra_detalle_id'],
                'recepcion_orden_detalle_unique'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recepcion_compra_detalles');
    }
};
