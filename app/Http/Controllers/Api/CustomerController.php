<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Http\Requests\CustomerRegisterRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{

    public function registrationStore(CustomerRegisterRequest $request)
    {
        $validated = $request->validated();

        // Handle image uploads
        try {
            $customerImgPath = store_image($request->file('image'), 'customer/images');
        } catch (\Exception $e) {
            return api_exception($e, 'Failed to upload image:');
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
            return api_success('Customer registration successful', $customer,
                ['images' => ['image' =>  $customer->image ? asset("storage/customer/images/{$customerImgPath}") : null]]
            );

        } else {
            return api_error('something went wrong');
        }
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
            return api_success('OTP sent successfully', $otp  );


        } catch (\Exception $e) {
            return api_exception($e, 'Error sending OTP');
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
                return api_unauthorized('Invalid OTP or OTP expired');
            }

            // Mark OTP as verified and clear OTP data
            $user->update([
                'is_otp_verified' => true,
                'otp' => null,
                'otp_expire_at' => null
            ]);

            // Login user
            Auth::login($user);
             // Revoke all existing tokens
     $user->tokens()->delete();

     // Create new token with customer-access scope
     $token = $user->createToken('CustomerToken', ['customer-access'])->accessToken;

     return response()->json([
        'status' => true,
        'message' => 'customer login successful',
        // 'token' => $tokenMeta,
        'token' => $token,
        'user' => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'permissions' => ['customer-access']
        ]
    ], 200);
            // return api_success('Login successful'  );

        } catch (\Exception $e) {
            return api_exception($e, 'Error verifying OTP');
        }
    }


    public function dashboard(Request $request)
    {
        return 'kdfkdlfkldkfld';
        // return new UserResource($request->user());
    }
    public function profile(Request $request)
    {
        return 'customerprofile';
    }
}
