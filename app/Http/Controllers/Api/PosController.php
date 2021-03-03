<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Product;
use DB;

class PosController extends Controller
{
    public function getProductsByCat($id){
        $product = DB::table('products')->where('category_id', $id)->get();
        return response()->json($product);
    }
}
