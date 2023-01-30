<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function create()
    {
        return view('department-create');
    }

    public function invite_members(Request $request)
    {
        dd($request->all());
        foreach ($request->email as $key => $email) {
            
        }
    }
}
