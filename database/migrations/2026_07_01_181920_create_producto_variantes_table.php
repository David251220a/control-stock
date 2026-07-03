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
        Schema::create('producto_variantes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('producto_id')->constrained();
            $table->string('codigo', 30)->unique();
            $table->string('codigo_barra')->nullable()->unique();
            $table->decimal('precio_compra', 18, 2)->default(0);
            $table->decimal('precio_venta', 18, 2)->default(0);
            $table->integer('stock_minimo')->default(0);
            $table->decimal('peso', 10, 2)->nullable();
            $table->foreignId('estado_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->unsignedBigInteger('usuario_modificacion')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('producto_variantes');
    }
};
