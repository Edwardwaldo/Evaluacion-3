@extends('layouts.app')

@section('contenido')

<div class="auth-card">
    <h1>Crear cuenta</h1>

    @if ($errors->any())
        <div class="form-errors">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('register.store') }}" method="POST">
        @csrf

        <label>Nombre:</label><br>
        <input type="text" name="nombre" value="{{ old('nombre') }}" required><br><br>

        <label>Correo:</label><br>
        <input type="email" name="correo" value="{{ old('correo') }}" required><br><br>

        <label>Clave:</label><br>
        <input type="password" name="clave" required minlength="6"><br><br>

        <label>Confirmar clave:</label><br>
        <input type="password" name="clave_confirmation" required minlength="6"><br><br>

        <button type="submit">Registrarme</button>
    </form>

    <p>¿Ya tienes cuenta? <a href="{{ route('login') }}">Inicia sesión aquí</a></p>
</div>

@endsection
