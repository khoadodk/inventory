<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Product;
use DB;

class CartController extends Controller
{
    public function addToCart($id){
        $product = DB::table('products')->where('id',$id)->first();
        // check if product already in cart, update quantity by 1, else set quantity by 1
        $productInCart = DB::table('pos')->where('prod_id', $id)->first();
        if($productInCart){
            DB::table('pos')->where('prod_id', $id)->increment('prod_quantity');
        }else {
            $data =array();
            $data['prod_id']= $id;
            $data['prod_name']= $product->product_name;
            $data['prod_quantity']= 1;
            $data['prod_price']= $product->selling_price;
            $data['sub_total']= $product->selling_price;
            DB::table('pos')->insert($data);
        };
    }

    public function getCart(){
        $cart = DB::table('pos')->get();
        return response()->json($cart);
    }

    public function removeItemFromCart($id){
        DB::table('pos')->where('id', $id)->delete();
    }

    public function increment($id){
        DB::table('pos')->where('id',$id)->increment('prod_quantity');

        $product = DB::table('pos')->where('id',$id)->first();
        $subtotal = $product->prod_quantity * $product->prod_price;
        DB::table('pos')->where('id',$id)->update(['sub_total'=> $subtotal]);
    }

    public function decrement($id){
        DB::table('pos')->where('id',$id)->decrement('prod_quantity');

        $product = DB::table('pos')->where('id',$id)->first();
        $subtotal = $product->prod_quantity * $product->prod_price;
        DB::table('pos')->where('id',$id)->update(['sub_total'=> $subtotal]);
    }

    public function tax(){
  	$tax = DB::table('extra')->first();
  	return response()->json($tax);
  }
}
