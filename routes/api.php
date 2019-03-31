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


    // todo setup caching here with the resource collection instead of the product model collection because a product
    // model with the same id will contain different attribute naming depending on which endpoint was used. For example
    // a product model created from the products search endpoint will have different address attribute names to a
    // product model created from the product find endpoint. So we will cache the page - collection of products against
    // the filter/query instead of caching individual products against product ids.
    // Ideally we could map the two endpoint "product" attribute sets into a common product attribute set for thr
    // created model, but that would be a lot of work for little gain at this time.

    $all = Product::all($request->query->all());

    return Resources\Product::collection($all);

})->name('products');

Route::get('areas',function(Request $request){

    // todo setup resource caching here

    return Resources\Area::collection(Area::all($request->query->all()));

})->name('areas');

Route::get('regions',function(Request $request){

    // todo setup resource caching here

    return Resources\Region::collection(Region::all($request->query->all()));

})->name('regions');

Route::get('product/{product}',function(Product $product){

    // todo setup resource caching here

    return Resources\Product::make($product);

})->name('product');



