<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VisitorController extends Controller
{
    public function visitor_register(Request $request)
    {
        return response()->json($request->all(),200);
    }
}
