<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Department;
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
        $companies = User::find(auth('web')->id())->adminCompanies()->where('uuid',$this->companyUuid)->select('user_id','id')->get()->pluck('id')->toArray();
        $departments = Department::whereIn('company_id',$companies)->withCount('users')->get();
        return view('chat', compact('departments'));
    }

    public function settings()
    {
        $companies = User::find(auth('web')->id())->adminCompanies()->where('uuid',$this->companyUuid)->select('user_id','id')->get()->pluck('id')->toArray();
        
        $members = User::whereHas('companies',function($query)use($companies){
            $query->whereIn('company_id',$companies);
        })->with('companies:user_id,uuid,id')->get();

        $departments = Department::whereIn('company_id',$companies)->withCount('users')->get();

        return view('settings',compact('members','departments'));
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
            $message->messages = $request->messages;
            $message->save();

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error']);
        }
    }

    public function chat_transfer(Request $request)
    {
        try {
            // $this->chat_end($request);
            // $user = User::whereHas('departments',function($query)use($request){
            //     $query->where('department_id',$request->depart);
            // })->where('id','!=',auth('web')->id())->inRandomOrder()->first();
            $user = user::find(3);
            return response()->json(['status' => 'success','message'=>'Chat Transfer','data'=>$user]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error','message'=>$e->getMessage()]);
        }
    }
}
