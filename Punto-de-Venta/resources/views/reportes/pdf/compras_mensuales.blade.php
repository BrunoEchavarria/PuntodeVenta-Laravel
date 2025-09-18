<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Compras - {{ $nombreMes }} {{ $anio }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #3498db; padding-bottom: 20px; }
        .header h1 { color: #2c3e50; margin: 0; font-size: 24px; }
        .header p { color: #7f8c8d; margin: 5px 0; }
        .summary { display: flex; justify-content: space-around; margin: 20px 0; background: #f8f9fa; padding: 15px; border-radius: 5px; }
        .summary-item { text-align: center; }
        .summary-number { font-size: 18px; font-weight: bold; color: #2c3e50; }
        .summary-label { font-size: 12px; color: #7f8c8d; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #3498db; color: white; font-weight: bold; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .total-row { background-color: #e8f5e8; font-weight: bold; }
        .proveedor-section { margin: 30px 0; }
        .proveedor-header { background-color: #e74c3c; color: white; padding: 10px; border-radius: 5px; }
        .footer { margin-top: 50px; text-align: center; color: #7f8c8d; font-size: 12px; border-top: 1px solid #ddd; padding-top: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>📦 Reporte de Compras Mensuales</h1>
        <p>{{ $nombreMes }} de {{ $anio }}</p>
        <p>Generado el: {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <div class="summary">
        <div class="summary-item">
            <div class="summary-number">{{ $totalCompras }}</div>
            <div class="summary-label">TOTAL COMPRAS</div>
        </div>
        <div class="summary-item">
            <div class="summary-number">S/ {{ number_format($totalSubtotal, 2) }}</div>
            <div class="summary-label">SUBTOTAL</div>
        </div>
        <div class="summary-item">
            <div class="summary-number">S/ {{ number_format($totalImpuesto, 2) }}</div>
            <div class="summary-label">IMPUESTO</div>
        </div>
        <div class="summary-item">
            <div class="summary-number">S/ {{ number_format($totalMonto, 2) }}</div>
            <div class="summary-label">MONTO TOTAL</div>
        </div>
    </div>

    <h3>📋 Detalle de Compras</h3>
    <table>
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Comprobante</th>
                <th>N° Comprobante</th>
                <th>Proveedor</th>
                <th class="text-right">Subtotal</th>
                <th class="text-right">Impuesto</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($compras as $compra)
            <tr>
                <td>{{ \Carbon\Carbon::parse($compra->fecha_hora)->format('d/m/Y') }}</td>
                <td>{{ $compra->comprobante->nombre ?? 'N/A' }}</td>
                <td>{{ $compra->numero_comprobante }}</td>
                <td>{{ $compra->proveedore->persona->razon_social }}</td>
                <td class="text-right">S/ {{ number_format($compra->subtotal, 2) }}</td>
                <td class="text-right">S/ {{ number_format($compra->impuesto, 2) }}</td>
                <td class="text-right">S/ {{ number_format($compra->total, 2) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center">No hay compras registradas en este período</td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="4"><strong>TOTAL GENERAL</strong></td>
                <td class="text-right"><strong>S/ {{ number_format($totalSubtotal, 2) }}</strong></td>
                <td class="text-right"><strong>S/ {{ number_format($totalImpuesto, 2) }}</strong></td>
                <td class="text-right"><strong>S/ {{ number_format($totalMonto, 2) }}</strong></td>
            </tr>
        </tfoot>
    </table>

    @if($proveedores->isNotEmpty())
    <div class="proveedor-section">
        <h3>🏢 Compras por Proveedor</h3>
        <table>
            <thead>
                <tr>
                    <th>Proveedor</th>
                    <th class="text-center">N° Compras</th>
                    <th class="text-right">Monto Total</th>
                    <th class="text-right">% del Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($proveedores as $proveedor)
                <tr>
                    <td>{{ $proveedor['nombre'] }}</td>
                    <td class="text-center">{{ $proveedor['cantidad'] }}</td>
                    <td class="text-right">S/ {{ number_format($proveedor['total'], 2) }}</td>
                    <td class="text-right">{{ number_format(($proveedor['total'] / $totalMonto) * 100, 1) }}%</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <div class="footer">
        <p>Sistema de Ventas - Generado automáticamente</p>
        <p>Reporte de Compras - Página {{ $pdf->getPageNumber() }} de {{ $pdf->getPageCount() }}</p>
    </div>
</body>
</html>