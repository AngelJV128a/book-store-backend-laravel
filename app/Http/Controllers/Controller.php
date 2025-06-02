<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *   title="API Book Store",
 *   description="API para gestionar libros, autores, editoriales y categorias",
 *   version="1.0.0",
 *   @OA\Contact(
 *     email="angelj.vazquez1@gmail.com"
 *   )
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
