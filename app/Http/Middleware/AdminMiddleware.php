<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if (Session::has('admin')){
            return redirect('/admin_panel');
        }else{
            return  redirect()->route('admin_layout')->withErrors(['error'=> 'нет Доступа']);
        }
    }


        // Check if the user is authenticated and has the 'admin' role (you can adjust this condition based on your role system)
        //if (auth()->check() && auth()->user()->hasRole('admin')) {
          //  return redirect('/admin_panel');
        //}


        //return $next($request);
        //if (Auth::check()) {
         //   $user = Auth::user();
          //  if ($user->hasRole('admin')) {
           //     return redirect()->route('/admin_panel'); // Перенаправление админа на админ-панель
            //} else {
             //   return redirect()->route('user.home'); // Перенаправление обычных пользователей на их домашнюю страницу
        //    }
       // }

        //return $next($request);
    //}
}
