<?php

use App\Area;
use App\Http\Resources;
use App\Product;
use App\Region;
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

Route::get('products',function(Request $request){

    return Resources\Product::collection(Product::all($request->query->all()));

})->name('products');

Route::get('areas',function(Request $request){

    return Resources\Area::collection(Area::all($request->query->all()));

})->name('areas');

Route::get('regions',function(Request $request){

    return Resources\Region::collection(Region::all($request->query->all()));

})->name('regions');



