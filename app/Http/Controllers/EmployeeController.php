<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use App\Models\Department;
use App\Models\Employee;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EmployeeController extends Controller
{
    public function index(){
        $employees = Employee::with('department','achievement')->get();
        $employees = $employees->map(function ($employee) {
            return [
                'id'         => $employee->id,
                'name'       => $employee->name,
                'email'      => $employee->email,
                'phone'      => $employee->phone,
                'address'    => $employee->address,
                'department' => $employee->department->name,
                'achievement' =>implode(', ', $employee->achievement->pluck('name')->toArray()),
            ];
        }); 

        return Inertia::render('employee/index',['employees'=>$employees]);
    } 

    public function create(){
        $departments = Department::all();
        $Achievements = Achievement::all();
        return Inertia::render('employee/create',['departments'=>$departments,'achievements'=>$Achievements]);
    } 

    public function store(Request $request){
        $request->validate([
            'name'       => 'required|string',
            'email'      => 'required|email|unique:users',
            'phone'      => 'required',
            'address'    => 'required',
            'department' => 'required',
        ]);

        $employee = new Employee();
        $employee->name           = $request->name;  
        $employee->email          = $request->email;  
        $employee->phone          = $request->phone;  
        $employee->address        = $request->address;  
        $employee->department_id  = $request->department;  
        $employee->save();
        $employee->achievement()->attach($request->achievement);
        $employee->save(); 
        
        return redirect()->route('employees')->with('message', 'Employee Created Successfully');
    }

    public function edit($id){
        $employee = Employee::with('department', 'achievement')->find($id);
        dd($employee);
    }

    public function destroy($id){
        Employee::destroy($id);
        return redirect()->route('employees');
    }
}
