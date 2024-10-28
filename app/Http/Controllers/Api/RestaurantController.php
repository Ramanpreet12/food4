<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Models\Restaurant;
use App\Http\Requests\RestaurantRequest;
use Illuminate\Support\Facades\Hash;

class RestaurantController extends Controller
{


    public function restaurantLoginApi(Request $request)
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

        // Find restaurant with role check
        $restaurant = Restaurant::where('email', $request->email)
            ->where('role', 'restaurant_owner')
            ->first();

        if (!$restaurant) {
            return response()->json([
                'status' => false,
                'message' => 'No restaurant owner account found with this email'
            ], 404);
        }

        // Verify password
        if (!Hash::check($request->password, $restaurant->password)) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid credentials'
            ], 401);
        }

        // Revoke all existing tokens
        $restaurant->tokens()->delete();

        // Create token with restaurant-access scope
        // $token = $restaurant->createToken('RestaurantToken', ['restaurant-access'])->accessToken;
        // Create new token with restaurant-access scope
        $token = $restaurant->createToken('RestaurantToken', ['restaurant-access'])->accessToken;


        // Add token creation timestamp and expiration
        // $tokenMeta = [
        //     'access_token' => $token,
        //     'token_type' => 'Bearer',
        //     'created_at' => now()->toDateTimeString(),
        //     'expires_at' => now()->addDays(7)->toDateTimeString(), // Adjust expiration as needed
        // ];

        return response()->json([
            'status' => true,
            'message' => 'Restaurant login successful',
            // 'token' => $tokenMeta,
            'token' => $token,
            'user' => [
                'id' => $restaurant->id,
                'name' => $restaurant->name,
                'email' => $restaurant->email,
                'role' => $restaurant->role,
                'permissions' => ['restaurant-access']
            ]
        ], 200);
    }

    public function dashboard()
    {
        return 'hlo restraunt';
    }
    public function store(RestaurantRequest $request)
    {
        try {
            // Store images
            try {
                // $logoFilename = store_image($request->file('logo'), 'restaurants/logos');
                // $restaurantImage = store_image($request->file('restaurant_images'), 'restaurants/images');
                $featuredImage = store_image($request->file('featured_image'), 'restaurants/featured');
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Failed to upload image: ' . $e->getMessage());
            }


            $restaurant = Restaurant::create([
                'name' => $request->name,
                'description' => $request->description,
                'speciality' => $request->speciality,
                'address' => $request->address,
                'city' => $request->city,
                'state' => $request->state,
                'postal_code' => $request->postal_code,
                'country' => $request->country,
                'delivery_radius' => $request->delivery_radius,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'phone' => $request->phone,
                'secondary_phone' => $request->secondary_phone,
                'email' => $request->email,
                'website' => $request->website,
                'owner_name' => $request->owner_name,
                'owner_contact_number' => $request->owner_contact_number,
                'owner_email' => $request->owner_email,
                'opening_time' => $request->opening_time,
                'closing_time' => $request->closing_time,
                'days_of_operation' => $request->days_of_operation,
                'delivery_fee' => $request->delivery_fee,
                'delivery_time' => $request->delivery_time,
                'delivery_on_off' => $request->delivery_on_off,
                'average_cost_for_per_person' => $request->average_cost_for_per_person,
                'tax_gst_number' => $request->tax_gst_number,
                'fssai_number' => $request->fssai_number,
                // 'logo' => $logoFilename,
                // 'restaurant_images' => $restaurantImage,
                'featured_image' => $featuredImage,
            ]);

            // Check if request is API
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'status' => true,
                    'message' => 'Restaurant created successfully',
                    'data' => $restaurant,
                    'images' => [
                        // 'logo' => asset("storage/restaurants/logos/{$logoFilename}"),
                        // 'restaurant_image' => asset("storage/restaurants/images/{$restaurantImage}"),
                        'featured_image' => asset("storage/restaurants/featured/{$featuredImage}")
                    ]
                ], 201);
            }
        } catch (\Exception $e) {
            // Delete uploaded images if restaurant creation fails
            // delete_image($logoFilename, 'restaurants/logos');
            // delete_image($restaurantImage, 'restaurants/images');
            delete_image($featuredImage, 'restaurants/featured');

            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'status' => false,
                    'message' => 'Failed to create restaurant',
                    'error' => $e->getMessage()
                ], 500);
            }
        }
    }

    public function get()
    {
        try {
            // Fetch all restaurant records
            $restaurants = Restaurant::all();

            // Loop through each restaurant and append image URLs
            foreach ($restaurants as $restaurant) {
                // $restaurant->logo = asset("storage/restaurants/logos/{$restaurant->logo}");
                // $restaurant->restaurant_images = asset("storage/restaurants/images/{$restaurant->restaurant_images}");
                $restaurant->featured_image = asset("storage/restaurants/featured/{$restaurant->featured_image}");
            }

            return response()->json([
                'status' => true,
                'message' => 'Restaurants retrieved successfully',
                'data' => $restaurants,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to retrieve restaurants',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function edit($id)
    {

        try {
            $restaurant = Restaurant::find($id);
            if ($restaurant) {
                return response()->json([
                    'status' => true,
                    'message' => 'Restaurant get successfully',
                    'data' => $restaurant,
                    'images' => [
                        // 'logo' => asset("storage/restaurants/logos/{$restaurant->logo}"),
                        // 'restaurant_image' => asset("storage/restaurants/images/{$restaurant->restaurant_images}"),
                        'featured_image' => asset("storage/restaurants/featured/{$restaurant->featured_image}")
                    ]
                ], 201);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Failed to get restaurant',
                    'error' => 'Id not found'
                ], 500);
            }
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to get restaurants',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function update()
    {
        dd('update restraunt');
    }
}
