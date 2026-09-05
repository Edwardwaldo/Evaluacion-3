<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Requerimiento Eval. 2: modelo Proyecto (actualizado desde la Eval. 1)
 * ahora respaldado por Base de Datos/ORM, incluyendo created_by como
 * referencia al usuario autenticado que creo el registro.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proyectos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 150);
            $table->date('fecha_inicio');
            $table->string('estado', 50);
            $table->string('responsable', 150);
            $table->decimal('monto', 12, 2);
            $table->foreignId('created_by')
                ->constrained('usuarios')
                ->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proyectos');
    }
};
