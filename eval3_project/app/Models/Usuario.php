<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

/**
 * Modelo Usuario (Eval. 2).
 * A diferencia de la Eval. 1, este modelo SI usa Eloquent/ORM y persiste
 * en la tabla "usuarios" de la base de datos configurada en .env.
 *
 * Campos minimos requeridos: Id, Nombre, Correo (identificador unico), Clave.
 */
class Usuario extends Model
{
    use HasFactory, Notifiable;

    protected $table = 'usuarios';

    protected $fillable = [
        'nombre',
        'correo',
        'clave',
    ];

    protected $hidden = [
        'clave',
        'remember_token',
    ];

    /**
     * Requerimiento: cifrado de la clave.
     * Al usar el cast 'hashed', Laravel aplica bcrypt automaticamente
     * cada vez que se asigna un valor nuevo a "clave" (create/update),
     * sin necesidad de llamar Hash::make() manualmente en el controlador.
     */
    protected function casts(): array
    {
        return [
            'clave' => 'hashed',
        ];
    }

    // Relacion: un usuario puede haber creado muchos proyectos
    public function proyectos()
    {
        return $this->hasMany(Proyecto::class, 'created_by');
    }
}
