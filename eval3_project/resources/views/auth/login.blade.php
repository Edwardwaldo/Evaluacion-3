@extends('layouts.app')

@section('contenido')

<div class="auth-card">
    <h1>Iniciar sesión</h1>

    @if ($errors->any())
        <div class="form-errors">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('login.attempt') }}" method="POST">
        @csrf

        <label>Correo:</label><br>
        <input type="email" name="correo" value="{{ old('correo') }}" required><br><br>

        <label>Clave:</label><br>
        <input type="password" name="clave" required><br><br>

        <button type="submit">Ingresar</button>
    </form>

    <p>¿No tienes cuenta? <a href="{{ route('register') }}">Regístrate aquí</a></p>
</div>

@endsection
