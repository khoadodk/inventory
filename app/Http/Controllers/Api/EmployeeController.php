<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Employee;
use Image;
use DB;

class EmployeeController extends Controller 
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $employee = Employee::all();
       return response()->json($employee);
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
         'name' => 'required|unique:employees|max:255',
         'email' => 'required|unique:employees',
         'phone' => 'required|unique:employees',
        ]);
      $employee = new Employee;
         // extract image
      if ($request->photo) {
         $position = strpos($request->photo, ';');
         $sub = substr($request->photo, 0, $position);
         $ext = explode('/', $sub)[1];

         $name = time().".".$ext;
         $img = Image::make($request->photo)->resize(240,240);
         $upload_path = 'backend/employee/';
         $image_url = $upload_path.$name;
         $img->save($image_url);

         $employee->photo = $image_url;
     }else {
        $employee->photo = 'backend/employee/noImage.png';
     }
         $employee->name = $request->name;
         $employee->email = $request->email;
         $employee->phone = $request->phone;
         $employee->salary = $request->salary;
         $employee->address = $request->address;
         $employee->employee_id = $request->employee_id;
         $employee->join_date = $request->join_date;
         $employee->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
       $employee = DB::table('employees')->where('id',$id)->first();
       return response()->json($employee);
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
        $data['salary'] = $request->salary;
        $data['address'] = $request->address;
        $data['employee_id'] = $request->employee_id;
        $data['join_date'] = $request->join_date;
        $image = $request->newphoto;
         // Check if there is a new image
        if ($image && $image !== "backend/employee/noImage.png") {
         $position = strpos($image, ';');
         $sub = substr($image, 0, $position);
         $ext = explode('/', $sub)[1];

         $name = time().".".$ext;
         $img = Image::make($image)->resize(240,200);
         $upload_path = 'backend/employee/';
         $image_url = $upload_path.$name;
         $success = $img->save($image_url);
         // Remove image
         if ($success) {
            $data['photo'] = $image_url;
            $img = DB::table('employees')->where('id',$id)->first();
            $image_path = $img->photo;
            if($image_path !== "backend/employee/noImage.png"){
            unlink($image_path);
            }
            DB::table('employees')->where('id',$id)->update($data);
         }
         // Keep the old photo
        }else{
            $oldphoto = $request->photo;
            $data['photo'] = $oldphoto;
            DB::table('employees')->where('id',$id)->update($data);
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
       $employee = DB::table('employees')->where('id',$id)->first();
       $photo = $employee->photo;

       if ($photo !== "backend/employee/noImage.png") {
         unlink($photo);
         DB::table('employees')->where('id',$id)->delete();
       }else{
        DB::table('employees')->where('id',$id)->delete();
       }
    }

 
}
