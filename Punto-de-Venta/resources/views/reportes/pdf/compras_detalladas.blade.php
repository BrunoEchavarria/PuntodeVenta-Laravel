<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte Detallado de Compras - {{ $nombreMes }} {{ $anio }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #e74c3c; padding-bottom: 20px; }
        .header h1 { color: #2c3e50; margin: 0; font-size: 24px; }
        .header p { color: #7f8c8d; margin: 5px 0; }
        .summary { display: flex; justify-content: space-around; margin: 20px 0; background: #f8f9fa; padding: 15px; border-radius: 5px; }
        .summary-item { text-align: center; }
        .summary-number { font-size: 18px; font-weight: bold; color: #2c3e50; }
        .summary-label { font-size: 12px; color: #7f8c8d; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #e74c3c; color: white; font-weight: bold; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .total-row { background-color: #e8f5e8; font-weight: bold; }
        .compra-header { background-color: #3498db; color: white; padding: 8px; margin: 15px 0 5px 0; border-radius: 3px; }
        .product-row { background-color: #f9f9f9; }
        .footer { margin-top: 50px; text-align: center; color: #7f8c8d; font-size: 12px; border-top: 1px solid #ddd; padding-top: 20px; }
        .page-break { page-break-before: always; }
    </style>
</head>
<body>
    <div class="header">
        <h1>📦 Reporte Detallado de Compras</h1>
        <p>{{ $nombreMes }} de {{ $anio }}</p>
        <p>Generado el: {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <div class="summary">
        <div class="summary-item">
            <div class="summary-number">{{ $totalCompras }}</div>
            <div class="summary-label">TOTAL COMPRAS</div>
        </div>
        <div class="summary-item">
            <div class="summary-number">{{ $totalProductos }}</div>
            <div class="summary-label">PRODUCTOS COMPRADOS</div>
        </div>
        <div class="summary-item">
            <div class="summary-number">S/ {{ number_format($totalMonto, 2) }}</div>
            <div class="summary-label">INVERSIÓN TOTAL</div>
        </div>
    </div>

    <h3>📋 Detalle de Compras por Orden</h3>
    @forelse($compras as $compra)
    <div class="compra-header">
        Compra #{{ $compra->id }} - {{ \Carbon\Carbon::parse($compra->fecha_hora)->format('d/m/Y H:i') }} - 
        {{ $compra->proveedore->persona->razon_social }} - 
        Comprobante: {{ $compra->comprobante->nombre ?? 'N/A' }} {{ $compra->numero_comprobante }}
    </div>

    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th class="text-center">Cantidad</th>
                <th class="text-right">Precio Compra</th>
                <th class="text-right">Precio Venta</th>
                <th class="text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($compra->productos as $producto)
            <tr class="product-row">
                <td>{{ $producto->nombre }} ({{ $producto->codigo }})</td>
                <td class="text-center">{{ $producto->pivot->cantidad }}</td>
                <td class="text-right">S/ {{ number_format($producto->pivot->precio_compra, 2) }}</td>
                <td class="text-right">S/ {{ number_format($producto->pivot->precio_venta, 2) }}</td>
                <td class="text-right">S/ {{ number_format($producto->pivot->cantidad * $producto->pivot->precio_compra, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="4" class="text-right"><strong>Total Compra:</strong></td>
                <td class="text-right"><strong>S/ {{ number_format($compra->total, 2) }}</strong></td>
            </tr>
        </tfoot>
    </table>
    @empty
    <p>No hay compras registradas en este período</p>
    @endforelse

    @if($productosComprados->isNotEmpty())
    <div class="page-break"></div>
    <h3>📊 Productos Más Comprados</h3>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Producto</th>
                <th>Código</th>
                <th class="text-center">Unidades Compradas</th>
                <th class="text-right">Inversión Total</th>
                <th class="text-right">% del Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($productosComprados as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item['producto']->nombre }}</td>
                <td>{{ $item['producto']->codigo }}</td>
                <td class="text-center">{{ $item['cantidad_total'] }}</td>
                <td class="text-right">S/ {{ number_format($item['monto_total'], 2) }}</td>
                <td class="text-right">{{ number_format(($item['monto_total'] / $totalMonto) * 100, 1) }}%</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <div class="footer">
        <p>Sistema de Ventas - Generado automáticamente</p>
        <p>Reporte de Compras Detallado - Página {{ $pdf->getPageNumber() }} de {{ $pdf->getPageCount() }}</p>
    </div>
</body>
</html>