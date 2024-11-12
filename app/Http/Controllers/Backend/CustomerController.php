<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\CustomerRegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{

    public function showRegistrationForm(){
        return view('customer.registration');
    }
    public function user_register(CustomerRegisterRequest $request){
        if ($request->isMethod('post')) {
            $validated = $request->validated();
            // Handle image uploads
            try {
                $customerImgPath = store_image($request->file('image'), 'customer/images');
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Failed to upload image: ' . $e->getMessage());
            }
            $customer = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'altNumber' => $validated['altNumber'] ?? null,
                'gender' => $validated['gender'],
                'image' => $customerImgPath ?? null,
                'role' => 'customer'
            ]);

            if ($customer) {
                return redirect()->route('user.login')
                    ->with('success', 'User Registration successful! Please login to continue.');
            }

            return back()->with('error', 'Something went wrong! Please try again.');
        }
    }
    public function showLoginForm(){
        return view('customer.login');
    }

    public function sendOTP(Request $request)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'phone' => 'required|exists:users,phone'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Find user by phone
            $user = User::where('phone', $request->phone)->first();

            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'Phone number not registered'
                ], 404);
            }

            // Generate OTP
            $otp = rand(100000, 999999);

            // Store OTP in database
            $user->update([
                'otp' => $otp,
                'otp_expire_at' => Carbon::now()->addMinutes(5),
                'is_otp_verified' => false
            ]);

            // Here you would integrate with your SMS service
            // For example:
            // $this->smsService->send($user->phone, "Your OTP is: " . $otp);

            return response()->json([
                'status' => true,
                'message' => 'OTP sent successfully',
                'otp' => $otp // Remove in production
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error sending OTP',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function verifyOTPAndLogin(Request $request)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'phone' => 'required|exists:users,phone',
            'otp' => 'required|numeric|digits:6'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = User::where('phone', $request->phone)
                       ->where('otp', $request->otp)
                       ->where('otp_expire_at', '>', Carbon::now())
                       ->first();

            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid OTP or OTP expired'
                ], 401);
            }

            // Mark OTP as verified and clear OTP data
            $user->update([
                'is_otp_verified' => true,
                'otp' => null,
                'otp_expire_at' => null
            ]);

            // Login user
            Auth::login($user);

            // Return success response with redirect URL
            return response()->json([
                'status' => true,
                'message' => 'Login successful',
                'redirect' => route('user.dashboard') // Change this to your dashboard route
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error verifying OTP',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function login(){
        return 'login';
    }
    public function dashboard(){
        return 'customer';
    }
}
