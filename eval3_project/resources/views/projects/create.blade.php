@extends('layouts.app')

@section('contenido')

{{-- Formulario para crear un nuevo proyecto (requerimiento 2) --}}

<h1>Agregar proyecto</h1>

<form action="{{ route('projects.store') }}" method="POST">
    @csrf

    <label>Nombre:</label><br>
    <input type="text" name="nombre"><br><br>

    <label>Fecha de inicio:</label><br>
    <input type="date" name="fecha_inicio"><br><br>

    <label>Estado:</label><br>
    <select name="estado">
        <option value="Planificado">Planificado</option>
        <option value="En curso">En curso</option>
        <option value="Finalizado">Finalizado</option>
    </select><br><br>

    <label>Responsable:</label><br>
    <input type="text" name="responsable"><br><br>

    <label>Monto:</label><br>
    <input type="number" name="monto"><br><br>

    <button type="submit">Guardar proyecto</button>
</form>

<p><a href="{{ route('projects.index') }}">Cancelar y volver al listado</a></p>

@endsection