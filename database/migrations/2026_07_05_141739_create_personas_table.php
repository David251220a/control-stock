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
        Schema::create('personas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tipo_persona_id')->constrained();
            $table->foreignId('sexo_id')->constrained();
            $table->tinyInteger('estado_civil')->default(0);
            $table->string('documento', 20);
            $table->string('ruc')->nullable();
            $table->string('nombre', 200);
            $table->string('apellido', 200)->nullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->text('direccion')->nullable();
            $table->string('barrio', 250)->nullable();
            $table->string('celular')->nullable();
            $table->string('email',250);
            $table->foreignId('estado_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->unsignedBigInteger('usuario_modificacion');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personas');
    }
};
