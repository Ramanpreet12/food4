<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Http\Requests\CustomerRegisterRequest;
use App\Models\User;

class CustomerController extends Controller
{

    public function registrationStore(CustomerRegisterRequest $request)
    {
        $validated = $request->validated();

        // Handle image uploads
        try {
            $customerImgPath = store_image($request->file('image'), 'customer/images');
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to upload image:' . $e->getMessage()
            ], 401);
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
            return response()->json([
                'status' => true,
                'message' => 'Customer registration successful',
                'data' => [
                    'customer' => $customer,
                    'images' => [
                        'image' => asset("storage/customer/images/{$customerImgPath}"),
                    ]
                ]
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'something went wrong'
            ], 401);
        }
    }
    public function dashboard(Request $request)
    {
        return new UserResource($request->user());
    }
    public function profile(Request $request)
    {
        return 'customerprofile';
    }
}
