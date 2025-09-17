<?php

namespace App\Http\Controllers;

use App\Models\Compra;
use App\Models\Venta;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReporteController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:ver-reportes', ['only' => ['index']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Obtener compras mensuales del año actual
        $comprasMensuales = $this->getComprasMensuales();

        // Obtener ventas mensuales del año actual
        $ventasMensuales = $this->getVentasMensuales();

        // Obtener top 5 productos más vendidos
        $topProductos = $this->getTopProductos();

        return view('reportes.index', compact('comprasMensuales', 'ventasMensuales', 'topProductos'));
    }

    /**
     * Obtiene las compras mensuales del año actual
     */
    private function getComprasMensuales()
    {
        $compras = Compra::select(
            DB::raw('MONTH(fecha_hora) as mes'),
            DB::raw('YEAR(fecha_hora) as año'),
            DB::raw('SUM(total) as total')
        )
            ->whereYear('fecha_hora', date('Y'))
            ->groupBy('año', 'mes')
            ->orderBy('año', 'asc')
            ->orderBy('mes', 'asc')
            ->get();

        // Preparar datos para el gráfico
        $data = [];
        foreach ($compras as $compra) {
            $data[] = [
                'mes' => $this->getNombreMes($compra->mes),
                'total' => floatval($compra->total)
            ];
        }

        return $data;
    }

    /**
     * Obtiene las ventas mensuales del año actual
     */
    private function getVentasMensuales()
    {
        $ventas = Venta::select(
            DB::raw('MONTH(created_at) as mes'),
            DB::raw('YEAR(created_at) as año'),
            DB::raw('SUM(total) as total')
        )
            ->whereYear('created_at', date('Y'))
            ->groupBy('año', 'mes')
            ->orderBy('año', 'asc')
            ->orderBy('mes', 'asc')
            ->get();

        // Preparar datos para el gráfico
        $data = [];
        foreach ($ventas as $venta) {
            $data[] = [
                'mes' => $this->getNombreMes($venta->mes),
                'total' => floatval($venta->total)
            ];
        }

        return $data;
    }

    /**
     * Obtiene el top 5 de productos más vendidos
     */
/**
 * Obtiene el top 5 de productos más vendidos
 */
private function getTopProductos()
{
    $productos = Producto::select(
            'productos.id',
            'productos.nombre',
            DB::raw('SUM(producto_venta.cantidad) as total_vendido'),
            DB::raw('SUM(producto_venta.cantidad * producto_venta.precio_venta) as total_ventas')
        )
        ->join('producto_venta', 'productos.id', '=', 'producto_venta.producto_id')
        ->join('ventas', 'producto_venta.venta_id', '=', 'ventas.id')
        ->groupBy('productos.id', 'productos.nombre')
        ->orderBy('total_vendido', 'desc')
        ->take(5)
        ->get();

    // Preparar datos para el gráfico
    $data = [];
    foreach ($productos as $producto) {
        $data[] = [
            'nombre' => $producto->nombre,
            'cantidad' => intval($producto->total_vendido),
            'total_ventas' => floatval($producto->total_ventas)
        ];
    }

    return $data;
}

    /**
     * Convierte el número de mes a nombre
     */
    private function getNombreMes($mes)
    {
        $meses = [
            1 => 'Enero',
            2 => 'Febrero',
            3 => 'Marzo',
            4 => 'Abril',
            5 => 'Mayo',
            6 => 'Junio',
            7 => 'Julio',
            8 => 'Agosto',
            9 => 'Septiembre',
            10 => 'Octubre',
            11 => 'Noviembre',
            12 => 'Diciembre'
        ];

        return $meses[$mes] ?? 'Desconocido';
    }

    /**
     * Método para obtener datos mediante AJAX
     */
    public function getData(Request $request)
    {
        $tipo = $request->get('tipo');

        switch ($tipo) {
            case 'compras':
                $data = $this->getComprasMensuales();
                break;
            case 'ventas':
                $data = $this->getVentasMensuales();
                break;
            case 'productos':
                $data = $this->getTopProductos();
                break;
            default:
                $data = [];
                break;
        }

        return response()->json($data);
    }
}
