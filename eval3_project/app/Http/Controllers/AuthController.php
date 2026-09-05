<?php

namespace App\Http\Controllers;

/**
 * Controlador de Autenticacion (Eval. 2).
 *
 * Se encarga de las dos funciones pedidas por el requerimiento:
 *  - registro(): crea un Usuario nuevo cifrando la clave.
 *  - login(): valida que las credenciales (correo/clave) sean correctas.
 *
 * La sesion (usuario_id / usuario_nombre) es la que despues revisa el
 * middleware EnsureUsuarioAutenticado para proteger las rutas de
 * proyectos, siguiendo el estandar de "controladores + middleware para
 * autorizacion" pedido en la rubrica.
 */

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    // Muestra el formulario de registro
    public function mostrarRegistro()
    {
        return view('auth.register');
    }

    // Procesa el registro de un usuario nuevo
    public function registro(Request $request)
    {
        $validado = $request->validate([
            'nombre' => 'required|string|max:150',
            'correo' => ['required', 'email', 'max:150', Rule::unique('usuarios', 'correo')],
            'clave' => 'required|string|min:6|confirmed',
        ], [
            'correo.unique' => 'Ese correo ya está registrado.',
            'clave.confirmed' => 'La confirmación de la clave no coincide.',
        ]);

        // Cifrado de la clave: el modelo Usuario aplica el cast 'hashed'
        // (bcrypt) automáticamente al asignar este atributo.
        $usuario = Usuario::create([
            'nombre' => $validado['nombre'],
            'correo' => $validado['correo'],
            'clave' => $validado['clave'],
        ]);

        // Deja al usuario autenticado inmediatamente después de registrarse
        $request->session()->regenerate();
        $request->session()->put('usuario_id', $usuario->id);
        $request->session()->put('usuario_nombre', $usuario->nombre);

        return redirect()->route('projects.index')->with('success', 'Cuenta creada correctamente. ¡Bienvenido/a, ' . $usuario->nombre . '!');
    }

    // Muestra el formulario de inicio de sesión
    public function mostrarLogin()
    {
        return view('auth.login');
    }

    // Valida las credenciales e inicia sesión
    public function login(Request $request)
    {
        $credenciales = $request->validate([
            'correo' => 'required|email',
            'clave' => 'required|string',
        ]);

        $usuario = Usuario::where('correo', $credenciales['correo'])->first();

        // Verifica existencia del usuario y que la clave coincida con el hash guardado
        if (!$usuario || !Hash::check($credenciales['clave'], $usuario->clave)) {
            return back()->withInput(['correo' => $credenciales['correo']])
                ->with('error', 'Correo o clave incorrectos.');
        }

        $request->session()->regenerate();
        $request->session()->put('usuario_id', $usuario->id);
        $request->session()->put('usuario_nombre', $usuario->nombre);

        return redirect()->route('projects.index')->with('success', 'Sesión iniciada correctamente.');
    }

    // Cierra la sesión del usuario autenticado
    public function logout(Request $request)
    {
        $request->session()->forget(['usuario_id', 'usuario_nombre']);
        $request->session()->regenerate();

        return redirect()->route('login')->with('success', 'Sesión cerrada correctamente.');
    }
}
