<?php

namespace App\Http\Middleware;

use App\Models\Company;
use Closure;
use Illuminate\Http\Request;

class CompanyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if(auth('web')->check() && Company::where('user_id',auth('web')->id())->count() == 0){
            return redirect()->route('company.create');
        }

        if(auth('web')->check() && (Company::where('uuid',request()->segment(2))->first() == '' || Company::where('uuid',request()->segment(2))->first()->user_id != auth('web')->id())){
            return abort(403);
        }
        
        return $next($request);
    }
}
