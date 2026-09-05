@extends('layouts.app')

@section('contenido')

<h1>Listado de proyectos</h1>

<p>
    <a href="{{ route('projects.create') }}">Agregar proyecto</a>
</p>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Fecha inicio</th>
            <th>Estado</th>
            <th>Responsable</th>
            <th>Monto</th>
            <th>Creado por</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>

        @forelse ($proyectos as $proyecto)
        <tr>
            <td>{{ $proyecto->id }}</td>
            <td>{{ $proyecto->nombre }}</td>
            <td>{{ $proyecto->fecha_inicio->format('d-m-Y') }}</td>
            <td>{{ $proyecto->estado }}</td>
            <td>{{ $proyecto->responsable }}</td>
            <td>{{ number_format($proyecto->monto, 0, ',', '.') }}</td>
            <td>{{ $proyecto->creador->nombre ?? '—' }}</td>
            <td>
                <a href="{{ route('projects.show', $proyecto->id) }}">Ver</a>
                |
                <a href="{{ route('projects.edit', $proyecto->id) }}">Editar</a>
                |
                <a href="{{ route('projects.confirmDelete', $proyecto->id) }}">Eliminar</a>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="8">Aún no hay proyectos registrados.</td>
        </tr>
        @endforelse
    </tbody>
</table>

@endsection
