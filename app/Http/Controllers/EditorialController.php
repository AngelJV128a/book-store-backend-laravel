<?php

namespace App\Http\Controllers;

use App\Models\Editorial;
use Illuminate\Http\Request;

/**
 * @OA\Schema(
 *     schema="Editorial",
 *     type="object",
 *     title="Editorial",
 *     required={"name", "country", "website"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Planeta"),
 *     @OA\Property(property="country", type="string", example="España"),
 *     @OA\Property(property="website", type="string", example="https://www.editorialplaneta.com"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2025-06-01T00:00:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-06-02T00:00:00Z")
 * )
 */

class EditorialController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * @OA\Get(
     *     path="/api/editorials",
     *     summary="Listar editoriales",
     *     description="Devuelve una lista paginada de editoriales",
     *     operationId="getEditorials",
     *     tags={"Editorials"},
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Número de página para la paginación",
     *         required=false,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de editoriales paginada",
     *         @OA\JsonContent(
     *             @OA\Property(property="current_page", type="integer", example=1),
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(ref="#/components/schemas/Editorial")
     *             ),
     *             @OA\Property(property="total", type="integer", example=25),
     *             @OA\Property(property="per_page", type="integer", example=10),
     *             @OA\Property(property="last_page", type="integer", example=3),
     *             @OA\Property(property="next_page_url", type="string", example="http://localhost/api/editorials?page=2"),
     *             @OA\Property(property="prev_page_url", type="string", example=null)
     *         )
     *     )
     * )
     */
    public function index()
    {
        $editorials = Editorial::paginate(10);
        return response()->json($editorials);
    }

    /**
     * @OA\Post(
     *     path="/api/editorials",
     *     summary="Crear una nueva editorial",
     *     description="Crea una editorial con nombre, país y sitio web",
     *     operationId="storeEditorial",
     *     tags={"Editorials"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "country", "website"},
     *             @OA\Property(property="name", type="string", example="Editorial Alfa"),
     *             @OA\Property(property="country", type="string", example="España"),
     *             @OA\Property(property="website", type="string", example="https://editorialalfa.com")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Editorial creada exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="Editorial created successfully"),
     *             @OA\Property(property="editorial", ref="#/components/schemas/Editorial")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Datos inválidos"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'website' => 'required|string|max:255'
        ]);
        $editorial = new Editorial();
        $editorial->name = $request->name;
        $editorial->country = $request->country;
        $editorial->website = $request->website;
        $editorial->save();
        $response = [
            'code' => 200,
            'message' => 'Editorial created successfully',
            'editorial' => $editorial
        ];
        return response()->json($response);
    }

    /**
     * @OA\Get(
     *     path="/api/editorials/{id}",
     *     summary="Obtener una editorial por ID",
     *     description="Devuelve una editorial si el ID existe",
     *     operationId="getEditorialById",
     *     tags={"Editorials"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la editorial",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Editorial encontrada exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="Editorial found successfully"),
     *             @OA\Property(property="editorial", ref="#/components/schemas/Editorial")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Editorial no encontrada",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Editorial not found")
     *         )
     *     )
     * )
     */
    public function show($id)
    {
        $editorial = Editorial::find($id);
        if (!$editorial) {
            return response()->json(['message' => 'Editorial not found'], 404);
        }
        $response = [
            'code' => 200,
            'message' => 'Editorial found successfully',
            'editorial' => $editorial
        ];
        return response()->json($response);
    }

    /**
     * @OA\Put(
     *     path="/api/editorials/{id}",
     *     summary="Actualizar una editorial",
     *     description="Actualiza los datos de una editorial existente por su ID",
     *     operationId="updateEditorial",
     *     tags={"Editorials"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la editorial a actualizar",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "country", "website"},
     *             @OA\Property(property="name", type="string", example="Editorial Actualizada"),
     *             @OA\Property(property="country", type="string", example="México"),
     *             @OA\Property(property="website", type="string", example="https://editorialactualizada.com")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Editorial actualizada exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="Editorial updated successfully"),
     *             @OA\Property(property="editorial", ref="#/components/schemas/Editorial")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Editorial no encontrada",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Editorial not found")
     *         )
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'website' => 'required|string|max:255'
        ]);
        $editorial = Editorial::find($id);
        if (!$editorial) {
            return response()->json(['message' => 'Editorial not found'], 404);
        }
        $editorial->name = $request->name;
        $editorial->country = $request->country;
        $editorial->website = $request->website;
        $editorial->save();
        $response = [
            'code' => 200,
            'message' => 'Editorial updated successfully',
            'editorial' => $editorial
        ];
        return response()->json($response);
    }

    /**
     * @OA\Delete(
     *     path="/api/editorials/{id}",
     *     summary="Eliminar una editorial",
     *     description="Elimina una editorial existente por su ID",
     *     operationId="deleteEditorial",
     *     tags={"Editorials"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la editorial a eliminar",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Editorial eliminada exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="Editorial deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Editorial no encontrada",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Editorial not found")
     *         )
     *     )
     * )
     */
    public function destroy($id)
    {
        $editorial = Editorial::find($id);
        if (!$editorial) {
            return response()->json(['message' => 'Editorial not found'], 404);
        }
        $editorial->delete();
        $response = [
            'code' => 200,
            'message' => 'Editorial deleted successfully'
        ];
        return response()->json($response);
    }

    /**
     * @OA\Post(
     *     path="/api/editorials/by-name",
     *     summary="Buscar editorial por nombre",
     *     description="Devuelve una editorial que coincida con el nombre proporcionado",
     *     operationId="getEditorialByName",
     *     tags={"Editorials"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="Alfaguara")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Editorial encontrada exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="Editorial found successfully"),
     *             @OA\Property(property="editorial", ref="#/components/schemas/Editorial")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Editorial no encontrada",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Editorial not found")
     *         )
     *     )
     * )
     */
    public function showByName(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);
        $name = $request->name;
        $editorial = Editorial::where('name', $name)->first();
        if (!$editorial) {
            return response()->json(['message' => 'Editorial not found'], 404);
        }
        $response = [
            'code' => 200,
            'message' => 'Editorial found successfully',
            'editorial' => $editorial
        ];
        return response()->json($response);
    }

    /**
     * @OA\Post(
     *     path="/api/editorials/by-country",
     *     summary="Buscar editorial por país",
     *     description="Devuelve una editorial que coincida con el país proporcionado",
     *     operationId="getEditorialByCountry",
     *     tags={"Editorials"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"country"},
     *             @OA\Property(property="country", type="string", example="Argentina")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Editorial encontrada exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="Editorial found successfully"),
     *             @OA\Property(property="editorial", ref="#/components/schemas/Editorial")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Editorial no encontrada",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Editorial not found")
     *         )
     *     )
     * )
     */
    public function showByCountry(Request $request)
    {
        $request->validate([
            'country' => 'required|string|max:255'
        ]);
        $country = $request->country;
        $editorial = Editorial::where('country', $country)->first();
        if (!$editorial) {
            return response()->json(['message' => 'Editorial not found'], 404);
        }
        $response = [
            'code' => 200,
            'message' => 'Editorial found successfully',
            'editorial' => $editorial
        ];
        return response()->json($response);
    }
}
