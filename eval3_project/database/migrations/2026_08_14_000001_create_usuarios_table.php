<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Requerimiento Eval. 2: modelo Usuario con Id, Nombre, Correo
 * (identificador unico) y Clave, respaldado por Eloquent/ORM.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 150);
            $table->string('correo')->unique();
            $table->string('clave'); // se guarda cifrada (bcrypt) desde el controlador
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
