<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class DeliveryBoyMiddleware
{
    
    public function handle(Request $request, Closure $next)
    {
        if ($request->is('api/*')) {
            // API routes
            if (!Auth::guard('delivery-api')->check()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized. Please login as delivery personnel.',
                ], 401);
            }

            $user = Auth::guard('delivery-api')->user();
            
            // Check both guard and token scope
            if ($user->role !== 'delivery_boy' || !$request->user('delivery-api')->tokenCan('delivery-access')) {
                return response()->json([
                    'status' => false,
                    'message' => 'Access denied. Delivery personnel only.',
                ], 403);
            }
        } else {
            // Web routes remain unchanged
            if (!Auth::guard('delivery')->check()) {
                return redirect()->route('delivery.login');
            }

            if (Auth::guard('delivery')->user()->role !== 'delivery_boy') {
                Auth::guard('delivery')->logout();
                return redirect()->route('delivery.login')
                    ->with('error', 'Unauthorized access attempt.');
            }
        }

        return $next($request);
    }
}
