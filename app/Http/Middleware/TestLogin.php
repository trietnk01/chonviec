<?php

namespace App\Http\Middleware;

use Closure;
use Sentinel;
class TestLogin
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
        if(Sentinel::check()){
            $user=Sentinel::getUser();                                
            $status=$user->status;    
            if((int)$status==1){
                return $next($request);
            }else{
               return redirect()->route('adminsystem.login');     
            }            
        }
        else{
            return redirect()->route('adminsystem.login');    
        }
    }
}
