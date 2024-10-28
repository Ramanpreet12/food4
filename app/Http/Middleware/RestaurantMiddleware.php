<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RestaurantMiddleware
{

    public function handle(Request $request, Closure $next)
    {
        if ($request->is('api/*')) {
            // API routes
            if (!Auth::guard('restaurant-api')->check()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized. Please login as restaurant personnel.',
                ], 401);
            }

            $user = Auth::guard('restaurant-api')->user();
            
            // Check both guard and token scope
            if ($user->role !== 'restaurant_owner' || !$request->user('restaurant-api')->tokenCan('restaurant-access')) {
                return response()->json([
                    'status' => false,
                    'message' => 'Access denied. Restaurant personnel only.',
                ], 403);
            }
        } else {
            // Web routes remain unchanged
            if (!Auth::guard('restaurant')->check()) {
                return redirect()->route('restaurant.login');
            }

            if (Auth::guard('restaurant')->user()->role !== 'restaurant_owner') {
                Auth::guard('restaurant')->logout();
                return redirect()->route('restaurant.login')
                    ->with('error', 'Unauthorized access attempt.');
            }
        }

        return $next($request);
    }


}
