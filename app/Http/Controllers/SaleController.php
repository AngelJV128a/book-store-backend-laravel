<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * @OA\Schema(
 *     schema="SaleDetail",
 *     type="object",
 *     title="SaleDetail",
 *     description="Detalle de una venta",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="id_sale", type="integer", example=10),
 *     @OA\Property(property="id_book", type="integer", example=5),
 *     @OA\Property(property="quantity", type="integer", example=2),
 *     @OA\Property(property="unit_price", type="number", format="float", example=15.50),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 * )
 *
 * @OA\Schema(
 *     schema="Sale",
 *     type="object",
 *     title="Sale",
 *     description="Venta realizada por un cliente",
 *     @OA\Property(property="id", type="integer", example=10),
 *     @OA\Property(property="id_client", type="integer", example=123),
 *     @OA\Property(property="date", type="string", format="date-time", example="2025-06-02T15:30:00Z"),
 *     @OA\Property(property="total", type="number", format="float", example=50.00),
 *     @OA\Property(
 *         property="sale_details",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/SaleDetail")
 *     ),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 * )
 */

class SaleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * @OA\Get(
     *     path="/api/sales",
     *     summary="Listar ventas",
     *     description="Devuelve todas las ventas con sus detalles",
     *     operationId="getSales",
     *     tags={"Sales"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de ventas con detalles",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Sale")
     *         )
     *     )
     * )
     */
    public function index()
    {
        Log::info('Request received');
        $sales = Sale::with('SaleDetail')->get();
        Log::info('Request processed');
        return response()->json($sales);
    }

    /**
     * @OA\Post(
     *     path="/sales",
     *     summary="Crear una nueva venta con detalles",
     *     tags={"Sales"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"id_client","saleDetails"},
     *             @OA\Property(property="id_client", type="integer", example=123, description="ID del cliente que realiza la venta"),
     *             @OA\Property(
     *                 property="saleDetails",
     *                 type="array",
     *                 @OA\Items(
     *                     required={"book_id","quantity","unit_price"},
     *                     @OA\Property(property="book_id", type="integer", example=456, description="ID del libro vendido"),
     *                     @OA\Property(property="quantity", type="integer", example=2, description="Cantidad vendida del libro"),
     *                     @OA\Property(property="unit_price", type="number", format="float", example=19.99, description="Precio unitario del libro")
     *                 ),
     *                 description="Lista de detalles de la venta"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Venta creada exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="Sale created successfully"),
     *             @OA\Property(
     *                 property="sale",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="id_client", type="integer", example=123),
     *                 @OA\Property(property="date", type="string", format="date-time", example="2025-06-02T12:34:56Z"),
     *                 @OA\Property(property="total", type="number", format="float", example=39.98)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al crear la venta",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=500),
     *             @OA\Property(property="message", type="string", example="Sale creation failed"),
     *             @OA\Property(property="error", type="string", example="Error message describing the failure")
     *         )
     *     )
     * )
     */
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

    /**
     * @OA\Get(
     *     path="/sales/{id_sale}",
     *     summary="Obtener una venta por su ID con detalles",
     *     tags={"Sales"},
     *     @OA\Parameter(
     *         name="id_sale",
     *         in="path",
     *         required=true,
     *         description="ID de la venta a obtener",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Venta encontrada exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="Sale found successfully"),
     *             @OA\Property(
     *                 property="sale",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="id_client", type="integer", example=123),
     *                 @OA\Property(property="date", type="string", format="date-time", example="2025-06-02T12:34:56Z"),
     *                 @OA\Property(property="total", type="number", format="float", example=39.98),
     *                 @OA\Property(
     *                     property="sale_detail",
     *                     type="array",
     *                     @OA\Items(
     *                         @OA\Property(property="id", type="integer", example=10),
     *                         @OA\Property(property="id_sale", type="integer", example=1),
     *                         @OA\Property(property="id_book", type="integer", example=456),
     *                         @OA\Property(property="quantity", type="integer", example=2),
     *                         @OA\Property(property="unit_price", type="number", format="float", example=19.99)
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Venta no encontrada",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Sale not found")
     *         )
     *     )
     * )
     */
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

    /**
     * @OA\Delete(
     *     path="/sales",
     *     summary="Eliminar una venta por su ID",
     *     tags={"Sales"},
     *     @OA\Parameter(
     *         name="id",
     *         in="query",
     *         required=true,
     *         description="ID de la venta a eliminar",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Venta eliminada exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="Sale deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Venta no encontrada",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Sale not found")
     *         )
     *     )
     * )
     */
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

    /**
     * @OA\Get(
     *     path="/sales/by-user",
     *     summary="Obtener ventas por ID de usuario",
     *     tags={"Sales"},
     *     @OA\Parameter(
     *         name="id_user",
     *         in="query",
     *         required=true,
     *         description="ID del usuario (cliente) para obtener sus ventas",
     *         @OA\Schema(type="integer", example=123)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Ventas encontradas exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="Sale found successfully"),
     *             @OA\Property(
     *                 property="sales",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Sale")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Ventas no encontradas",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Sale not found")
     *         )
     *     )
     * )
     */
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
