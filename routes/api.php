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

    $filter             = $request->query->all();
    $key                = 'products::' . serialize($filter);
    $resourceCollection = Cache::get($key, function() use ($key,$filter){
        $resourceCollection = Resources\Product::collection(Product::all($filter));
        \Log::debug('adding to cache '.$key);
        Cache::put($key,$resourceCollection,now()->addMinutes(60));
        return $resourceCollection;
    });
    return $resourceCollection;

})->name('products');

Route::get('areas',function(Request $request){

    $filter             = $request->query->all();
    $key                = 'areas::' . serialize($filter);
    $resourceCollection = Cache::get($key, function() use ($key,$filter){
        $resourceCollection = Resources\Area::collection(Area::all($filter));
        \Log::debug('adding to cache '.$key);
        Cache::put($key,$resourceCollection,now()->addMinutes(60));
        return $resourceCollection;
    });
    return $resourceCollection;

})->name('areas');

Route::get('regions',function(Request $request){

    $filter = $request->query->all();
    $key                = 'regions::' . serialize($filter);
    $resourceCollection = Cache::get($key, function() use ($key,$filter){
        $resourceCollection = Resources\Region::collection(Region::all($filter));
        \Log::debug('adding to cache '.$key);
        Cache::put($key,$resourceCollection,now()->addMinutes(60));
        return $resourceCollection;
    });

    return $resourceCollection;

})->name('regions');

Route::get('product/{product}',function(Product $product){

    $resource = Resources\Product::make($product);
    return $resource;

})->name('product');



