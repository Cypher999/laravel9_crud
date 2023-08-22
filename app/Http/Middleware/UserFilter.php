<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;

class UserFilter
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
        if($request->session()->get('id_user_laravel_crud')!=null){
          $id_user=$request->session()->get('id_user_laravel_crud');
          $cekUser=User::whereRaw('id_user = ?',[$id_user])->get();
          if($cekUser[0]['type']!='U'){
            return redirect('admin/');
          }
          else{
            return $next($request);
          }

        }
        else{
          return redirect('/');
        }

    }
}
