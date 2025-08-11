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
        Schema::create('horarios', function (Blueprint $table) {
            $table->id();
            $table->integer('dia_semana');
            $table->time('tiempo_inicio');
            $table->time('tiempo_final');
            $table->time('duracion_cita');
            $table->char('estado', 1)->default('S');
            $table->unsignedBigInteger('doctor_id');
            $table->timestamps();
            $table->foreign('doctor_id')->references('id')->on('perfiles_doctores')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('horarios');
    }
};
