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
        Schema::create('citas', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->time('tiempo_inicio');
            $table->time('tiempo_final');
            $table->string('razon',100);
            $table->char('estado', 1)->default('S');
            $table->unsignedBigInteger('horario_id');
            $table->unsignedBigInteger('paciente_id');
            $table->timestamps();
            $table->foreign('horario_id')->references('id')->on('horarios')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('paciente_id')->references('id')->on('perfiles_pacientes')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('citas');
    }
};
