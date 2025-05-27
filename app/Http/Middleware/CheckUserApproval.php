<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckUserApproval
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next)
    {
        if (auth()->check()) {
            if (auth()->user()->approve_user == 0) {
                auth()->logout();
                return redirect()->route('approval.wait')->with('error', 'Your registration under review, please contact us.');
            }
        }
    
        return $next($request);
    }
    
}
