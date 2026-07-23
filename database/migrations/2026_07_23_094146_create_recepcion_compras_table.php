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
        Schema::create('recepcion_compras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('orden_compra_id')->constrained('orden_compras');
            $table->unsignedInteger('numero');
            $table->date('fecha_recepcion');
            // Remisión entregada por el proveedor
            $table->string('numero_remision', 50)->nullable();
            // 0=Borrador, 1=Confirmada, 2=Anulada
            $table->unsignedTinyInteger('estado_recepcion')->default(0);
            $table->text('observacion')->nullable();
            $table->unsignedTinyInteger('estado_id')->default(1);
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('usuario_modificacion')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recepcion_compras');
    }
};
