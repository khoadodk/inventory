<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Salary;
use DB;


class SalaryController extends Controller
{
    // id -- employee_id
    public function paid(Request $request, $id){
        $request->validate([
            'salary_month' => 'required',
            'amount' => 'required'
        ]);

        // Check if salary has been already paid to the employee
        $month = $request->salary_month;
        $check = DB::table('salaries')->where('employee_id',$id)->where('salary_month', $month)->first();
        $check
        ? response()->json('Salary already paid for this month!')
        :   $data = array();
            $data['employee_id']=$id;
            $data['amount']=$request->amount;
            $data['salary_date']=date('m/d/Y');
            $data['salary_month']=$month;
            $data['salary_year']=date('Y');
            DB::table('salaries')->insert($data);
    }

    public function allSalary(){
        $salary = Salary::all();
        return response()->json($salary);
    }
    // id-- month
    public function viewSalary($id){
        $month = $id;
        $view = DB::table('salaries')->join('employees', 'salaries.employee_id', 'employees.id')->select('employees.name','salaries.*')->where('salaries.salary_month', $month)->get();
        return response()->json($view);
    }

    public function editSalary($id){
        $view = DB::table('salaries')->join('employees', 'salaries.employee_id', 'employees.id')->select('employees.name','employees.email','salaries.*')->where('salaries.id', $id)->first();
        return response()->json($view);
    }
    
    public function updateSalary(Request $request, $id){
        
        $data = array();
        $data['employee_id']=$request->employee_id;
        $data['amount']=$request->amount;
        $data['salary_month']=$request->salary_month;
        DB::table('salaries')->where('id', $id)->update($data);
    }
}
