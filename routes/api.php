<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\EditorialController;
use App\Http\Controllers\SaleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');
    Route::post('/register', [AuthController::class, 'register']);

});

Route::get('/authors', [AuthorController::class, 'index']);
Route::post('/authors', [AuthorController::class, 'store']);
Route::get('/authors/name', [AuthorController::class, 'showByName']);
Route::get('/authors/last_name', [AuthorController::class, 'showByLastName']);
Route::get('/authors/nationality', [AuthorController::class, 'showByNationality']);
Route::get('/authors/{id}', [AuthorController::class, 'show']);
Route::put('/authors/{id}', [AuthorController::class, 'update']);
Route::delete('/authors/{id}', [AuthorController::class, 'destroy']);

Route::get('/editorials', [EditorialController::class, 'index']);
Route::post('/editorials', [EditorialController::class, 'store']);
Route::get('/editorials/name', [EditorialController::class, 'showByName']);
Route::get('/editorials/country', [EditorialController::class, 'showByCountry']);
Route::get('/editorials/{id}', [EditorialController::class, 'show']);
Route::put('/editorials/{id}', [EditorialController::class, 'update']);
Route::delete('/editorials/{id}', [EditorialController::class, 'destroy']); 

Route::get('/categories', [CategoryController::class, 'index']);
Route::post('/categories', [CategoryController::class, 'store']);
Route::get('/categories/name', [CategoryController::class, 'showByName']);
Route::get('/categories/{id}', [CategoryController::class, 'show']);
Route::put('/categories/{id}', [CategoryController::class, 'update']);
Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);

Route::get('/books', [BookController::class, 'index']);
Route::post('/books', [BookController::class, 'store']);
Route::get('/books/author', [BookController::class, 'showByAuthor']);
Route::get('/books/editorial', [BookController::class, 'showByEditorial']);
Route::get('/books/category', [BookController::class, 'showByCategory']);
Route::get('/books/filters', [BookController::class, 'showByFilters']);
Route::get('/books/title', [BookController::class, 'showByTitle']);
Route::post('/books/search', [BookController::class, 'show']);
Route::get('/books/random', [BookController::class, 'showRandomBooks']);
Route::put('/books/{id}', [BookController::class, 'update']);
Route::delete('/books/{id}', [BookController::class, 'destroy']);

Route::get('/sales', [SaleController::class, 'index']);
Route::post('/sales', [SaleController::class, 'store']);
Route::get('/sales/search', [SaleController::class, 'show']);
Route::delete('/sales/delete', [SaleController::class, 'delete']);
Route::get('/sales/user', [SaleController::class, 'showByUser']);