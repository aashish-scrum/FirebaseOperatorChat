<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\PendingInvite;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request,$token = '')
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
        $insert = [
            'operator_id' => str_replace(' ','_',strtolower($request->name)).rand(10000,99999).now()->timestamp,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ];
        if($request->token){
            $pending = PendingInvite::where('email',$request->email)->where('token',$request->token)->first();
            if(empty($pending)){
                return abort(404);
            }
            $insert['email'] = $pending->email;
            $insert['role'] = $pending->role;
            $company = Company::where('uuid',$pending->company_id)->first();
            $pending->delete();
        }
        $user = User::create($insert);
        if($request->token){
            $user->companies()->attach($company->id);
        }
        event(new Registered($user));

        Auth::login($user);
        if($request->token){
            return redirect()->route('dashboard',$company->uuid);
        }
        return redirect(RouteServiceProvider::HOME);
    }
}
