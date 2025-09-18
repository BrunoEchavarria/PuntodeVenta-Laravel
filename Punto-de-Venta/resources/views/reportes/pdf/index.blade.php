@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="my-4 text-center">Generar Reportes PDF</h1>

    <div class="row">

        <!-- Reporte de Ventas Mensuales -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5>💰 Ventas Mensuales</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('reportes.pdf.ventas') }}" method="GET">
                        <div class="mb-3">
                            <label class="form-label">Mes:</label>
                            <select name="mes" class="form-select">
                                @for($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ $i == date('m') ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create()->month($i)->locale('es')->monthName }}
                                </option>
                                @endfor
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Año:</label>
                            <input type="number" name="anio" class="form-control" value="{{ date('Y') }}" min="2020" max="2030">
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            📄 Generar PDF
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Reporte de Inventario -->
        <div class="col-md-6 mb-4">
            <div class="card" style="height: 100%;">
                <div class="card-header bg-success text-white">
                    <h5>📦 Inventario y Stock</h5>
                </div>
                <div class="card-body text-center d-flex flex-column justify-content-between">
                    <p class="text-muted">Reporte completo de productos y stock disponible</p>
                    <p>Al hacer click en "Generar" este se descarga automaticamente.</p>
                    <form action="{{ route('reportes.pdf.inventario') }}" method="GET">
                        <button type="submit" class="btn btn-success w-100">
                            📄 Generar PDF
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Productos Más Vendidos -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h5>🔥 Productos Más Vendidos</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('reportes.pdf.top-productos') }}" method="GET">
                        <div class="mb-3">
                            <label class="form-label">Límite:</label>
                            <select name="limite" class="form-select">
                                <option value="5">Top 5</option>
                                <option value="10" selected>Top 10</option>
                                <option value="20">Top 20</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Mes:</label>
                            <select name="mes" class="form-select">
                                @for($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ $i == date('m') ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create()->month($i)->locale('es')->monthName }}
                                </option>
                                @endfor
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Año:</label>
                            <input type="number" name="anio" class="form-control" value="{{ date('Y') }}">
                        </div>
                        <button type="submit" class="btn btn-warning w-100">
                            📄 Generar PDF
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection