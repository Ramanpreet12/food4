<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\DeliveryRequest;
use Illuminate\Support\Facades\Hash;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\DeliveryBoy;

class DeliveryController extends Controller
{

    public function deliveryRegistrationApi(DeliveryRequest $request)
    {
        $validated = $request->validated();

        // Handle image uploads
        try {
            $deliveryBoyImagePath = store_image($request->file('image'), 'delivery_boys/images');

            // for now not sure to get number or photo
            // $adharPath = $request->file('adhar')->store('delivery_boys/documents', 'public');
            // $licensePath = $request->file('drivingLicence')->store('delivery_boys/documents', 'public');
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to upload image:' . $e->getMessage()
            ], 401);
        }


        // Create delivery boy
        $deliveryBoy = DeliveryBoy::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'],
            'altNumber' => $validated['altNumber'] ?? null,
            'gender' => $validated['gender'],
            'adhar' => $validated['adhar'],
            'image' => $deliveryBoyImagePath ?? null,
            'drivingLicence' =>  $validated['drivingLicence'],
            'deliveryBoyType' => $validated['deliveryBoyType'],
            'locationId' => $validated['locationId'] ?? null,
            'vehicle_type' => $validated['vehicle_type'] ?? null,
            'vehicle_number' => $validated['vehicle_number'] ?? null,
            'role' => 'delivery_boy'
        ]);

        if ($deliveryBoy) {
            // return redirect()->route('delivery.login')
            //     ->with('success', 'Registration successful! Please login to continue.');

            return response()->json([
                'status' => true,
                'message' => 'Delivery registration successful',
                // 'token' => $token,
                'data' => [
                    'deliveryBoy' => $deliveryBoy,
                    'images' => [
                        'image' => asset("storage/delivery_boys/images/{$deliveryBoyImagePath}"),
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



    public function deliveryLoginApi(Request $request)
{
    // Validate request data
    $validator = Validator::make($request->all(), [
        'email' => 'required|email',
        'password' => 'required'
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => false,
            'errors' => $validator->errors()
        ], 422);
    }

    // Find delivery boy with role check
    $delivery = DeliveryBoy::where('email', $request->email)
        ->where('role', 'delivery_boy')
        ->first();

    if (!$delivery) {
        return response()->json([
            'status' => false,
            'message' => 'No delivery boy account found with this email'
        ], 404);
    }

    // Verify password
    if (!Hash::check($request->password, $delivery->password)) {
        return response()->json([
            'status' => false,
            'message' => 'Invalid credentials'
        ], 401);
    }

    // Create token with delivery-access scope
    // $token = $delivery->createToken('DeliveryToken', ['delivery-access'])->accessToken;

     // Revoke all existing tokens
     $delivery->tokens()->delete();

     // Create new token with delivery-access scope
     $token = $delivery->createToken('DeliveryToken', ['delivery-access'])->accessToken;

    // Add token creation timestamp and expiration
    // $tokenMeta = [
    //     'access_token' => $token,
    //     'token_type' => 'Bearer',
    //     'created_at' => now()->toDateTimeString(),
    //     'expires_at' => now()->addDays(7)->toDateTimeString(), // Adjust expiration as needed
    // ];

    return response()->json([
        'status' => true,
        'message' => 'Delivery login successful',
        // 'token' => $tokenMeta,
        'token' => $token,
        'user' => [
            'id' => $delivery->id,
            'name' => $delivery->name,
            'email' => $delivery->email,
            'role' => $delivery->role,
            'permissions' => ['delivery-access']
        ]
    ], 200);
}
    
    public function dashboard()
    {
        return 'delivery boy';
    }
    public function profile()
    {
        return 'delivery boy profile';
    }
}
