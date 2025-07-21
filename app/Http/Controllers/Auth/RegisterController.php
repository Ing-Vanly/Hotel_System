<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Brian2694\Toastr\Facades\Toastr;
use Hash;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;

class RegisterController extends Controller
{
    public function register()
    {
        return view('auth.register');
    }
    
    public function storeUser(Request $request)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|string|email|max:255|unique:users',
            'role_id'      => 'required|exists:roles,id',
            'phone_number' => 'nullable|string|max:20',
            'gender'       => 'nullable|in:male,female,other',
            'age'          => 'nullable|integer|min:0|max:100',
            'address'      => 'nullable|string|max:255',
            'password'     => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required',
            'image'        => 'nullable|image|max:2048',
        ]);
        
        DB::beginTransaction();
        try {
            $dt = Carbon::now();
            $join_date = $dt->toDateTimeString();
            
            // Get role information
            $role = Role::findOrFail($request->role_id);
            
            // Handle image upload
            $imagePath = null;
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '_' . $image->getClientOriginalName();
                $image->move(public_path('assets/upload'), $imageName);
                $imagePath = $imageName;
            }
            
            // Create user
            $user = new User();
            $user->name         = $request->name;
            $user->email        = $request->email;
            $user->phone_number = $request->phone_number;
            $user->join_date    = $join_date;
            $user->role         = $role->name;
            // Only set role_id if column exists
            if (Schema::hasColumn('users', 'role_id')) {
                $user->role_id = $role->id;
            }
            $user->gender       = $request->gender;
            $user->age          = $request->age;
            $user->address      = $request->address;
            $user->image        = $imagePath;
            // Only set status if column exists
            if (Schema::hasColumn('users', 'status')) {
                $user->status = 'active';
            }
            $user->password     = Hash::make($request->password);
            $user->save();
            
            // Assign role using Spatie
            $user->assignRole($role->name);
            
            DB::commit();
            Toastr::success('User created successfully!', 'Success');
            
            // Redirect based on context
            if ($request->expectsJson()) {
                return response()->json(['success' => true, 'message' => 'User created successfully']);
            }
            
            // If coming from user management, redirect there
            if ($request->has('redirect_to_users')) {
                return redirect()->route('users/list/page');
            }
            
            return redirect('login');
            
        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error('Error creating user: ' . $e->getMessage(), 'Error');
            return redirect()->back()->withInput();
        }
    }
}
