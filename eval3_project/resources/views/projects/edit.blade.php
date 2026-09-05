@extends('layouts.app')

@section('contenido')

{{-- Formulario para editar un proyecto existente (requerimiento 4) --}}

<h1>Editar proyecto #{{ $proyecto->id }}</h1>

<form action="{{ route('projects.update', $proyecto->id) }}" method="POST">
    @csrf
    @method('PUT')

    <label>Nombre:</label><br>
    <input type="text" name="nombre" value="{{ old('nombre', $proyecto->nombre) }}"><br><br>

    <label>Fecha de inicio:</label><br>
    <input type="date" name="fecha_inicio" value="{{ old('fecha_inicio', $proyecto->fecha_inicio->format('Y-m-d')) }}"><br><br>

    <label>Estado:</label><br>
    <select name="estado">
        <option value="Planificado" @selected(old('estado', $proyecto->estado) == 'Planificado')>Planificado</option>
        <option value="En curso" @selected(old('estado', $proyecto->estado) == 'En curso')>En curso</option>
        <option value="Finalizado" @selected(old('estado', $proyecto->estado) == 'Finalizado')>Finalizado</option>
    </select><br><br>

    <label>Responsable:</label><br>
    <input type="text" name="responsable" value="{{ old('responsable', $proyecto->responsable) }}"><br><br>

    <label>Monto:</label><br>
    <input type="number" name="monto" value="{{ old('monto', $proyecto->monto) }}"><br><br>

    <button type="submit">Actualizar proyecto</button>
</form>

<p><a href="{{ route('projects.index') }}">Cancelar y volver al listado</a></p>

@endsection
