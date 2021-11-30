<?php

// Store all the permission details to seperate table permissions or array ....

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class AuthGates
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
       // $user = Auth::user();

        Gate::define('user_access', function(User $user){
             return $user->role_id==1;
            });

        Gate::define('user_edit', function(User $user){
            return $user->role_id==1;
            });
        Gate::define('user_create', function(User $user){
            return $user->role_id==1;
            });
        Gate::define('user_delete', function(User $user){
            return $user->role_id==1;
            });
        Gate::define('question_access', function(User $user){
            return $user->role_id==1;
            });
        Gate::define('question_create', function(User $user){
            return $user->role_id==1;
            });
        Gate::define('question_show', function(User $user){
            return $user->role_id==1;
            });
        Gate::define('question_edit', function(User $user){
            return $user->role_id==1;
            });
        Gate::define('question_delete', function(User $user){
            return $user->role_id==1;
            });
        Gate::define('category_access', function(User $user){
            return $user->role_id==1;
            });
        Gate::define('category_create', function(User $user){
            return $user->role_id==1;
            });
        Gate::define('category_edit', function(User $user){
            return $user->role_id==1;
            });
        Gate::define('category_show', function(User $user){
            return $user->role_id==1;
            });
        Gate::define('category_delete', function(User $user){
            return $user->role_id==1;
            });
        Gate::define('option_access', function(User $user){
            return $user->role_id==1;
            });
        Gate::define('option_create', function(User $user){
            return $user->role_id==1;
            });
        Gate::define('option_edit', function(User $user){
            return $user->role_id==1;
            });
        Gate::define('option_show', function(User $user){
            return $user->role_id==1;
            });
        Gate::define('option_delete', function(User $user){
            return $user->role_id==1;
            });
        Gate::define('option_show', function(User $user){
            return $user->role_id==1;
            });
        Gate::define('users_result_show', function(User $user){
            return $user->role_id==1;
            });

        return $next($request);
    }
}

