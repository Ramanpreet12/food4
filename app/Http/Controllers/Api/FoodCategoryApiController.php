<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FoodCategory;
use App\Models\Restaurant;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use PhpParser\Node\Stmt\TryCatch;

class FoodCategoryApiController extends Controller
{
    public function getFoodCategories()
    {
        try {
            $restaurant = Auth::guard('restaurant-api')->user();
            // Get only food categories belonging to the authenticated restaurant
            if ($restaurant) {
                $food_categories = FoodCategory::where('restaurant_id', $restaurant->id)
                    ->with('restaurant')
                    ->get();
                // Loop through each food_categories and append image URLs
                foreach ($food_categories as $category) {
                    $category->image = asset("storage/foodCategory/images/{$category->image}");
                }

                return response()->json([
                    'status' => true,
                    'message' => 'Food categories  retrieved successfully',
                    'data' => $food_categories,
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to retrieve food categories',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function storeFoodCategory(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'status' => 'boolean',
        ]);

        try {
            $restaurant = Auth::guard('restaurant-api')->user();

            if (!$restaurant) {
                return response()->json([
                    'status' => false,
                    'message' => 'Restaurant not found. Please login again.',
                ], 500);
            }
            $foodCatImg = null;
            if ($request->hasFile('image')) {
                try {
                    $foodCatImg = store_image($request->file('image'), 'foodCategory/images');
                    $validated['image'] = $foodCatImg;
                } catch (\Exception $e) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Failed to upload image: ' . $e->getMessage(),
                        // 'error' => $e->getMessage(),
                    ], 500);
                }
            }
            $validated['restaurant_id'] = $restaurant->id;
            $validated['status'] = 1;
            $food_category = FoodCategory::create($validated);
            return response()->json([
                'status' => true,
                'message' => 'Food category created successfully',
                'data' => $food_category,
                'images' => [
                    'image' => asset("storage/foodCategory/images/{$foodCatImg}")
                ]
            ], 200);
        } catch (\Exception $e) {
            // If image was uploaded but record creation failed, remove the image
            return response()->json([
                'status' => false,
                'message' => 'Error creating food category:  ' . $e->getMessage(),
                // 'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function editFoodCategory($id)
    {
        try {
            $restaurant = Auth::guard('restaurant-api')->user();

            $food_category = FoodCategory::where('restaurant_id', $restaurant->id)
                ->findOrFail($id);

            if ($food_category) {
                return response()->json([
                    'status' => true,
                    'message' => 'Food category get successfully',
                    'data' => $food_category,
                    'images' => [
                        'image' => asset("storage/foodCategory/images/{$food_category->image}")
                    ]
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Food category not found, ',
                ], 500);
            }

            return view('restaurantOwner.foodCategory.edit', compact('category'));
        } catch (\Exception $e) {

            return response()->json([
                'status' => false,
                'message' => 'Something went wrong ' . $e->getMessage(),
                // 'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function updateFoodCategory(Request $request, $id)
    {

        try {

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif',
                'status' => 'boolean',
            ]);
            try {
                $restaurant = Auth::guard('restaurant-api')->user();

                if (!$restaurant) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Restaurant not found. Please login again.',
                    ], 500);
                }

                // Find the food category using URL parameter
                $foodCategory = FoodCategory::where('id', $id)
                    ->where('restaurant_id', $restaurant->id)
                    ->first();

                if (!$foodCategory) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Food category not found.',
                    ], 404);
                }
                // Handle image upload
                if ($request->hasFile('image')) {
                    try {
                        // Delete old image if exists
                        if ($foodCategory->image) {
                            // Assuming you have a delete_image helper function
                            // delete_image('foodCategory/images/' . $foodCategory->image);
                            Storage::delete('public/foodCategory/images/' . $foodCategory->image);
                        }

                        // Store new image
                        $foodCatImg = store_image($request->file('image'), 'foodCategory/images');
                        $validated['image'] = $foodCatImg;
                    } catch (\Exception $e) {
                        return response()->json([
                            'status' => false,
                            'message' => 'Failed to upload image: ' . $e->getMessage(),
                        ], 500);
                    }
                }

                // Update the food category
                $foodCategory->update($validated);

                return response()->json([
                    'status' => true,
                    'message' => 'Food category updated successfully',
                    'data' => $foodCategory,
                    'images' => [
                        'image' => $foodCategory->image ? asset("storage/foodCategory/images/{$foodCategory->image}") : null
                    ]
                ], 200);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => false,
                    'message' => 'Error updating food category: ' . $e->getMessage(),
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error updating food category: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function deleteFoodCategory($id)
    {
        try {
            // Attempt to find the FoodCategory by ID
            $foodCategory = FoodCategory::findOrFail($id);

            // Check if there is a image and delete it
            if ($foodCategory->image) {
                delete_image($foodCategory->image, 'foodCategory/images');
            }

            // Soft delete the category record
            $foodCategory->delete();

            return response()->json([
                'status' => true,
                'message' => 'Food category deleted successfully',
                'data' => $foodCategory,

            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Food category not found. ' . $e->getMessage(),
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error in deleting food category: ' . $e->getMessage(),
            ], 500);
        }
    }
}
