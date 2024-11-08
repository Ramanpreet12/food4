<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\CustomerRegisterRequest;
use App\Models\User;


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
    public function login(){
        return 'login';
    }
    public function dashboard(){
        return 'customer';
    }
}
