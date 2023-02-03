<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        
        if(auth('web')->check() && User::find(auth('web')->id())->companies->first() == ''){
            return redirect()->route('company.create');
        }

        if(auth('web')->check() && User::find(auth('web')->id())->companies()->where('uuid',request()->segment(2))->first() == ''){
            return abort(403);
        }
        
        return $next($request);
    }
}
