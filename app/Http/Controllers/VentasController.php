<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\Client;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\Worker;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class VentasController extends Controller
{
    public function index(Request $request): View
    {
        $query = Sale::with(['client', 'worker', 'saleDetails.batch.product', 'saleDetails.batch.supplier', 'saleDetails.discount']);

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        }
        
        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        // We clone the query to get the total revenue of the filtered dataset
        $revenueQuery = clone $query;
        $totalRevenue = $revenueQuery->sum('total_amount');

        $sales = $query->orderBy('created_at', 'desc')->paginate(15);
        $totalSalesCount = $sales->total();

        return view('ventas.historial', compact('sales', 'totalSalesCount', 'totalRevenue', 'startDate', 'endDate'));
    }

    public function create(): View
    {
        // Traer lotes que tengan stock disponible
        $batches = Batch::with(['product.productCategory', 'supplier'])
            ->where('current_stock', '>', 0)
            ->orderBy('created_at', 'desc')
            ->get();
            
        $clients = Client::orderBy('name')->get();

        return view('ventas.nueva', compact('batches', 'clients'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'client_id'          => 'nullable|exists:clients,id',
            'new_client_name'    => 'nullable|string|max:255',
            'items'              => 'required|array|min:1',
            'items.*.batch_id'   => 'required|exists:batches,id',
            'items.*.quantity'   => 'required|integer|min:1',
            'items.*.price'      => 'required|numeric|min:0.01',
            'total_net'          => 'required|numeric|min:0',
            'total_taxes'        => 'required|numeric|min:0',
            'total_amount'       => 'required|numeric|min:0.01',
            'discount_id'        => 'nullable|exists:discounts,id',
            'discount_amount'    => 'nullable|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();
            
            // Determinar Cliente
            $clientId = $validated['client_id'] ?? null;
            $finalClientName = 'Cliente General';
            
            if (!$clientId) {
                // Si enviaron nombre, creamos un cliente. Si no, usamos "Cliente General"
                $clientName = !empty($validated['new_client_name']) ? $validated['new_client_name'] : 'Cliente General';
                
                $client = Client::firstOrCreate(
                    ['name' => $clientName],
                    [
                        'document_type' => 'DNI',
                        'document_number' => '00000000',
                        'lastname' => '',
                    ]
                );
                $clientId = $client->id;
                $finalClientName = $client->name;
            } else {
                $client = Client::find($clientId);
                if ($client) {
                    $finalClientName = $client->name . ' ' . $client->lastname;
                }
            }

            // Asumimos el trabajador 1 por defecto ya que no hay auth de workers vinculada
            $worker = Worker::first();
            $workerId = $worker ? $worker->id : 1; 
            
            if (!$worker) {
                 Worker::insert([
                     'id' => 1,
                     'document_type' => 'DNI',
                     'document_number' => '11111111',
                     'name' => 'Admin',
                     'lastname' => 'User',
                     'email' => 'admin@abad.com',
                     'phone' => '999999999',
                     'position' => 'Admin',
                     'salary' => 1000,
                     'hire_date' => now(),
                 ]);
                 $workerId = 1;
            }

            // Crear la Venta
            $sale = Sale::create([
                'client_id'    => $clientId,
                'worker_id'    => $workerId,
                'total_net'    => $validated['total_net'],
                'total_taxes'  => $validated['total_taxes'],
                'total_amount' => $validated['total_amount'],
                'status'       => 'COMPLETED',
            ]);

            $discountId = $validated['discount_id'] ?? null;
            $discountAmount = (float) ($validated['discount_amount'] ?? 0.00);

            $totalOriginal = 0;
            foreach ($validated['items'] as $item) {
                $totalOriginal += ((int) $item['quantity']) * ((float) $item['price']);
            }

            $remainingDiscount = $discountAmount;
            $itemIndex = 0;
            $totalItems = count($validated['items']);

            // Procesar items y descontar stock del lote seleccionado
            foreach ($validated['items'] as $item) {
                $itemIndex++;
                $qtyNeeded = (int) $item['quantity'];
                
                $batch = Batch::find($item['batch_id']);
                                
                if (!$batch || $batch->current_stock < $qtyNeeded) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'No hay suficiente stock para el lote con ID: ' . $item['batch_id']
                    ], 400);
                }

                $itemSubtotal = $qtyNeeded * ((float) $item['price']);
                $itemDiscount = 0.00;
                if ($discountAmount > 0 && $totalOriginal > 0) {
                    if ($itemIndex === $totalItems) {
                        $itemDiscount = $remainingDiscount;
                    } else {
                        $itemDiscount = round(($itemSubtotal / $totalOriginal) * $discountAmount, 2);
                        $remainingDiscount -= $itemDiscount;
                    }
                }
                
                // Crear detalle de venta asociando el lote usado
                SaleDetail::create([
                    'sale_id'         => $sale->id,
                    'batch_id'        => $batch->id,
                    'discount_id'     => $itemDiscount > 0 ? $discountId : null,
                    'quantity'        => $qtyNeeded,
                    'unit_price'      => $item['price'],
                    'discount_amount' => $itemDiscount > 0 ? $itemDiscount : null,
                    'is_active'       => true,
                ]);
                
                // Descontar del lote
                $batch->current_stock -= $qtyNeeded;
                $batch->save();
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Venta registrada exitosamente.',
                'sale_id' => $sale->id,
                'client_name' => trim($finalClientName)
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error al procesar la venta: ' . $e->getMessage()
            ], 500);
        }
    }

    public function validateDiscount(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'code'         => 'required|string|max:50',
            'total_amount' => 'required|numeric|min:0.01',
        ]);

        $discount = \App\Models\Discount::where('code', $validated['code'])->first();

        if (!$discount) {
            return response()->json(['success' => false, 'message' => 'El código de descuento no existe.'], 404);
        }

        // 1. Expiración
        if ($discount->expiration_date->isPast()) {
            return response()->json(['success' => false, 'message' => 'El código de descuento ha expirado.'], 400);
        }

        // 2. Límite de uso
        $useCount = SaleDetail::where('discount_id', $discount->id)->distinct('sale_id')->count();
        if ($useCount >= $discount->use_limit) {
            return response()->json(['success' => false, 'message' => 'El código de descuento ha agotado su límite de usos.'], 400);
        }

        // 3. Monto Mínimo de Compra
        if ((float) $validated['total_amount'] < (float) $discount->minimum_amount) {
            return response()->json([
                'success' => false, 
                'message' => 'El monto mínimo de compra para este cupón es S/ ' . number_format((float) $discount->minimum_amount, 2)
            ], 400);
        }

        return response()->json([
            'success' => true,
            'discount' => [
                'id' => $discount->id,
                'code' => $discount->code,
                'type_discount' => $discount->type_discount,
                'amount' => (float) $discount->amount,
                'minimum_amount' => (float) $discount->minimum_amount,
                'maximum_amount' => (float) $discount->maximum_amount,
            ]
        ]);
    }
}