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
    
    public function todaySell(){
        $today = date('m/d/Y');
        $totalSale = DB::table('orders')->where('order_date', $today)->sum('total');
        return response()->json($totalSale);
    }

    public function todayIncome(){
        $today = date('m/d/Y');
        $totalIncome = DB::table('orders')->where('order_date', $today)->sum('pay_amount');
        return response()->json($totalIncome);
    }
    public function todayDue(){
        $today = date('m/d/Y');
        $totalDue = DB::table('orders')->where('order_date', $today)->sum('due');
        return response()->json($totalDue);
    }
    public function todayExpense(){
        $today = date('m/d/Y');
        $totalExpense = DB::table('expenses')->where('expense_date', $today)->sum('amount');
        return response()->json($totalExpense);
    }
    public function todayStock(){
        $product = DB::table('products')->where('product_quantity', '<', '1')->get();
        return response()->json($product);
    }
}