<?php

namespace App\Http\Controllers\Api;

/**
 * API REST de Proyectos (Eval. 3).
 *
 * Implementa el CRUD completo contra la Base de Datos usando el modelo
 * Eloquent Proyecto (creado en la Eval. 2), respondiendo siempre en JSON
 * con los codigos de estado HTTP pedidos por la rubrica:
 *
 *   POST   /api/proyectos       -> 201 Created
 *   GET    /api/proyectos       -> 200 OK  (arreglo vacio si no hay datos)
 *   GET    /api/proyectos/{id}  -> 200 OK  | 404 Not Found
 *   PUT    /api/proyectos/{id}  -> 200 OK  | 404 Not Found
 *   PATCH  /api/proyectos/{id}  -> 200 OK  | 404 Not Found
 *   DELETE /api/proyectos/{id}  -> 204 No Content | 404 Not Found
 */

use App\Http\Controllers\Controller;
use App\Models\Proyecto;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProyectoApiController extends Controller
{
    /**
     * Reglas de validacion compartidas por store() y update().
     * Todos los campos son requeridos y no pueden estar vacios.
     */
    private function reglas(): array
    {
        return [
            'nombre' => ['required', 'string', 'max:150'],
            'fecha_inicio' => ['required', 'date'],
            'estado' => ['required', 'string', 'max:50'],
            'responsable' => ['required', 'string', 'max:150'],
            'monto' => ['required', 'numeric', 'min:0'],
            // El usuario que crea/es dueño del proyecto debe existir en la tabla usuarios.
            'created_by' => ['required', 'integer', 'exists:usuarios,id'],
        ];
    }

    // GET /api/proyectos -> 200, incluye un arreglo vacio si no hay registros
    public function index()
    {
        $proyectos = Proyecto::orderBy('id')->get();

        return response()->json($proyectos, Response::HTTP_OK);
    }

    // POST /api/proyectos -> 201 si se crea correctamente
    public function store(Request $request)
    {
        $validado = $request->validate($this->reglas());

        $proyecto = Proyecto::create($validado);

        return response()->json($proyecto, Response::HTTP_CREATED);
    }

    // GET /api/proyectos/{id} -> 200 si existe, 404 si no existe
    public function show(string $id)
    {
        $proyecto = Proyecto::find($id);

        if (!$proyecto) {
            return response()->json([
                'message' => "No existe un proyecto con id {$id}.",
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json($proyecto, Response::HTTP_OK);
    }

    // PUT/PATCH /api/proyectos/{id} -> 200 con los campos actualizados, 404 si no existe
    public function update(Request $request, string $id)
    {
        $proyecto = Proyecto::find($id);

        if (!$proyecto) {
            return response()->json([
                'message' => "No existe un proyecto con id {$id}.",
            ], Response::HTTP_NOT_FOUND);
        }

        $validado = $request->validate($this->reglas());

        $proyecto->update($validado);

        return response()->json($proyecto->fresh(), Response::HTTP_OK);
    }

    // DELETE /api/proyectos/{id} -> 204 (respuesta vacia) si se elimina, 404 si no existe
    public function destroy(string $id)
    {
        $proyecto = Proyecto::find($id);

        if (!$proyecto) {
            return response()->json([
                'message' => "No existe un proyecto con id {$id}.",
            ], Response::HTTP_NOT_FOUND);
        }

        $proyecto->delete();

        return response()->noContent(); // 204, cuerpo vacio
    }
}
