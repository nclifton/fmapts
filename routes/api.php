<?php

use App\Http\Resources\Product;
use App\Product as ProductModel;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('all',function(Request $request,$page=0){

    $all = ProductModel::all();
    $resource  = Product::collection($all);

    return $resource;


})->name('products');



