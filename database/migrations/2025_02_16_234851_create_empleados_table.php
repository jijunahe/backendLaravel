<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('empleados', function (Blueprint $table) {
            $table->id();
            $table->string('nombres');
            $table->string('apellidos');
            $table->string('email')->unique();
            $table->string('cargo');
            $table->date('fecha_nacimiento');
            $table->date('fecha_ingreso');
            $table->enum('estado', ['activo', 'dado de baja', 'de vacaciones', 'permiso', 'licencia'])->default('activo');
            $table->timestamps(); // Esto genera 'created_at' y 'updated_at'
        });
    }

    public function down()
    {
        Schema::dropIfExists('empleados');
    }
};
