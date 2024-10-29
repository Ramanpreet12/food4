<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FoodCategory;
use App\Models\Restaurant;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class FoodCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get the authenticated restaurant
        $restaurant = Auth::guard('restaurant')->user();

        // Get only food categories belonging to the authenticated restaurant
        $food_categories = FoodCategory::where('restaurant_id', $restaurant->id)
            ->with('restaurant')
            ->get();

        return view('restaurantOwner.foodCategory.index', compact('food_categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('restaurantOwner.foodCategory.create');
    }



    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'status' => 'boolean',
        ]);

        try {
            $restaurant = Auth::guard('restaurant')->user();

            if (!$restaurant) {
                return redirect()->back()
                    ->with('error', 'Restaurant not found. Please login again.')
                    ->withInput();
            }
            $foodCatImg = null;
            if ($request->hasFile('image')) {
                try {
                    $foodCatImg = store_image($request->file('image'), 'foodCategory/images');
                    $validated['image'] = $foodCatImg;
                } catch (\Exception $e) {
                    return redirect()->back()
                        ->with('error', 'Failed to upload image: ' . $e->getMessage())
                        ->withInput();
                }
            }
            $validated['restaurant_id'] = $restaurant->id;
            $validated['status'] = 1;
            FoodCategory::create($validated);
            return redirect()->back()
                ->with('success', 'Food category created successfully');
        } catch (\Exception $e) {
            // If image was uploaded but record creation failed, remove the image
            if ($foodCatImg) {
                delete_image($foodCatImg, 'foodCategory/images');
            }

            return redirect()->back()
                ->with('error', 'Error creating food category: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $restaurant = Auth::guard('restaurant')->user();

            $category = FoodCategory::where('restaurant_id', $restaurant->id)
                ->findOrFail($id);

            return view('restaurantOwner.foodCategory.edit', compact('category'));
        } catch (\Exception $e) {
            return redirect()->route('restaurant.food-categories.index')
                ->with('error', 'Food category not found');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // 1. Validate the request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif', // 2MB Max
            'status' => 'boolean',
        ]);

        try {
            // 2. Get the authenticated restaurant
            $restaurant = Auth::guard('restaurant')->user();

            // 3. Find the category and ensure it belongs to the restaurant
            $category = FoodCategory::where('restaurant_id', $restaurant->id)
                ->findOrFail($id);

            // 4. Handle image upload if present
            if ($request->hasFile('image')) {
                try {
                    // Delete old image if exists
                    if ($category->image) {
                        delete_image($category->image, 'foodCategory/images');
                    }

                    // Store new image
                    $foodCatImg = store_image($request->file('image'), 'foodCategory/images');
                    $validated['image'] = $foodCatImg;
                } catch (\Exception $e) {
                    return redirect()->back()
                        ->with('error', 'Failed to upload image: ' . $e->getMessage())
                        ->withInput();
                }
            }

            // 5. Update the category
            $category->update($validated);

            // 6. Redirect with success message
            return redirect()->route('restaurant.food-categories.index')
                ->with('success', 'Food category updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error updating food category: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            // Attempt to find the FoodCategory by ID
            $category = FoodCategory::findOrFail($id);

            // Check if there is a image and delete it
            if ($category->image) {
                delete_image($category->image, 'foodCategory/images');
            }

            // Soft delete the category record
            $category->delete();

            // Redirect back with a success message
            return redirect()->back()->with('success', 'Food category deleted successfully.');
        } catch (ModelNotFoundException $e) {
            return redirect()->back()->withInput()->with('error', 'Food category not found.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Failed to delete Food category: ' . $e->getMessage());
        }
    }
}
