@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Reportes y Estadísticas</h1>
    
    <div class="row">
        <!-- Gráfico de Compras Mensuales -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Compras Mensuales ({{ date('Y') }})</div>
                <div class="card-body">
                    <canvas id="comprasChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Gráfico de Ventas Mensuales -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Ventas Mensuales ({{ date('Y') }})</div>
                <div class="card-body">
                    <canvas id="ventasChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mt-4">
        <!-- Top 5 Productos Más Vendidos -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Top 5 Productos Más Vendidos</div>
                <div class="card-body">
                    <canvas id="productosChart" width="400" height="70"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Incluir Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Datos para los gráficos (pasados desde el controlador)
        const comprasData = @json($comprasMensuales);
        const ventasData = @json($ventasMensuales);
        const productosData = @json($topProductos);
        
        // Gráfico de Compras Mensuales
        const comprasCtx = document.getElementById('comprasChart').getContext('2d');
        new Chart(comprasCtx, {
            type: 'bar',
            data: {
                labels: comprasData.map(item => item.mes),
                datasets: [{
                    label: 'Compras Mensuales',
                    data: comprasData.map(item => item.total),
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
        
        // Gráfico de Ventas Mensuales
        const ventasCtx = document.getElementById('ventasChart').getContext('2d');
        new Chart(ventasCtx, {
            type: 'bar',
            data: {
                labels: ventasData.map(item => item.mes),
                datasets: [{
                    label: 'Ventas Mensuales',
                    data: ventasData.map(item => item.total),
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
        
        // Gráfico de Top Productos
        const productosCtx = document.getElementById('productosChart').getContext('2d');
        new Chart(productosCtx, {
            type: 'bar',
            data: {
                labels: productosData.map(item => item.nombre),
                datasets: [{
                    label: 'Cantidad Vendida',
                    data: productosData.map(item => item.cantidad),
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                indexAxis: 'y',
                scales: {
                    x: {
                        beginAtZero: true
                    }
                }
            }
        });
    });
</script>
@endsection