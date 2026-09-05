@extends('layouts.app')

@section('contenido')
{{-- Vista que muestra el detalle de un solo proyecto (requerimiento 5) --}}

<h1>Detalle del proyecto</h1>

<table>
    <tr>
        <th>ID</th>
        <td>{{ $proyecto->id }}</td>
    </tr>
    <tr>
        <th>Nombre</th>
        <td>{{ $proyecto->nombre }}</td>
    </tr>
    <tr>
        <th>Fecha de inicio</th>
        <td>{{ $proyecto->fecha_inicio->format('d-m-Y') }}</td>
    </tr>
    <tr>
        <th>Estado</th>
        <td>{{ $proyecto->estado }}</td>
    </tr>
    <tr>
        <th>Responsable</th>
        <td>{{ $proyecto->responsable }}</td>
    </tr>
    <tr>
        <th>Monto</th>
        <td>{{ number_format($proyecto->monto, 0, ',', '.') }}</td>
    </tr>
    <tr>
        <th>Creado por</th>
        <td>{{ $proyecto->creador->nombre ?? '—' }}</td>
    </tr>
</table>

<p><a href="{{ route('projects.index') }}">Volver al listado</a></p>

@endsection
