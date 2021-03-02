<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Product;
use Image;
use DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $product = DB::table('products')
                    ->join('categories','products.category_id','categories.id')
                    ->join('suppliers','products.supplier_id','suppliers.id')
                    ->select('categories.category_name','suppliers.supplier_name','products.*')
                    ->orderBy('products.id','DESC')
                    ->get();
                    return response()->json($product);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validateData = $request->validate([
         'product_name' => 'required|max:255',
         'product_code' => 'required|unique:products|max:255',
         'category_id' => 'required',
         'supplier_id' => 'required',
         'buying_price'=> 'required',
         'selling_price'=>'required',
         'purchase_date'=>'required',
         'product_quantity'=>'required'
        ]);
      $product = new Product;
         // extract image
      if ($request->product_image) {
         $position = strpos($request->product_image, ';');
         $sub = substr($request->product_image, 0, $position);
         $ext = explode('/', $sub)[1];

         $name = time().".".$ext;
         $img = Image::make($request->product_image)->resize(240,240);
         $upload_path = 'backend/product';
         $image_url = $upload_path.$name;
         $img->save($image_url);

         $product->product_image = $image_url;
     }else {
        $product->product_image = 'backend/noImage.png';
     }
         $product->product_name = $request->product_name;
         $product->product_code = $request->product_code;
         $product->category_id = $request->category_id;
         $product->supplier_id = $request->supplier_id;
         $product->buying_price = $request->buying_price;
         $product->selling_price = $request->selling_price;
         $product->purchase_date = $request->purchase_date;
         $product->product_quantity = $request->product_quantity;
         $product->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = DB::table('products')->where('id',$id)->first();
       return response()->json($product);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = array();
        $data['product_name'] = $request->product_name;
        $data['product_code'] = $request->product_code;
        $data['product_quantity'] = $request->product_quantity;
        $data['selling_price'] = $request->selling_price;
        $data['buying_price'] = $request->buying_price;
        $data['category_id'] = $request->category_id;
        $data['supplier_id'] = $request->supplier_id;
        $data['purchase_date'] = $request->purchase_date;
        $image = $request->newphoto;
         // Check if there is a new image
        if ($image && $image !== "backend/noImage.png") {
            $position = strpos($image, ';');
            $sub = substr($image, 0, $position);
            $ext = explode('/', $sub)[1];

            $name = time().".".$ext;
            $img = Image::make($image)->resize(240,200);
            $upload_path = 'backend/product/';
            $image_url = $upload_path.$name;
            $success = $img->save($image_url);
            // Remove image
            if ($success) {
                $data['product_image'] = $image_url;
                $img = DB::table('products')->where('id',$id)->first();
                $image_path = $img->product_image;
                if($image_path !== "backend/noImage.png"){
                    unlink($image_path);
                };
            DB::table('products')->where('id',$id)->update($data);
         }
         // Keep the old photo
        }else{
            $oldphoto = $request->product_image;
            $data['product_image'] = $oldphoto;
            DB::table('products')->where('id',$id)->update($data);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = DB::table('products')->where('id',$id)->first();
        $photo = $product->product_image;

       if ($photo !== "backend/noImage.png") {
         unlink($photo);
         DB::table('products')->where('id',$id)->delete();
       }else{
        DB::table('products')->where('id',$id)->delete();
       }
    }
}
