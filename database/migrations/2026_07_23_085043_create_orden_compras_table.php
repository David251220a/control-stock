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
        Schema::create('orden_compras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('persona_id')->constrained();
            $table->unsignedInteger('numero');
            $table->date('fecha_orden');
            $table->date('fecha_entrega_estimada')->nullable();
            $table->decimal('subtotal', 18, 0)->default(0);
            $table->decimal('descuento', 18, 0)->default(0);
            $table->decimal('monto_total', 18, 0)->default(0);
            // 0=Borrador, 1=Emitida, 2=Recepción parcial,
            // 3=Recepción completada, 4=Cancelada
            $table->unsignedTinyInteger('estado_orden')->default(0);
            $table->unsignedTinyInteger('condicion_pago')->default(1);
            $table->unsignedSmallInteger('cantidad_cuotas')->default(1);
            // 1=No pagado, 2=Pago parcial, 3=Pago total
            $table->unsignedTinyInteger('estado_pago')->default(1);
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
        Schema::dropIfExists('orden_compras');
    }
};
