<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('bitacora', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable(); // Usuario que hizo la acción
            $table->string('accion'); // Tipo de acción (login, logout, creación, actualización, eliminación)
            $table->string('tabla_afectada')->nullable(); // Tabla que fue afectada
            $table->unsignedBigInteger('registro_id')->nullable(); // ID del registro afectado
            $table->json('detalles')->nullable(); // Datos del evento (por ejemplo, cambios realizados)
            $table->timestamp('fecha_evento')->useCurrent(); // Fecha del evento

            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('bitacora');
    }
};
