<?php

namespace App\Http\Controllers;

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
        return view('settings');
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
