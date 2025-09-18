<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Top {{ $limite }} Productos Más Vendidos - {{ $nombreMes }} {{ $anio }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .header { text-align: center; margin-bottom: 30px; }
        .header h1 { color: #2c3e50; margin: 0; }
        .header p { color: #7f8c8d; margin: 5px 0; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #f8f9fa; font-weight: bold; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .top-1 { background-color: #fff9c4; }
        .top-2 { background-color: #f5f5f5; }
        .top-3 { background-color: #ffe0b2; }
        .footer { margin-top: 50px; text-align: center; color: #7f8c8d; font-size: 12px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Top {{ $limite }} Productos Más Vendidos</h1>
        <p>{{ $nombreMes }} de {{ $anio }}</p>
        <p>Generado el: {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Producto</th>
                <th>Código</th>
                <th class="text-center">Unidades Vendidas</th>
                <th class="text-right">Monto Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($productos as $index => $producto)
            <tr class="top-{{ $index < 3 ? $index + 1 : '' }}">
                <td>{{ $index + 1 }}</td>
                <td>{{ $producto->nombre }}</td>
                <td>{{ $producto->codigo }}</td>
                <td class="text-center">{{ $producto->total_vendido }}</td>
                <td class="text-right">S/ {{ number_format($producto->monto_total, 2) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center">No hay ventas registradas en este período</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($productos->isNotEmpty())
    <div style="margin-top: 30px;">
        <p><strong>Total unidades vendidas:</strong> {{ $productos->sum('total_vendido') }}</p>
        <p><strong>Monto total:</strong> $/ {{ number_format($productos->sum('monto_total'), 2) }}</p>
    </div>
    @endif

    <div class="footer">
        <p>Sistema de Ventas - Generado automáticamente</p>
    </div>
</body>
</html>