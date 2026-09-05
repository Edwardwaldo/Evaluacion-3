<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo Proyecto (Eval. 2 - actualizacion del Project.php de la Eval. 1).
 *
 * En la Eval. 1 este modelo no usaba Eloquent y guardaba los datos en
 * sesion (sin base de datos), tal como pedia esa evaluacion. En la
 * Eval. 2 se actualiza para usar el ORM de Laravel (Eloquent) y persistir
 * en la tabla "proyectos", agregando ademas el campo created_by con el
 * id del usuario autenticado que creo el proyecto.
 */
class Proyecto extends Model
{
    use HasFactory;

    protected $table = 'proyectos';

    protected $fillable = [
        'nombre',
        'fecha_inicio',
        'estado',
        'responsable',
        'monto',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'fecha_inicio' => 'date',
            'monto' => 'decimal:2',
        ];
    }

    // Relacion: el proyecto pertenece al usuario que lo creo
    public function creador()
    {
        return $this->belongsTo(Usuario::class, 'created_by');
    }
}
