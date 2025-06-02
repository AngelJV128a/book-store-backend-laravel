<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SaleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {
        Log::info('Request received');
        $sales = Sale::with('SaleDetail')->get();
        Log::info('Request processed');
        return response()->json($sales);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            // Crear la venta principal
            $sale = new Sale();
            $sale->id_client = $request->id_client;
            $sale->date = now(); // o $request->date si lo manejas tú
            $sale->total = 0; // Se calculará más abajo
            $sale->save();

            $total = 0;

            foreach ($request->saleDetails as $detail) {
                $saleDetail = new SaleDetail();
                $saleDetail->id_sale = $sale->id;
                $saleDetail->id_book = $detail['book_id'];
                $saleDetail->quantity = $detail['quantity'];
                $saleDetail->unit_price = $detail['unit_price'];
                $saleDetail->save();

                $total += $detail['quantity'] * $detail['unit_price'];
            }

            // Actualizar el total de la venta
            $sale->total = $total;
            $sale->save();

            DB::commit();

            return response()->json([
                'code' => 200,
                'message' => 'Sale created successfully',
                'sale' => $sale
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'code' => 500,
                'message' => 'Sale creation failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(Request $request)
    {
        $id = $request->id_sale;
        $sale = Sale::with('SaleDetail')->find($id);
        if (!$sale) {
            return response()->json(['message' => 'Sale not found'], 404);
        }
        $response = [
            'code' => 200,
            'message' => 'Sale found successfully',
            'sale' => $sale
        ];
        return response()->json($response);
    }

    public function delete(Request $request)
    {
        $id = $request->id;
        $sale = Sale::find($id);
        if (!$sale) {
            return response()->json(['message' => 'Sale not found'], 404);
        }
        $sale->delete();
        $response = [
            'code' => 200,
            'message' => 'Sale deleted successfully'
        ];
        return response()->json($response);
    }

    public function showByUser(Request $request)
    {
        $user_id = $request->id_user;
        $sale = Sale::with('SaleDetail')->where('id_client', $user_id)->get();
        if (!$sale) {
            return response()->json(['message' => 'Sale not found'], 404);
        }
        $response = [
            'code' => 200,
            'message' => 'Sale found successfully',
            'sales' => $sale
        ];
        return response()->json($response);
    }
}
