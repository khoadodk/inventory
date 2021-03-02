<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Customer;
use Image;
use DB;

class CustomerController extends Controller 
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $customer = Customer::all();
       return response()->json($customer);
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
         'name' => 'required|unique:customers|max:255',
         'phone' => 'required',
        ]);
      $customer = new Customer;
         // extract image
      if ($request->photo) {
         $position = strpos($request->photo, ';');
         $sub = substr($request->photo, 0, $position);
         $ext = explode('/', $sub)[1];

         $name = time().".".$ext;
         $img = Image::make($request->photo)->resize(240,240);
         $upload_path = 'backend/customer/';
         $image_url = $upload_path.$name;
         $img->save($image_url);

         $customer->photo = $image_url;
     }else {
        $customer->photo = 'backend/noImage.png';
     }
         $customer->name = $request->name;
         $customer->email = $request->email;
         $customer->phone = $request->phone;  
         $customer->address = $request->address;
         $customer->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
       $customer = DB::table('customers')->where('id',$id)->first();
       return response()->json($customer);
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
        $data['name'] = $request->name;
        $data['email'] = $request->email;
        $data['phone'] = $request->phone;
        $data['address'] = $request->address;
        $image = $request->newphoto;
         // Check if there is a new image
        if ($image && $image !== "backend/noImage.png") {
         $position = strpos($image, ';');
         $sub = substr($image, 0, $position);
         $ext = explode('/', $sub)[1];

         $name = time().".".$ext;
         $img = Image::make($image)->resize(240,200);
         $upload_path = 'backend/customer/';
         $image_url = $upload_path.$name;
         $success = $img->save($image_url);
         // Remove image
         if ($success) {
            $data['photo'] = $image_url;
            $img = DB::table('customers')->where('id',$id)->first();
            $image_path = $img->photo;
            if($image_path !== "backend/noImage.png"){
            unlink($image_path);
            }
            DB::table('customers')->where('id',$id)->update($data);
         }
         // Keep the old photo
        }else{
            $oldphoto = $request->photo;
            $data['photo'] = $oldphoto;
            DB::table('customers')->where('id',$id)->update($data);
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
       $customer = DB::table('customers')->where('id',$id)->first();
       $photo = $customer->photo;

       if ($photo !== "backend/noImage.png") {
         unlink($photo);
         DB::table('customers')->where('id',$id)->delete();
       }else{
        DB::table('customers')->where('id',$id)->delete();
       }
    }
}