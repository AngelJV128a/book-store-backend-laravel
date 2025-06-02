<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BookController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {
        $books = Book::paginate(10);
        return response()->json($books);
    }

    public function store(Request $request){
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
        if($request->has('description')){
            $book->description = $request->description;
        }
        $book->save();
        $response =[
            'code' => 200,
            'message' => 'Book created successfully',
            'book' => $book
        ];
        return response()->json($response);
    }

    public function show(Request $request){
        $id = $request->id;
        $book = Book::find($id);
        if(!$book){
            return response()->json(['message' => 'Book not found'], 404);
        }
        $response =[
            'code' => 200,
            'message' => 'Book found successfully',
            'book' => $book
        ];
        return response()->json($response);
    }
    
    public function update(Request $request, $id){
        $book = Book::find($id);
        if(!$book){
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
        if($request->has('description')){
            $book->description = $request->description;
        }
        $book->save();
        $response =[
            'code' => 200,
            'message' => 'Book updated successfully',
            'book' => $book
        ];
        return response()->json($response);
    }

    public function destroy($id){
        $book = Book::find($id);
        if(!$book){
            return response()->json(['message' => 'Book not found'], 404);
        }
        $book->delete();
        $response =[
            'code' => 200,
            'message' => 'Book deleted successfully'
        ];
        return response()->json($response);
    }

    public function showByAuthor(Request $request){
        $author_id = $request->author_id;
        $book = Book::where('author_id', $author_id)->with('author')->first();
        if(!$book){
            return response()->json(['message' => 'Book not found'], 404);
        }
        $response =[
            'code' => 200,
            'message' => 'Book found successfully',
            'book' => $book
        ];
        return response()->json($response);
    }

    public function showByEditorial(Request $request){
        $editorial_id = $request->editorial_id;
        $book = Book::where('editorial_id', $editorial_id)->with('editorial')->first();
        if(!$book){
            return response()->json(['message' => 'Book not found'], 404);
        }
        $response =[
            'code' => 200,
            'message' => 'Book found successfully',
            'book' => $book
        ];
        return response()->json($response);
    }

    public function showByCategory(Request $request){
        $category_id = $request->category_id;
        $book = Book::where('category_id', $category_id)->with('category')->first();
        if(!$book){
            return response()->json(['message' => 'Book not found'], 404);
        }
        $response =[
            'code' => 200,
            'message' => 'Book found successfully',
            'book' => $book
        ];
        return response()->json($response);
    }

    public function showByFilters(Request $request){
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

    public function showByTitle(Request $request){
        $title = $request->title;
        $book = Book::where('title', $title)->first();
        if(!$book){
            return response()->json(['message' => 'Book not found'], 404);
        }
        $response =[
            'code' => 200,
            'message' => 'Book found successfully',
            'book' => $book
        ];
        return response()->json($response);
    }
}
