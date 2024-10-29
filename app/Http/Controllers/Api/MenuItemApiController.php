<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FoodCategory;
use App\Models\Restaurant;
use App\Models\MenuItem;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class MenuItemApiController extends Controller
{

    public function getMenuItems()
    {
        try {
            $restaurant = Auth::guard('restaurant-api')->user();

            if (!$restaurant) {
                return api_unauthorized('Restaurant not found. Please login again.');
            }

            $menu_items = MenuItem::where('restaurant_id', $restaurant->id)
                ->with(['restaurant', 'foodCategory'])
                ->get();

            foreach ($menu_items as $menu_item) {
                // Add asset path to menu item image
                $menu_item->image = asset("storage/menuItem/images/{$menu_item->image}");

                // Check if restaurant has an image and add asset path
                if ($menu_item->restaurant && $menu_item->restaurant->featured_image) {
                    $menu_item->restaurant->featured_image = asset("storage/restaurants/featured/{$menu_item->restaurant->featured_image}");
                }

                // Check if food category has an image and add asset path
                if ($menu_item->foodCategory && $menu_item->foodCategory->image) {
                    $menu_item->foodCategory->image = asset("storage/foodCategory/images/{$menu_item->foodCategory->image}");
                }
            }

            return api_success('Menu Items retrieved successfully', $menu_items);
        } catch (\Exception $e) {
            return api_exception($e, 'Failed to retrieve Menu Items');
        }
    }



}
