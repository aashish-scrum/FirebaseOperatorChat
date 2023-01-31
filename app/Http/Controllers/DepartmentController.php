<?php

namespace App\Http\Controllers;

use App\Jobs\InviteJob;
use App\Models\Company;
use App\Models\Department;
use App\Models\PendingInvite;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DepartmentController extends Controller
{
    public function index()
    {
        $companies = User::find(auth('web')->id())->adminCompanies()->where('uuid',$this->companyUuid)->select('user_id','id')->get()->pluck('id')->toArray();
        $members = User::whereHas('companies',function($query)use($companies){
            $query->whereIn('company_id',$companies);
        })->get();
        return view('department-create',compact('members'));
    }

    public function members_create()
    {
        return view('member-create');
    }

    public function invite_members(Request $request)
    {
        try {
            foreach ($request->email as $key => $email) {
                $invite = new PendingInvite();
                $invite->email = $email;
                $invite->token = Str::random(25);
                $invite->role = $request->role[$key];
                $invite->company_id = $this->companyUuid;
                $invite->save();
                $invite->refresh();
                InviteJob::dispatch($invite);
            }
            $request->session()->flash('success','Members Invited Successfully!');
        } catch (\Exception $e) {
            $request->session()->flash('error',$e->getMessage());
        }
        return redirect()->route('settings',$this->companyUuid);
    }

    public function create(Request $request)
    {
        if($request->has('id')){
            $department = Department::find($request->id);
            $message = 'Department Saved Successfully!';
        }else{
            $department = new Department();
            $message = 'Department Created Successfully!';
        }
        $department->company_id = Company::where('uuid',$this->companyUuid)->select('id')->first()->id;
        $department->department_name = $request->department_name;
        $department->description = $request->description;
        $department->save();
        $department->users()->sync($request->members);
        $request->session()->flash('success',$message);
        return redirect()->route('settings',$this->companyUuid);
    }

    public function destroy($company,$id)
    {
        Department::find($id)->delete();
        request()->session()->flash('success','Department Deleted Successfully!');
        return redirect()->route('settings',$this->companyUuid);
    }

    public function edit($company,$id)
    {
        $edit = Department::with('users')->find($id);
        $companies = User::find(auth('web')->id())->adminCompanies()->where('uuid',$this->companyUuid)->select('user_id','id')->get()->pluck('id')->toArray();
        $members = User::whereHas('companies',function($query)use($companies){
            $query->whereIn('company_id',$companies);
        })->get();
        return view('department-create',compact('members','edit'));
    }
}
