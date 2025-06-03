<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AuthorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * @OA\Get(
     *     path="/api/authors",
     *     summary="Listar autores paginados",
     *     description="Obtiene una lista paginada de autores. Requiere autenticación mediante Bearer Token.",
     *     operationId="getAuthors",
     *     tags={"Authors"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Número de página para la paginación",
     *         required=false,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista paginada de autores",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="current_page", type="integer", example=1),
     *             @OA\Property(property="data", type="array", @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Gabriel"),
     *                 @OA\Property(property="last_name", type="string", example="García Márquez"),
     *                 @OA\Property(property="nationality", type="string", example="Colombiana")
     *             )),
     *             @OA\Property(property="total", type="integer", example=50),
     *             @OA\Property(property="per_page", type="integer", example=10),
     *             @OA\Property(property="last_page", type="integer", example=5)
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autenticado"
     *     )
     * )
     */
    public function index()
    {
        $authors = Author::paginate(10);
        return response()->json($authors);
    }

    /**
     * @OA\Post(
     *     path="/api/authors",
     *     summary="Crear un nuevo autor",
     *     description="Crea un nuevo autor en la base de datos. Requiere autenticación mediante Bearer Token.",
     *     operationId="createAuthor",
     *     tags={"Authors"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "last_name", "nationality"},
     *             @OA\Property(property="name", type="string", example="Isabel"),
     *             @OA\Property(property="last_name", type="string", example="Allende"),
     *             @OA\Property(property="nationality", type="string", example="Chilena")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Autor creado correctamente",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="Author created successfully"),
     *             @OA\Property(property="author", type="object",
     *                 @OA\Property(property="id", type="integer", example=5),
     *                 @OA\Property(property="name", type="string", example="Isabel"),
     *                 @OA\Property(property="last_name", type="string", example="Allende"),
     *                 @OA\Property(property="nationality", type="string", example="Chilena"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-01-01T00:00:00Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-01-01T00:00:00Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Datos inválidos"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autenticado"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'nationality' => 'required|string|max:255'
        ]);
        $author = new Author();
        $author->name = $request->name;
        $author->last_name = $request->last_name;
        $author->nationality = $request->nationality;
        $author->save();
        $response = [
            'code' => 200,
            'message' => 'Author created successfully',
            'author' => $author
        ];
        return response()->json($response);
    }

    /**
     * @OA\Get(
     *     path="/api/authors/{id}",
     *     summary="Obtener un autor por ID",
     *     description="Devuelve los datos de un autor específico por su ID. Requiere autenticación con Bearer Token.",
     *     operationId="getAuthorById",
     *     tags={"Authors"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del autor a consultar",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Autor encontrado correctamente",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="Author found successfully"),
     *             @OA\Property(property="author", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Gabriel"),
     *                 @OA\Property(property="last_name", type="string", example="García Márquez"),
     *                 @OA\Property(property="nationality", type="string", example="Colombiana"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-01-01T00:00:00Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-01-01T00:00:00Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Autor no encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Author not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autenticado"
     *     )
     * )
     */
    public function show($id)
    {
        $author = Author::find($id);
        if (!$author) {
            return response()->json(['message' => 'Author not found'], 404);
        }
        $response = [
            'code' => 200,
            'message' => 'Author found successfully',
            'author' => $author
        ];
        return response()->json($response);
    }

    /**
     * @OA\Put(
     *     path="/api/authors/{id}",
     *     summary="Actualizar un autor",
     *     description="Actualiza los datos de un autor existente por su ID. Requiere autenticación con Bearer Token.",
     *     operationId="updateAuthor",
     *     tags={"Authors"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del autor a actualizar",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "last_name", "nationality"},
     *             @OA\Property(property="name", type="string", example="Julio"),
     *             @OA\Property(property="last_name", type="string", example="Cortázar"),
     *             @OA\Property(property="nationality", type="string", example="Argentina")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Autor actualizado correctamente",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="Author updated successfully"),
     *             @OA\Property(property="author", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Julio"),
     *                 @OA\Property(property="last_name", type="string", example="Cortázar"),
     *                 @OA\Property(property="nationality", type="string", example="Argentina"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-01-01T00:00:00Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-01-02T00:00:00Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Autor no encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Author not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autenticado"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'nationality' => 'required|string|max:255'
        ]);
        $author = Author::find($id);
        if (!$author) {
            return response()->json(['message' => 'Author not found'], 404);
        }
        $author->name = $request->name;
        $author->last_name = $request->last_name;
        $author->nationality = $request->nationality;
        $author->save();
        $response = [
            'code' => 200,
            'message' => 'Author updated successfully',
            'author' => $author
        ];
        return response()->json($response);
    }

    /**
     * @OA\Delete(
     *     path="/api/authors/{id}",
     *     summary="Eliminar un autor",
     *     description="Elimina un autor existente por su ID. Requiere autenticación con Bearer Token.",
     *     operationId="deleteAuthor",
     *     tags={"Authors"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del autor a eliminar",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Autor eliminado correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="Author deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Autor no encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Author not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autenticado"
     *     )
     * )
     */
    public function destroy($id)
    {
        $author = Author::find($id);
        if (!$author) {
            return response()->json(['message' => 'Author not found'], 404);
        }
        $author->delete();
        $response = [
            'code' => 200,
            'message' => 'Author deleted successfully'
        ];
        return response()->json($response);
    }

    /**
     * @OA\Get(
     *     path="/api/authors/name",
     *     summary="Buscar autor por nombre",
     *     description="Retorna un autor que coincida con el nombre proporcionado. Requiere autenticación con Bearer Token.",
     *     operationId="getAuthorByName",
     *     tags={"Authors"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         description="Nombre del autor a buscar",
     *         required=true,
     *         @OA\Schema(type="string", example="Gabriel")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Autor encontrado correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="Author found successfully"),
     *             @OA\Property(
     *                 property="author",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Gabriel"),
     *                 @OA\Property(property="last_name", type="string", example="García Márquez"),
     *                 @OA\Property(property="nationality", type="string", example="Colombian"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Autor no encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Author not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autenticado"
     *     )
     * )
     */
    public function showByName(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);
        $name = $request->name;
        $author = Author::where('name', $name)->first();
        if (!$author) {
            return response()->json(['message' => 'Author not found'], 404);
        }
        $response = [
            'code' => 200,
            'message' => 'Author found successfully',
            'author' => $author
        ];
        return response()->json($response);
    }

    /**
     * @OA\Get(
     *     path="/api/authors/last_name",
     *     summary="Buscar autor por apellido",
     *     description="Retorna un autor que coincida con el apellido proporcionado. Requiere autenticación con Bearer Token.",
     *     operationId="getAuthorByLastName",
     *     tags={"Authors"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="last_name",
     *         in="query",
     *         description="Apellido del autor a buscar",
     *         required=true,
     *         @OA\Schema(type="string", example="García Márquez")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Autor encontrado correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="Author found successfully"),
     *             @OA\Property(
     *                 property="author",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Gabriel"),
     *                 @OA\Property(property="last_name", type="string", example="García Márquez"),
     *                 @OA\Property(property="nationality", type="string", example="Colombian"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Autor no encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Author not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autenticado"
     *     )
     * )
     */
    public function showByLastName(Request $request)
    {
        $request->validate([
            'last_name' => 'required|string|max:255'
        ]);
        $last_name = $request->last_name;
        $author = Author::where('last_name', $last_name)->first();
        if (!$author) {
            return response()->json(['message' => 'Author not found'], 404);
        }
        $response = [
            'code' => 200,
            'message' => 'Author found successfully',
            'author' => $author
        ];
        return response()->json($response);
    }

    /**
     * @OA\Get(
     *     path="/api/authors/nationality",
     *     summary="Buscar autor por nacionalidad",
     *     description="Retorna un autor que coincida con la nacionalidad proporcionada. Requiere autenticación con Bearer Token.",
     *     operationId="getAuthorByNationality",
     *     tags={"Authors"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="nationality",
     *         in="query",
     *         description="Nacionalidad del autor a buscar",
     *         required=true,
     *         @OA\Schema(type="string", example="Colombian")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Autor encontrado correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="Author found successfully"),
     *             @OA\Property(
     *                 property="author",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Gabriel"),
     *                 @OA\Property(property="last_name", type="string", example="García Márquez"),
     *                 @OA\Property(property="nationality", type="string", example="Colombian"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Autor no encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Author not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autenticado"
     *     )
     * )
     */
    public function showByNationality(Request $request)
    {
        $request->validate([
            'nationality' => 'required|string|max:255'
        ]);
        $nationality = $request->nationality;
        $author = Author::where('nationality', $nationality)->first();
        if (!$author) {
            return response()->json(['message' => 'Author not found'], 404);
        }
        $response = [
            'code' => 200,
            'message' => 'Author found successfully',
            'author' => $author
        ];
        return response()->json($response);
    }
}
