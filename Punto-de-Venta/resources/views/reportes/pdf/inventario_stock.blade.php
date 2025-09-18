<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Inventario - {{ $fecha }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .header { text-align: center; margin-bottom: 30px; }
        .header h1 { color: #2c3e50; margin: 0; }
        .header p { color: #7f8c8d; margin: 5px 0; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #f8f9fa; font-weight: bold; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .stock-bajo { color: #e74c3c; font-weight: bold; }
        .stock-medio { color: #f39c12; }
        .stock-alto { color: #27ae60; }
        .footer { margin-top: 50px; text-align: center; color: #7f8c8d; font-size: 12px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Reporte de Inventario y Stock</h1>
        <p>Al {{ $fecha }}</p>
        <p>Total de productos: {{ $productos->count() }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Código</th>
                <th>Producto</th>
                <th>Marca</th>
                <th>Presentación</th>
                <th class="text-center">Stock</th>
                <th class="text-center">Estado</th>
            </tr>
        </thead>
        <tbody>
            @forelse($productos as $producto)
            <tr>
                <td>{{ $producto->codigo }}</td>
                <td>{{ $producto->nombre }}</td>
                <td>{{ $producto->marca->caracteristica->nombre ?? 'N/A' }}</td>
                <td>{{ $producto->presentacione->caracteristica->nombre ?? 'N/A' }}</td>
                <td class="text-center">{{ $producto->stock_disponible }}</td>
                <td class="text-center">
                    @if($producto->stock_disponible <= 0)
                    <span class="stock-bajo">SIN STOCK</span>
                    @elseif($producto->stock_disponible <= 10)
                    <span class="stock-bajo">BAJO</span>
                    @elseif($producto->stock_disponible <= 25)
                    <span class="stock-medio">MEDIO</span>
                    @else
                    <span class="stock-alto">ALTO</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">No hay productos registrados</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Sistema de Ventas - Generado automáticamente</p>
    </div>
</body>
</html>