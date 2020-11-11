<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VoterMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if(!auth()->guard('mahasiswa')->check()){
            return redirect(route('mahasiswa.login'));
        }
        return $next($request);
    }
}
