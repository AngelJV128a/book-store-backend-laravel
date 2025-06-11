<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * @OA\Schema(
 *     schema="Book",
 *     type="object",
 *     title="Book",
 *     required={"id", "title", "author", "published_year"},
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         format="int64",
 *         example=1,
 *         description="ID único del libro"
 *     ),
 *     @OA\Property(
 *         property="title",
 *         type="string",
 *         example="Cien años de soledad",
 *         description="Título del libro"
 *     ),
 *     @OA\Property(
 *         property="author",
 *         type="string",
 *         example="Gabriel García Márquez",
 *         description="Autor del libro"
 *     ),
 *     @OA\Property(
 *         property="published_year",
 *         type="integer",
 *         example=1967,
 *         description="Año de publicación"
 *     ),
 *     @OA\Property(
 *         property="genre",
 *         type="string",
 *         example="Realismo mágico",
 *         description="Género literario"
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         type="string",
 *         format="date-time",
 *         description="Fecha de creación del registro"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         type="string",
 *         format="date-time",
 *         description="Fecha de última actualización del registro"
 *     )
 * )
 */
class BookController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * @OA\Get(
     *     path="/api/books",
     *     summary="Listar libros",
     *     description="Devuelve una lista paginada de libros",
     *     operationId="getBooks",
     *     tags={"Books"},
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Número de página para la paginación",
     *         required=false,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista paginada de libros",
     *         @OA\JsonContent(
     *             @OA\Property(property="current_page", type="integer", example=1),
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(ref="#/components/schemas/Book")
     *             ),
     *             @OA\Property(property="total", type="integer", example=100),
     *             @OA\Property(property="per_page", type="integer", example=10),
     *             @OA\Property(property="last_page", type="integer", example=10),
     *             @OA\Property(property="next_page_url", type="string", example="http://localhost/api/books?page=2"),
     *             @OA\Property(property="prev_page_url", type="string", example=null)
     *         )
     *     )
     * )
     */
    public function index()
    {
        $books = Book::with('author', 'editorial', 'category')->paginate(10);
        return response()->json($books);
    }

    /**
     * @OA\Post(
     *     path="/api/books",
     *     summary="Crear un libro",
     *     description="Crea un nuevo libro con la información proporcionada",
     *     operationId="storeBook",
     *     tags={"Books"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title","author_id","isbn","editorial_id","category_id","stock","release_date","language","image","price"},
     *             @OA\Property(property="title", type="string", example="Cien años de soledad"),
     *             @OA\Property(property="author_id", type="string", format="uuid", example="f47ac10b-58cc-4372-a567-0e02b2c3d479"),
     *             @OA\Property(property="isbn", type="string", example="978-3-16-148410-0"),
     *             @OA\Property(property="editorial_id", type="integer", example=1),
     *             @OA\Property(property="category_id", type="integer", example=2),
     *             @OA\Property(property="stock", type="integer", example=100),
     *             @OA\Property(property="release_date", type="string", format="date", example="1967-05-05"),
     *             @OA\Property(property="language", type="string", example="Español"),
     *             @OA\Property(property="image", type="string", example="http://example.com/images/book1.jpg"),
     *             @OA\Property(property="price", type="number", format="float", example=29.99),
     *             @OA\Property(property="description", type="string", example="Una novela mágica y famosa de Gabriel García Márquez")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Libro creado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="Book created successfully"),
     *             @OA\Property(property="book", ref="#/components/schemas/Book")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Solicitud inválida"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'author_id' => 'required|string|max:255',
            'isbn' => 'required|string|max:255',
            'editorial_id' => 'required|string|max:255',
            'category_id' => 'required|string|max:255',
            'stock' => 'required|integer|min:0',
            'release_date' => 'required|string|max:255',
            'language' => 'required|string|max:255',
            'image' => 'required|string|max:255',
            'price' => 'required|numeric|min:0'
        ]);
        $book = new Book();
        $book->id = Str::uuid();
        $book->title = $request->title;
        $book->author_id = $request->author_id;
        $book->isbn = $request->isbn;
        $book->editorial_id = $request->editorial_id;
        $book->category_id = $request->category_id;
        $book->stock = $request->stock;
        $book->release_date = $request->release_date;
        $book->language = $request->language;
        $book->image = $request->image;
        $book->price = $request->price;
        if ($request->has('description')) {
            $book->description = $request->description;
        }
        $book->save();
        $response = [
            'code' => 200,
            'message' => 'Book created successfully',
            'book' => $book
        ];
        return response()->json($response);
    }

    /**
     * @OA\Get(
     *     path="/api/books/show",
     *     summary="Obtener un libro por ID",
     *     description="Devuelve los detalles de un libro específico dado su ID",
     *     operationId="showBook",
     *     tags={"Books"},
     *     @OA\Parameter(
     *         name="id",
     *         in="query",
     *         description="ID del libro (UUID)",
     *         required=true,
     *         @OA\Schema(type="string", format="uuid", example="f47ac10b-58cc-4372-a567-0e02b2c3d479")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Libro encontrado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="Book found successfully"),
     *             @OA\Property(property="book", ref="#/components/schemas/Book")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Libro no encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Book not found")
     *         )
     *     )
     * )
     */
    public function show(Request $request)
    {
        $id = $request->id;
        $book = Book::with('author', 'editorial', 'category')->find($id);
        if (!$book) {
            return response()->json(['message' => 'Book not found'], 404);
        }
        $response = [
            'code' => 200,
            'message' => 'Book found successfully',
            'book' => $book
        ];
        return response()->json($response);
    }

    /**
     * @OA\Put(
     *     path="/api/books/{id}",
     *     summary="Actualizar un libro existente",
     *     description="Actualiza los detalles de un libro dado su ID",
     *     operationId="updateBook",
     *     tags={"Books"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del libro a actualizar",
     *         required=true,
     *         @OA\Schema(type="string", format="uuid", example="f47ac10b-58cc-4372-a567-0e02b2c3d479")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Datos para actualizar el libro",
     *         @OA\JsonContent(
     *             required={"title","author_id","isbn","editorial_id","category_id","stock","release_date","language","image","price"},
     *             @OA\Property(property="title", type="string", example="El Principito"),
     *             @OA\Property(property="author_id", type="string", format="uuid", example="a1b2c3d4-e5f6-7890-abcd-1234567890ef"),
     *             @OA\Property(property="isbn", type="string", example="978-3-16-148410-0"),
     *             @OA\Property(property="editorial_id", type="string", format="uuid", example="c1d2e3f4-5678-90ab-cdef-1234567890ab"),
     *             @OA\Property(property="category_id", type="string", format="uuid", example="d1e2f3a4-5678-90ab-cdef-1234567890cd"),
     *             @OA\Property(property="stock", type="integer", example=15),
     *             @OA\Property(property="release_date", type="string", format="date", example="2024-05-01"),
     *             @OA\Property(property="language", type="string", example="Español"),
     *             @OA\Property(property="image", type="string", example="https://example.com/images/book.jpg"),
     *             @OA\Property(property="price", type="number", format="float", example=19.99),
     *             @OA\Property(property="description", type="string", example="Una maravillosa historia sobre la infancia y la imaginación.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Libro actualizado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="Book updated successfully"),
     *             @OA\Property(property="book", ref="#/components/schemas/Book")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Libro no encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Book not found")
     *         )
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'author_id' => 'required|string|max:255',
            'isbn' => 'required|string|max:255',
            'editorial_id' => 'required|string|max:255',
            'category_id' => 'required|string|max:255',
            'stock' => 'required|integer|min:0',
            'release_date' => 'required|string|max:255',
            'language' => 'required|string|max:255',
            'image' => 'required|string|max:255',
            'price' => 'required|numeric|min:0'
        ]);
        $book = Book::find($id);
        if (!$book) {
            return response()->json(['message' => 'Book not found'], 404);
        }
        $book->title = $request->title;
        $book->author_id = $request->author_id;
        $book->isbn = $request->isbn;
        $book->editorial_id = $request->editorial_id;
        $book->category_id = $request->category_id;
        $book->stock = $request->stock;
        $book->release_date = $request->release_date;
        $book->language = $request->language;
        $book->image = $request->image;
        $book->price = $request->price;
        if ($request->has('description')) {
            $book->description = $request->description;
        }
        $book->save();
        $response = [
            'code' => 200,
            'message' => 'Book updated successfully',
            'book' => $book
        ];
        return response()->json($response);
    }

    /**
     * @OA\Delete(
     *     path="/api/books/{id}",
     *     summary="Eliminar un libro",
     *     description="Elimina un libro dado su ID",
     *     operationId="deleteBook",
     *     tags={"Books"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del libro a eliminar",
     *         required=true,
     *         @OA\Schema(type="string", format="uuid", example="f47ac10b-58cc-4372-a567-0e02b2c3d479")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Libro eliminado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="Book deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Libro no encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Book not found")
     *         )
     *     )
     * )
     */
    public function destroy($id)
    {
        $book = Book::find($id);
        if (!$book) {
            return response()->json(['message' => 'Book not found'], 404);
        }
        $book->delete();
        $response = [
            'code' => 200,
            'message' => 'Book deleted successfully'
        ];
        return response()->json($response);
    }

    /**
     * @OA\Get(
     *     path="/api/books/by-author",
     *     summary="Buscar libro por autor",
     *     description="Obtiene el primer libro que corresponde al ID del autor dado",
     *     operationId="getBookByAuthor",
     *     tags={"Books"},
     *     @OA\Parameter(
     *         name="author_id",
     *         in="query",
     *         description="ID del autor",
     *         required=true,
     *         @OA\Schema(type="string", format="uuid", example="a123bc45-d678-9ef0-1234-56789abcde01")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Libro encontrado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="Book found successfully"),
     *             @OA\Property(property="book", ref="#/components/schemas/Book")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Libro no encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Book not found")
     *         )
     *     )
     * )
     */
    public function showByAuthor($id)
    {
        $books = Book::where('author_id', $id)->with(['author','editorial'])->paginate(10);
        if (!$books) {
            return response()->json(['message' => 'Book not found'], 404);
        }
        $response = [
            'code' => 200,
            'message' => 'Book found successfully',
            'books' => $books
        ];
        return response()->json($response);
    }

    /**
     * @OA\Get(
     *     path="/api/books/by-editorial",
     *     summary="Buscar libro por editorial",
     *     description="Obtiene el primer libro que corresponde al ID de la editorial dado",
     *     operationId="getBookByEditorial",
     *     tags={"Books"},
     *     @OA\Parameter(
     *         name="editorial_id",
     *         in="query",
     *         description="ID de la editorial",
     *         required=true,
     *         @OA\Schema(type="string", format="uuid", example="b123cd45-e678-9fa0-2345-6789bcdef012")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Libro encontrado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="Book found successfully"),
     *             @OA\Property(property="book", ref="#/components/schemas/Book")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Libro no encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Book not found")
     *         )
     *     )
     * )
     */
    public function showByEditorial(Request $request)
    {
        $request->validate([
            'editorial_id' => 'required|string|max:255'
        ]);
        $editorial_id = $request->editorial_id;
        $book = Book::where('editorial_id', $editorial_id)->with('editorial')->first();
        if (!$book) {
            return response()->json(['message' => 'Book not found'], 404);
        }
        $response = [
            'code' => 200,
            'message' => 'Book found successfully',
            'book' => $book
        ];
        return response()->json($response);
    }

    /**
     * @OA\Get(
     *     path="/api/books/by-category",
     *     summary="Buscar libro por categoría",
     *     description="Obtiene el primer libro que corresponde al ID de la categoría dada",
     *     operationId="getBookByCategory",
     *     tags={"Books"},
     *     @OA\Parameter(
     *         name="category_id",
     *         in="query",
     *         description="ID de la categoría",
     *         required=true,
     *         @OA\Schema(type="string", format="uuid", example="c789de12-f345-6789-abcd-ef0123456789")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Libro encontrado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="Book found successfully"),
     *             @OA\Property(property="book", ref="#/components/schemas/Book")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Libro no encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Book not found")
     *         )
     *     )
     * )
     */
    public function showByCategory(Request $request)
    {
        $request->validate([
            'category_id' => 'required|string|max:255'
        ]);
        $category_id = $request->category_id;
        $book = Book::where('category_id', $category_id)->with('category')->first();
        if (!$book) {
            return response()->json(['message' => 'Book not found'], 404);
        }
        $response = [
            'code' => 200,
            'message' => 'Book found successfully',
            'book' => $book
        ];
        return response()->json($response);
    }

    /**
     * @OA\Get(
     *     path="/api/books/filter",
     *     summary="Filtrar libros",
     *     description="Obtiene libros filtrados por categoría, idioma y rango de precio",
     *     operationId="filterBooks",
     *     tags={"Books"},
     *     @OA\Parameter(
     *         name="category_id",
     *         in="query",
     *         description="ID de la categoría (usar 'all' para ignorar filtro)",
     *         required=false,
     *         @OA\Schema(type="string", format="uuid", example="c789de12-f345-6789-abcd-ef0123456789")
     *     ),
     *     @OA\Parameter(
     *         name="language",
     *         in="query",
     *         description="Idioma del libro (usar 'all' para ignorar filtro)",
     *         required=false,
     *         @OA\Schema(type="string", example="es")
     *     ),
     *     @OA\Parameter(
     *         name="min_price",
     *         in="query",
     *         description="Precio mínimo",
     *         required=false,
     *         @OA\Schema(type="number", format="float", example=10.0)
     *     ),
     *     @OA\Parameter(
     *         name="max_price",
     *         in="query",
     *         description="Precio máximo",
     *         required=false,
     *         @OA\Schema(type="number", format="float", example=50.0)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Libros encontrados exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="Books found successfully"),
     *             @OA\Property(property="books", type="object",
     *                 @OA\Property(property="current_page", type="integer", example=1),
     *                 @OA\Property(property="data", type="array",
     *                     @OA\Items(ref="#/components/schemas/Book")
     *                 ),
     *                 @OA\Property(property="total", type="integer", example=25),
     *                 @OA\Property(property="per_page", type="integer", example=10),
     *                 @OA\Property(property="last_page", type="integer", example=3),
     *                 @OA\Property(property="next_page_url", type="string", example="http://localhost/api/books/filter?page=2"),
     *                 @OA\Property(property="prev_page_url", type="string", example=null),
     *             )
     *         )
     *     )
     * )
     */
    public function showByFilters(Request $request)
    {
        $query = Book::with('category');

        if ($request->filled('category_id') && $request->category_id !== 'all') {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('language') && $request->language !== 'all') {
            $query->where('language', $request->language);
        }

        if ($request->filled('min_price') && $request->filled('max_price')) {
            $query->whereBetween('price', [$request->min_price, $request->max_price]);
        }

        $books = $query->paginate(10);

        return response()->json([
            'code' => 200,
            'message' => 'Books found successfully',
            'books' => $books
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/books/title",
     *     summary="Buscar libro por título",
     *     description="Obtiene un libro que coincida exactamente con el título proporcionado",
     *     operationId="getBookByTitle",
     *     tags={"Books"},
     *     @OA\Parameter(
     *         name="title",
     *         in="query",
     *         description="Título exacto del libro a buscar",
     *         required=true,
     *         @OA\Schema(type="string", example="Cien años de soledad")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Libro encontrado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="Book found successfully"),
     *             @OA\Property(property="book", ref="#/components/schemas/Book")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Libro no encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Book not found")
     *         )
     *     )
     * )
     */
    public function showByTitle(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255'
        ]);
        $title = $request->title;
        $book = Book::where('title', $title)->first();
        if (!$book) {
            return response()->json(['message' => 'Book not found'], 404);
        }
        $response = [
            'code' => 200,
            'message' => 'Book found successfully',
            'book' => $book
        ];
        return response()->json($response);
    }

    public function showRandomBooks(){
        $books = Book::inRandomOrder()->with('author', 'editorial', 'category')->paginate(10);
        return response()->json($books);
    }
}
