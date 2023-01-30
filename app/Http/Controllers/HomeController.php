<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\User;
use App\Models\Visitor;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    public function index()
    {
        return view('dashboard');
    }

    public function chat()
    {
        return view('chat');
    }

    public function settings()
    {
        $companies = auth('web')->user()->adminCompanies()->where('uuid',$this->companyUuid)->select('user_id','id')->get()->pluck('id')->toArray();
        $members = User::whereHas('companies',function($query)use($companies){
            $query->whereIn('company_id',$companies);
        })->get();
        return view('settings',compact('members'));
    }

    public function operator_status($operator_id, $status)
    {
        try {
            User::where('operator_id', $operator_id)->first()->update(['status' => $status]);
            return response()->json(['status' => 'success', 'message' => 'status changed']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function chat_end(Request $request)
    {
        try {
            $message = new Visitor();
            $message->visitor_id = $request->visitor_id;
            $message->operator_id = $request->operator_id;
            $message->status = 1;
            $message->messages = json_encode($request->messages);
            $message->save();

            return response()->json(['status' => 'success']);
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error']);
        }
    }
}
