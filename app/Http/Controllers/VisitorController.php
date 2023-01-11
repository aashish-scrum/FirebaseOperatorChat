<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use App\Models\Visitor;
use Illuminate\Http\Request;
use PHPUnit\Framework\Constraint\Operator;

class VisitorController extends Controller
{
    public function visitor_register(Request $request)
    {
        return $request->ip();
        // dd($request->all());

        $visitor = Visitor::create([
            'visitor_id' => 'V'.rand(100000000000000,999999999999999),
            'ip' => $request->ip(),
            'status' => 1,
        ]);

        // $operator = User::where('status',1)->orderBy('assigned_visitors')->first();
        
        $newArray['visitor'] = $visitor->visitor_id;

        return response()->json($newArray,200);
    }

    public function visitors()
    {
        return Visitor::whereHas('messages',function($query){$query->where('operator_id',auth()->id());})->orderBy('status','DESC')->get();
    }

    public function visitor_logout($operator,$visitor)
    {
        $op = User::where('operator_id',$operator)->first();
        $op->assigned_visitors --;
        $op->save();
    }
}
