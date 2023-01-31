<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\PendingInvite;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
class CompanyController extends Controller
{
    public function create()
    {
        $back = request()->server('HTTP_REFERER');
        return view('company-create',compact('back'));
    }

    public function save(Request $request)
    {
        $company = new Company();
        $company->uuid = Str::random(24);
        $company->user_id = auth('web')->id();
        $company->company_name = $request->company;
        $company->company_url = $request->site_url;
        $company->widget_name = $request->widget_name;
        $company->save();
        User::find(auth('web')->id())->companies()->attach($company->id);
        return redirect()->route('dashboard',$company->uuid);
    }

    public function verify(Request $request)
    {
        $pending = PendingInvite::where('email',$request->email)->where('token',$request->token)->first();
        if(empty($pending)){
            return abort(404);
        }
        if(User::where('email',$pending->email)->count() > 0){
            $company = Company::where('uuid',$pending->company_id)->first();
            $user = User::where('email',$pending->email)->first();
            $user->role = $pending->role;
            $user->save();
            $user->companies()->attach($company->id);
            $pending->delete();
            event(new Registered($user));
            Auth::login($user);
            return redirect()->route('dashboard',$company->uuid);
        }
        return view('auth.register');
    }
}
