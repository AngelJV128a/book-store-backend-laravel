<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {
        $categories = Category::paginate(10);
        return response()->json($categories);
    }

    public function store(Request $request){
        $category = new Category();
        $category->name = $request->name;
        $category->description = $request->description;
        $category->save();
        $response =[
            'code' => 200,
            'message' => 'Category created successfully',
            'category' => $category
        ];
        return response()->json($response);
    }

    public function show($id){
        $category = Category::find($id);
        if(!$category){
            return response()->json(['message' => 'Category not found'], 404);
        }
        $response =[
            'code' => 200,
            'message' => 'Category found successfully',
            'category' => $category
        ];
        return response()->json($response);
    }
    
    public function update(Request $request, $id){
        $category = Category::find($id);
        if(!$category){
            return response()->json(['message' => 'Category not found'], 404);
        }
        $category->name = $request->name;
        $category->save();
        $response =[
            'code' => 200,
            'message' => 'Category updated successfully',
            'category' => $category
        ];
        return response()->json($response);
    }

    public function destroy($id){
        $category = Category::find($id);
        if(!$category){
            return response()->json(['message' => 'Category not found'], 404);
        }
        $category->delete();
        $response =[
            'code' => 200,
            'message' => 'Category deleted successfully'
        ];
        return response()->json($response);
    }

    public function showByName(Request $request){
        $name = $request->name;
        $category = Category::where('name', $name)->first();
        if(!$category){
            return response()->json(['message' => 'Category not found'], 404);
        }
        $response =[
            'code' => 200,
            'message' => 'Category found successfully',
            'category' => $category
        ];
        return response()->json($response);
    }
}
