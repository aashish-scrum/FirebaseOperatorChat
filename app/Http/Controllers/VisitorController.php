<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Visitor;
use Illuminate\Http\Request;
use PHPUnit\Framework\Constraint\Operator;

class VisitorController extends Controller
{
    public function visitor_register(Request $request)
    {
        $newArray = [
            'operator' => null,
            'visitor' => '',
        ];
        // ->orWhere('ip',$request->ip())
        $visitor = Visitor::where('email',$request->email)->latest()->first();
        if($visitor == ''){
            $visitor = Visitor::create([
                'visitor_id' => str_replace(' ','_',strtolower($request->username)).rand(10000,99999).now()->timestamp,
                'ip' => $request->ip(),
                'name' => $request->username,
                'email' => $request->email,
                'phone' => $request->phone,
                'status' => 1,
            ]);
        }
        $operator = User::where('status',1)->orderBy('assigned_visitors')->first();
        
        $newArray['visitor'] = $visitor->visitor_id;

        if ($operator != '') {
            $newArray['operator'] = $operator->operator_id;
            $operator->assigned_visitors ++;
            $operator->save();
        }
        return response()->json($newArray,200);
    }

    public function visitors()
    {
        return Visitor::orderBy('status','DESC')->get();
    }

    public function visitor_logout($operator,$visitor)
    {
        $op = User::where('operator_id',$operator)->first();
        $op->assigned_visitors --;
        $op->save();
    }
}
