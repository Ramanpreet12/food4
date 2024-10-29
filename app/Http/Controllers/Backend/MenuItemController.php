<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\MenuItemRequest;
use Illuminate\Http\Request;
use App\Models\MenuItem;
use App\Models\FoodCategory;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class MenuItemController extends Controller
{
    public function index()
    {
        try {
            // Get the authenticated restaurant
            $restaurant = Auth::guard('restaurant')->user();

            $menuItems = MenuItem::with('foodCategory')
                ->where('restaurant_id', $restaurant->id)
                ->latest()
                ->paginate(10);
            return view('restaurantOwner.menuItem.index', compact('menuItems'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to fetch menu items' . $e->getMessage());
        }
    }

    public function create()
    {
        //get the  login restaurant's food category
        // Get the authenticated restaurant
        $restaurant = Auth::guard('restaurant')->user();

        // Get only food categories belonging to the authenticated restaurant
        $food_categories = FoodCategory::where('restaurant_id', $restaurant->id)
            ->with('restaurant')
            ->get();
        return view('restaurantOwner.menuItem.create', compact('food_categories'));
    }

    public function store(MenuItemRequest $request)
    {

        try {
            $restaurant = Auth::guard('restaurant')->user();

            if (!$restaurant) {
                return redirect()->back()
                    ->with('error', 'Restaurant not found. Please login again.')
                    ->withInput();
            }
            $itemImg = null;
            if ($request->hasFile('image')) {
                try {
                    $itemImg = store_image($request->file('image'), 'menuItem/images');
                    // $validated['image'] = $itemImg;
                } catch (\Exception $e) {
                    return redirect()->back()
                        ->with('error', 'Failed to upload image: ' . $e->getMessage())
                        ->withInput();
                }
            }
            $menuItem = MenuItem::create([
                'name' => $request->name,
                'description' => $request->description,
                'category_id' => $request->category_id,
                'restaurant_id' => $restaurant->id,
                'price' => $request->price,
                'image' => $itemImg,
                'status' => 'active'
            ]);

            return redirect()->route('restaurant.menu-items.index')->with('success', 'Food Item created successfully');
        } catch (\Exception $e) {
            // If image was uploaded but record creation failed, remove the image
            if ($itemImg) {
                delete_image($itemImg, 'menuItem/images');
            }

            return redirect()->back()
                ->with('error', 'Error creating food category: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function edit(string $id)
    {
        try {
            $restaurant = Auth::guard('restaurant')->user();

            $menu_item = MenuItem::where('restaurant_id', $restaurant->id)
                ->findOrFail($id);

            // Get only food categories belonging to the authenticated restaurant
            $food_categories = FoodCategory::where('restaurant_id', $restaurant->id)
                ->with('restaurant')
                ->get();

            return view('restaurantOwner.menuItem.edit', compact('menu_item', 'food_categories'));
        } catch (\Exception $e) {
            return redirect()->route('restaurant.menu-items.index')
                ->with('error', 'Menu Item  not found');
        }
    }


    public function show(MenuItem $menuItem)
    {
        // try {
        //     if ($menuItem->restaurant_id !== auth()->user()->restaurant_id) {
        //         return response()->json([
        //             'status' => 'error',
        //             'message' => 'Unauthorized access'
        //         ], 403);
        //     }

        //     return response()->json([
        //         'status' => 'success',
        //         'data' => $menuItem->load('foodCategory')
        //     ]);
        // } catch (\Exception $e) {
        //     return response()->json([
        //         'status' => 'error',
        //         'message' => 'Failed to fetch menu item',
        //         'error' => $e->getMessage()
        //     ], 500);
        // }
    }

    public function update(MenuItemRequest $request, MenuItem $menuItem)
    {
        try {
            $data = $request->validated();
            $restaurant = Auth::guard('restaurant')->user();
            if ($request->hasFile('image')) {
                try {
                    // Delete old image
                    delete_image($menuItem->featured_image, 'menuItem/images');
                    // Store new image
                    $data['image'] = store_image($request->file('image'), 'menuItem/images');
                } catch (\Exception $e) {
                    return redirect()->back()->with('error', 'Failed to upload item image: ' . $e->getMessage());
                }
            } else {
                // Keep existing image
                unset($data['image']);
            }

            // Update restaurant
            $menuItem->update($data);
            return redirect()->route('restaurant.menu-items.index')->with('success', 'Item updated successfully');
        } catch (\Exception $e) {
            if (isset($data['image']) ) {
                delete_image($data['image'], 'menuItem/images');
            }

            return redirect()->back()->withInput() ->with('error', 'Failed to update item: ' . $e->getMessage());
        }


    }

    public function destroy(string $id)
    {
        try {
            // Attempt to find the FoodCategory by ID
            $menu_item = MenuItem::findOrFail($id);

            // Check if there is a image and delete it
            if ($menu_item->image) {
                delete_image($menu_item->image, 'menuItem/images');
            }

            // Soft delete the menu_item record
            $menu_item->delete();

            // Redirect back with a success message
            return redirect()->back()->with('success', 'Menu item deleted successfully.');
        } catch (ModelNotFoundException $e) {
            return redirect()->back()->withInput()->with('error', 'Menu item not found.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Failed to delete Menu item: ' . $e->getMessage());
        }
    }

}
