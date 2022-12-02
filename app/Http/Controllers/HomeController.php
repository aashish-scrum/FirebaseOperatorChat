<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use App\Models\Visitor;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // dd(strtolower('Aashish').rand(0,100).now()->format('hisdmY'));
        return view('home');
    }
    
    public function chat()
    {
        // dd(User::withCount('messages','unread_messages')->where('id', '!=', auth('web')->id())->get()->toArray());
        return view('chat');
    }

    public function operator_status($operator_id,$status)
    {
        try {
            User::where('operator_id',$operator_id)->first()->update(['status'=>$status]);
            return response()->json(['status'=>'success','message'=>'status changed']);
        } catch (\Exception $e) {
            return response()->json(['status'=>'error','message'=>$e->getMessage()]);
        }
    }

    public function chat_end(Request $request)
    {
       try {
        $visitor = Visitor::where('visitor_id',$request->visitor_id)->select("id")->first();
        $operator = User::where('operator_id',$request->operator_id)->select("id","assigned_visitors")->first();
        $operator->assigned_visitors --;
        $operator->save();
        $message = Message::where('operator_id',$operator->id)->where('visitor_id',$visitor->id)->whereNull('messages')->latest()->first();
        // $message->operator_id = $operator->id;
        // $message->visitor_id = $visitor->id;
        $message->messages = json_encode($request->messages);
        $message->save();

        return response()->json(['status'=>'success']);
       } catch (\Throwable $th) {
        return response()->json(['status'=>'error']);
       }
    }
}
