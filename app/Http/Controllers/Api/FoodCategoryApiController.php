<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FoodCategory;
use App\Models\Restaurant;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class FoodCategoryApiController extends Controller
{
    public function getFoodCategories()
    {
        try {
            $restaurant = Auth::guard('restaurant-api')->user();

            if (!$restaurant) {
                return api_unauthorized('Restaurant not found. Please login again.');
            }

            $food_categories = FoodCategory::where('restaurant_id', $restaurant->id)
                ->with('restaurant')
                ->get();

            foreach ($food_categories as $category) {
                $category->image = asset("storage/foodCategory/images/{$category->image}");
            }

            return api_success('Food categories retrieved successfully', $food_categories);
        } catch (\Exception $e) {
            return api_exception($e, 'Failed to retrieve food categories');
        }
    }

    public function storeFoodCategory(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif',
                'status' => 'boolean',
            ]);

            $restaurant = Auth::guard('restaurant-api')->user();

            if (!$restaurant) {
                return api_unauthorized('Restaurant not found. Please login again.');
            }

            $foodCatImg = null;
            if ($request->hasFile('image')) {
                $foodCatImg = store_image($request->file('image'), 'foodCategory/images');
                $validated['image'] = $foodCatImg;
            }

            $validated['restaurant_id'] = $restaurant->id;
            $validated['status'] = 1;

            $food_category = FoodCategory::create($validated);

            return api_success(
                'Food category created successfully',
                $food_category,
                ['images' => ['image' => asset("storage/foodCategory/images/{$foodCatImg}")]]
            );
        } catch (\Exception $e) {
            return api_exception($e, 'Error creating food category');
        }
    }

    public function editFoodCategory($id)
    {
        try {
            $restaurant = Auth::guard('restaurant-api')->user();

            $food_category = FoodCategory::where('restaurant_id', $restaurant->id)
                ->findOrFail($id);

            if ($food_category) {
                return api_success(
                    'Food category retrieved successfully',
                    $food_category,
                    ['images' => ['image' => $food_category->image ? asset("storage/foodCategory/images/{$food_category->image}") : null]]
                );


            } else {
                return api_not_found('Food category not found.');
            }

            return view('restaurantOwner.foodCategory.edit', compact('category'));
        } catch (\Exception $e) {
            return api_exception($e, 'Failed to retrieve food categories');

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

            $restaurant = Auth::guard('restaurant-api')->user();

            if (!$restaurant) {
                return api_unauthorized('Restaurant not found. Please login again.');
            }

            $foodCategory = FoodCategory::where('id', $id)
                ->where('restaurant_id', $restaurant->id)
                ->first();

            if (!$foodCategory) {
                return api_not_found('Food category not found.');
            }

            if ($request->hasFile('image')) {
                if ($foodCategory->image) {
                    Storage::delete('public/foodCategory/images/' . $foodCategory->image);
                }
                $validated['image'] = store_image($request->file('image'), 'foodCategory/images');
            }

            $foodCategory->update($validated);

            return api_success(
                'Food category updated successfully',
                $foodCategory,
                ['images' => ['image' => $foodCategory->image ? asset("storage/foodCategory/images/{$foodCategory->image}") : null]]
            );
        } catch (\Exception $e) {
            return api_exception($e, 'Error updating food category');
        }
    }

    public function deleteFoodCategory($id)
    {
        try {
            $foodCategory = FoodCategory::findOrFail($id);

            if ($foodCategory->image) {
                delete_image($foodCategory->image, 'foodCategory/images');
            }

            $foodCategory->delete();

            return api_success('Food category deleted successfully', $foodCategory);
        } catch (ModelNotFoundException $e) {
            return api_not_found('Food category not found');
        } catch (\Exception $e) {
            return api_exception($e, 'Error deleting food category');
        }
    }
}
