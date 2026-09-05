{{-- Vista del componente reutilizable que muestra el valor de la UF del día --}}
<p>
    <strong>Valor UF del día:</strong>
    {{ number_format($uf['valor'], 2, ',', '.') }}
    ({{ $uf['fecha'] }})
    <br>
    <small>{{ $uf['origen'] }}</small>
</p>