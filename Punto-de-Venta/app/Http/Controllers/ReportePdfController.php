<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportePdfController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:ver-reportes', ['only' => ['index']]);
    }

    /**
     * Mostrar página principal de reportes PDF
     */
    public function index()
    {
        return view('reportes.pdf.index');
    }

    /**
     * Generar PDF de ventas mensuales
     */
    public function ventasMensuales(Request $request)
    {
        $mes = $request->input('mes', date('m'));
        $anio = $request->input('anio', date('Y'));

        $ventas = Venta::select(
                DB::raw('DATE(created_at) as fecha'),
                DB::raw('COUNT(*) as total_ventas'),
                DB::raw('SUM(total) as monto_total')
            )
            ->whereMonth('created_at', $mes)
            ->whereYear('created_at', $anio)
            ->groupBy('fecha')
            ->orderBy('fecha', 'desc')
            ->get();

        $totalGeneral = $ventas->sum('monto_total');
        $totalVentas = $ventas->sum('total_ventas');

        $pdf = Pdf::loadView('reportes.pdf.ventas_mensuales', [
            'ventas' => $ventas,
            'mes' => $mes,
            'anio' => $anio,
            'totalGeneral' => $totalGeneral,
            'totalVentas' => $totalVentas,
            'nombreMes' => $this->getNombreMes($mes)
        ]);

        return $pdf->download('reporte-ventas-' . $this->getNombreMes($mes) . '-' . $anio . '.pdf');
    }

    /**
     * Generar PDF de inventario y stock
     */
    public function inventarioStock(Request $request)
    {
        $productos = Producto::with(['marca.caracteristica', 'presentacione.caracteristica'])
            ->orderBy('nombre')
            ->get();

        // Calcular stock disponible (esto depende de tu lógica de negocio)
        foreach ($productos as $producto) {
            $producto->stock_disponible = $this->calcularStock($producto->id);
        }

        $pdf = Pdf::loadView('reportes.pdf.inventario_stock', [
            'productos' => $productos,
            'fecha' => now()->format('d/m/Y')
        ]);

        return $pdf->download('reporte-inventario-' . now()->format('Y-m-d') . '.pdf');
    }

    /**
     * Generar PDF de productos más vendidos
     */
    public function productosMasVendidos(Request $request)
    {
        $limite = $request->input('limite', 10);
        $mes = $request->input('mes', date('m'));
        $anio = $request->input('anio', date('Y'));

        $productos = Producto::select(
                'productos.id',
                'productos.nombre',
                'productos.codigo',
                DB::raw('SUM(producto_venta.cantidad) as total_vendido'),
                DB::raw('SUM(producto_venta.cantidad * producto_venta.precio_venta) as monto_total')
            )
            ->join('producto_venta', 'productos.id', '=', 'producto_venta.producto_id')
            ->join('ventas', 'producto_venta.venta_id', '=', 'ventas.id')
            ->whereMonth('ventas.created_at', $mes)
            ->whereYear('ventas.created_at', $anio)
            ->groupBy('productos.id', 'productos.nombre', 'productos.codigo')
            ->orderBy('total_vendido', 'desc')
            ->take($limite)
            ->get();

        $pdf = Pdf::loadView('reportes.pdf.productos_mas_vendidos', [
            'productos' => $productos,
            'mes' => $mes,
            'anio' => $anio,
            'limite' => $limite,
            'nombreMes' => $this->getNombreMes($mes)
        ]);

        return $pdf->download('top-productos-' . $this->getNombreMes($mes) . '-' . $anio . '.pdf');
    }

    /**
     * Calcular stock disponible de un producto
     */
    private function calcularStock($productoId)
    {
        // Implementar lógica según tu sistema
        // Ejemplo básico:
        $compras = DB::table('compra_producto')
            ->where('producto_id', $productoId)
            ->sum('cantidad');

        $ventas = DB::table('producto_venta')
            ->where('producto_id', $productoId)
            ->sum('cantidad');

        return $compras - $ventas;
    }

    /**
     * Obtener nombre del mes
     */
    private function getNombreMes($mes)
    {
        $meses = [
            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
            5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
            9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
        ];

        return $meses[$mes] ?? 'Desconocido';
    }

}