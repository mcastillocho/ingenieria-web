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

            // Procesar items y descontar stock del lote seleccionado
            foreach ($validated['items'] as $item) {
                $qtyNeeded = (int) $item['quantity'];
                
                $batch = Batch::find($item['batch_id']);
                                
                if (!$batch || $batch->current_stock < $qtyNeeded) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'No hay suficiente stock para el lote con ID: ' . $item['batch_id']
                    ], 400);
                }
                
                // Crear detalle de venta asociando el lote usado
                SaleDetail::create([
                    'sale_id'         => $sale->id,
                    'batch_id'        => $batch->id,
                    'discount_id'     => null,
                    'quantity'        => $qtyNeeded,
                    'unit_price'      => $item['price'],
                    'discount_amount' => 0,
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
}
