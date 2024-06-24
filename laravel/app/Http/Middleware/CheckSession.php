<?php

namespace App\Http\Middleware;

use Closure;
use Session;

use Illuminate\Support\Facades\Route;



class CheckSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    
    public function handle($request, Closure $next)
    {
        $data = session('Data');
        
        if (empty($data)) {
            return redirect('/logout');
        }
        $update = $request->session()->put('role', $data->role);
        return $next($request);
    }
}