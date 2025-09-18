<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Ventas - {{ $nombreMes }} {{ $anio }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .header { text-align: center; margin-bottom: 30px; }
        .header h1 { color: #2c3e50; margin: 0; }
        .header p { color: #7f8c8d; margin: 5px 0; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #f8f9fa; font-weight: bold; }
        .total-row { background-color: #e8f5e8; font-weight: bold; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .footer { margin-top: 50px; text-align: center; color: #7f8c8d; font-size: 12px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Reporte de Ventas Mensuales</h1>
        <p>{{ $nombreMes }} de {{ $anio }}</p>
        <p>Generado el: {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Fecha</th>
                <th class="text-center">N° Ventas</th>
                <th class="text-right">Monto Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($ventas as $venta)
            <tr>
                <td>{{ \Carbon\Carbon::parse($venta->fecha)->format('d/m/Y') }}</td>
                <td class="text-center">{{ $venta->total_ventas }}</td>
                <td class="text-right">S/ {{ number_format($venta->monto_total, 2) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="3" class="text-center">No hay ventas registradas en este período</td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td><strong>TOTAL GENERAL</strong></td>
                <td class="text-center"><strong>{{ $totalVentas }}</strong></td>
                <td class="text-right"><strong>$/ {{ number_format($totalGeneral, 2) }}</strong></td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <p>Sistema de Ventas - Generado automáticamente</p>
    </div>
</body>
</html>