<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\Client;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        // 1. Total Productos y nuevos esta semana
        $totalProducts = Product::count();
        $productsNewThisWeek = Product::where('created_at', '>=', now()->subDays(7))->count();

        // 2. Ventas hoy y comparación con ayer
        $todaySalesSum = (float) Sale::whereDate('created_at', today())->sum('total_amount');
        $yesterdaySalesSum = (float) Sale::whereDate('created_at', today()->subDay())->sum('total_amount');

        $percentageChange = 0.0;
        if ($yesterdaySalesSum > 0) {
            $percentageChange = (($todaySalesSum - $yesterdaySalesSum) / $yesterdaySalesSum) * 100;
        }

        // 3. Stock crítico (agotado + stock bajo)
        $outOfStock = Batch::where('current_stock', 0)->count();
        $lowStock = Batch::where('current_stock', '>', 0)->where('current_stock', '<=', 10)->count();
        $criticalStock = $outOfStock + $lowStock;

        // 4. Clientes y ticket promedio
        $totalClients = Client::count();
        $averageTicket = (float) (Sale::avg('total_amount') ?? 0.0);

        // 5. Ventas últimos 7 días (para el gráfico)
        $salesLast7Days = [];
        $dayNames = ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'];
        for ($i = 6; $i >= 0; $i--) {
            $date = today()->subDays($i);
            $formattedDate = $date->format('Y-m-d');
            $displayDate = $dayNames[(int) $date->format('w')] . ' ' . $date->format('j');
            $total = (float) Sale::whereDate('created_at', $formattedDate)->sum('total_amount');
            
            $salesLast7Days[] = [
                'date' => $displayDate,
                'total' => $total
            ];
        }

        // 6. Producto destacado (basado en ventas de los últimos 30 días, o mayor stock)
        $topProductData = SaleDetail::select('batch_id', DB::raw('SUM(quantity) as total_sold'))
            ->join('sales', 'sale_details.sale_id', '=', 'sales.id')
            ->where('sales.created_at', '>=', now()->subDays(30))
            ->groupBy('batch_id')
            ->orderByDesc('total_sold')
            ->first();

        $featuredProduct = null;
        if ($topProductData) {
            $batch = Batch::with(['product.productCategory', 'supplier'])->find($topProductData->batch_id);
            if ($batch && $batch->product) {
                $featuredProduct = $batch->product;
                $featuredProduct->supplier_name = $batch->supplier->name ?? 'Sin Proveedor';
                $featuredProduct->category_name = $batch->product->productCategory->name ?? 'Varios';
                $featuredProduct->current_stock = $batch->current_stock;
            }
        }

        if (!$featuredProduct) {
            $batch = Batch::with(['product.productCategory', 'supplier'])->orderByDesc('current_stock')->first();
            if ($batch && $batch->product) {
                $featuredProduct = $batch->product;
                $featuredProduct->supplier_name = $batch->supplier->name ?? 'Sin Proveedor';
                $featuredProduct->category_name = $batch->product->productCategory->name ?? 'Varios';
                $featuredProduct->current_stock = $batch->current_stock;
            }
        }

        // 7. Últimos productos agregados (máximo 5)
        $recentProducts = Product::with('batches')->latest()->take(5)->get()->map(function ($product) {
            $stock = $product->batches->sum('current_stock');
            if ($stock == 0) {
                $badgeVariant = 'out';
                $badgeText = 'Sin stock';
            } elseif ($stock <= 10) {
                $badgeVariant = 'low';
                $badgeText = 'Bajo';
            } else {
                $badgeVariant = 'ok';
                $badgeText = 'OK';
            }
            
            return [
                'name' => $product->name,
                'stock' => $stock,
                'badge_variant' => $badgeVariant,
                'badge_text' => $badgeText
            ];
        });

        // 8. Alertas dinámicas
        $alerts = [];
        $todaySalesCount = Sale::whereDate('created_at', today())->count();
        $alerts[] = [
            'variant' => 'info',
            'message' => "Información: Se han registrado {$todaySalesCount} ventas el día de hoy."
        ];
        
        $alerts[] = [
            'variant' => 'success',
            'message' => "Éxito: El sistema está sincronizado con {$totalProducts} productos registrados."
        ];
        
        if ($lowStock > 0) {
            $alerts[] = [
                'variant' => 'warning',
                'message' => "Advertencia: Quedan {$lowStock} lotes con stock bajo (menos de 10 unidades)."
            ];
        }
        
        if ($outOfStock > 0) {
            $alerts[] = [
                'variant' => 'danger',
                'message' => "Peligro: Hay {$outOfStock} lotes completamente agotados en el inventario."
            ];
        }

        // 9. Tres productos destacados/recientes para la parte inferior
        $bottomProducts = Product::with(['batches.supplier', 'productCategory'])->latest()->take(3)->get()->map(function ($product) {
            $currentStock = $product->batches->sum('current_stock');
            
            $firstBatch = $product->batches->first();
            $supplierName = $firstBatch && $firstBatch->supplier ? $firstBatch->supplier->name : 'Sin Proveedor';
            
            if ($currentStock == 0) {
                $badgeVariant = 'out';
                $badgeText = 'Sin stock';
            } elseif ($currentStock <= 10) {
                $badgeVariant = 'low';
                $badgeText = 'Stock Bajo';
            } else {
                $badgeVariant = 'ok';
                $badgeText = 'Stock OK';
            }
            
            return [
                'id' => $product->id,
                'name' => $product->name,
                'supplier' => $supplierName,
                'category' => $product->productCategory->name ?? 'Varios',
                'description' => $product->description ?? 'Sin descripción disponible.',
                'sale_price' => $product->sale_price,
                'stock' => $currentStock,
                'badge_variant' => $badgeVariant,
                'badge_text' => $badgeText,
                'image_path' => $product->image_path ?? 'https://via.placeholder.com/400x240'
            ];
        });

        return view('welcome', compact(
            'totalProducts',
            'productsNewThisWeek',
            'todaySalesSum',
            'percentageChange',
            'criticalStock',
            'outOfStock',
            'lowStock',
            'totalClients',
            'averageTicket',
            'salesLast7Days',
            'featuredProduct',
            'recentProducts',
            'alerts',
            'bottomProducts'
        ));
    }
}
