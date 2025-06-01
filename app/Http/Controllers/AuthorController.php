<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {
        $authors = ["Autor"=>"Angel","Autor"=>"Juan"];
        return response()->json($authors);
    }

}
