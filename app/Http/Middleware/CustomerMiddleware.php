<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;


class CustomerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */

    // public function handle(Request $request, Closure $next): Response
    // {
    //     // Check if user is logged in and using web guard
    //     if (!Auth::guard('web')->check()) {
    //         if ($request->expectsJson() || $request->is('api/*')) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Unauthorized. Customer access only.',
    //             ], 403);
    //         }
    //         return redirect()->route('login');
    //     }

    //     // Ensure user is actually a customer
    //     if (Auth::guard('web')->user()->role !== 'customer') {
    //         Auth::guard('web')->logout();
    //         if ($request->expectsJson() || $request->is('api/*')) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Unauthorized. Customer access only.',
    //             ], 403);
    //         }
    //         return redirect()->route('login')
    //             ->with('error', 'Unauthorized access attempt.');
    //     }

    //     return $next($request);
    // }

    public function handle(Request $request, Closure $next)
    {
        if ($request->is('api/*')) {
            // API routes
            if (!Auth::guard('api')->check()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized. Please login as customer.',
                ], 401);
            }

            $user = Auth::guard('api')->user();

            // Check both guard and token scope
            if ($user->role !== 'customer' || !$request->user('api')->tokenCan('customer-access')) {
                return response()->json([
                    'status' => false,
                    'message' => 'Access denied. customer personnel only.',
                ], 403);
            }
        } else {
            // Web routes remain unchanged
            if (!Auth::guard('web')->check()) {
                return redirect()->route('user.login');
            }

            if (Auth::guard('web')->user()->role !== 'cutomer') {
                Auth::guard('web')->logout();
                return redirect()->route('user.login')
                    ->with('error', 'Unauthorized access attempt.');
            }
        }

        return $next($request);
    }
}
