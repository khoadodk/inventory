<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Product;
use DB;

use function Ramsey\Uuid\v1;

class PosController extends Controller
{
    public function getProductsByCat($id){
        $product = DB::table('products')->where('category_id', $id)->get();
        return response()->json($product);
    }

    public function posOrder(Request $request){
        $request->validate([
            'customer_id'=>'required',
            'paidby'=>'required',
        ]);
        $data = array();
        $data['customer_id']= $request->customer_id;
        $data['qty']= $request->qty;
        $data['sub_total']= $request->subtotal;
        $data['tax']= $request->tax;
        $data['total']= $request->total;
        $data['pay_amount']= $request->pay_amount;
        $data['due']= $request->due;
        $data['paidby']= $request->paidby;
        $data['order_date']= date('m/d/Y');
        $data['order_month']= date('F');
        $data['order_year']= date('Y');
        // Get order_id from orders table to pass to order_details
        $order_id = DB::table('orders')->insertGetId($data);

        $posDatas = DB::table('pos')->get();
        // Insert data from pos table into order_details table
        $orderDetailsData = array();
        foreach($posDatas as $posDatas){
            $orderDetailsData['order_id']=$order_id;
            $orderDetailsData['product_id']=$posDatas->prod_id;
            $orderDetailsData['product_quantity']=$posDatas->prod_quantity;
            $orderDetailsData['product_price']=$posDatas->prod_price;
            $orderDetailsData['sub_total']=$posDatas->sub_total;
            DB::table('order_details')->insert($orderDetailsData);

            // Update the product quantity in products table
            DB::table('products')
            ->where('id', $posDatas->prod_id)
            ->update(['product_quantity' => DB::raw('product_quantity -'. $posDatas->prod_quantity)]);
        }
        // Delete the data in pos table
        DB::table('pos')->delete();
    }
}
