@extends('layouts.app')

@section('contenido')

{{-- Vista de confirmación antes de eliminar un proyecto (requerimiento 3) --}}

<h1>Eliminar proyecto</h1>

<p>
    ¿Estás seguro que deseas eliminar el proyecto
    <strong>#{{ $proyecto->id }} - {{ $proyecto->nombre }}</strong>?
    Esta acción no se puede deshacer.
</p>

<form action="{{ route('projects.destroy', $proyecto->id) }}" method="POST">
    @csrf
    @method('DELETE')

    <button type="submit">Sí, eliminar</button>
</form>

<p><a href="{{ route('projects.index') }}">Cancelar y volver al listado</a></p>

@endsection
