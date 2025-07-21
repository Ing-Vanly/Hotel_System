<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\User;
use App\Models\Role;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class UserManagementController extends Controller
{
    /** Show user list page */
    public function userList(Request $request)
    {
        // Get pagination settings
        $perPage = $request->get('per_page', 15); // Default 15 users per page
        $validPerPageOptions = [10, 15, 25, 50, 100];

        // Validate per_page value
        if (!in_array($perPage, $validPerPageOptions)) {
            $perPage = 15;
        }

        // Get all users with their roles, optionally filter by status
        $query = User::orderBy('created_at', 'desc');

        // Apply filters if provided
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // If you add a 'status' column, this will allow filtering by status
        if ($request->filled('status')) {
            if (Schema::hasColumn('users', 'status')) {
                $query->where('status', $request->status);
            }
        }

        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('user_id', 'like', "%{$search}%")
                    ->orWhere('phone_number', 'like', "%{$search}%");
            });
        }

        // Paginate the results
        $users = $query->paginate($perPage);

        // Preserve query parameters for pagination links
        $users->appends($request->query());

        $roles = Role::all();
        $totalUsers = User::count(); // Get total count for display

        return view('usermanagement.listuser', compact('users', 'roles', 'totalUsers', 'perPage', 'validPerPageOptions'));
    }


    /** Show add new user form */
    public function userAddNew()
    {
        $roles = Role::all();
        return view('usermanagement.useraddnew', compact('roles'));
    }

    /** Create new user */
    public function userStore(Request $request)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|string|email|max:255|unique:users',
            'role_id'      => 'required|exists:roles,id',
            'phone_number' => 'nullable|string|max:20',
            'gender'       => 'nullable|in:male,female,other',
            'age'          => 'nullable|integer|min:0|max:100',
            // If you add status, keep this validation
            'status'       => 'required|in:active,inactive',
            'address'      => 'nullable|string|max:255',
            'password'     => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required',
            'image'        => 'nullable|image|max:2048',
        ]);

        DB::beginTransaction();
        try {
            // Get role information
            $role = Role::findOrFail($request->role_id);

            // Handle image upload
            $imagePath = null;
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '_' . $image->getClientOriginalName();
                // Store in public/assets/user_image
                $destinationPath = public_path('assets/user_image');
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }
                $image->move($destinationPath, $imageName);
                $imagePath = $imageName;
            }

            // Create user
            $user = new User();
            $user->name         = $request->name;
            $user->email        = $request->email;
            $user->phone_number = $request->phone_number;
            $user->join_date    = now();
            $user->role         = $role->name;
            // Only set role_id if column exists
            if (Schema::hasColumn('users', 'role_id')) {
                $user->role_id = $role->id;
            }
            $user->gender       = $request->gender;
            $user->age          = $request->age;
            // If you add status, set it from the request
            if (Schema::hasColumn('users', 'status')) {
                $user->status = $request->status;
            }
            $user->address      = $request->address;
            $user->image        = $imagePath;
            $user->password     = Hash::make($request->password);
            $user->save();

            // Assign role using Spatie
            try {
                $user->assignRole($role->name);
            } catch (\Exception $e) {
                // If Spatie roles fail, just continue
                \Log::info('Spatie role assignment failed: ' . $e->getMessage());
            }

            DB::commit();
            Toastr::success('User created successfully!', 'Success');
            return redirect()->route('users/list/page');
        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error('Error creating user: ' . $e->getMessage(), 'Error');
            return redirect()->back()->withInput();
        }
    }

    /** Show edit user form */
    public function userView($user_id)
    {
        $userData = User::where('user_id', $user_id)->firstOrFail();
        $roles = Role::all();
        return view('usermanagement.useredit', compact('userData', 'roles'));
    }

    /** Update user record */
    public function userUpdate(Request $request)
    {
        $request->validate([
            'user_id'      => 'required|string|exists:users,user_id',
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|max:255|unique:users,email,' . $request->user_id . ',user_id',
            'phone_number' => 'nullable|string|max:20',
            'role_id'      => 'required|exists:roles,id',
            'gender'       => 'nullable|in:male,female,other',
            'age'          => 'nullable|integer|min:0',
            'address'      => 'nullable|string|max:255',
            // If you add status, keep this validation
            'status'       => 'required|in:active,inactive',
            'image'        => 'nullable|image|max:2048',
            'password'     => 'nullable|min:8|confirmed',
        ]);

        DB::beginTransaction();
        try {
            $user = User::where('user_id', $request->user_id)->firstOrFail();
            $role = Role::findOrFail($request->role_id);

            // Update fields
            $user->name         = $request->name;
            $user->email        = $request->email;
            $user->phone_number = $request->phone_number;
            $user->role         = $role->name;
            // Only set role_id if column exists
            if (Schema::hasColumn('users', 'role_id')) {
                $user->role_id = $role->id;
            }
            $user->gender       = $request->gender;
            $user->age          = $request->age;
            $user->address      = $request->address;
            // If you add status, update it from the request
            if (Schema::hasColumn('users', 'status')) {
                $user->status = $request->status;
            }

            // Update password if provided
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }

            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($user->image && file_exists(public_path('assets/user_image/' . $user->image))) {
                    unlink(public_path('assets/user_image/' . $user->image));
                }
                // Upload new image
                $image = $request->file('image');
                $imageName = time() . '_' . $image->getClientOriginalName();
                $destinationPath = public_path('assets/user_image');
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }
                $image->move($destinationPath, $imageName);
                $user->image = $imageName;
            }

            $user->save();

            // Assign role using Spatie
            try {
                $user->assignRole($role->name);
            } catch (\Exception $e) {
                // If Spatie roles fail, just continue
                \Log::info('Spatie role assignment failed: ' . $e->getMessage());
            }

            DB::commit();
            Toastr::success('User updated successfully!', 'Success');
            return redirect()->route('users/list/page');
        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error('Update failed: ' . $e->getMessage(), 'Error');
            return redirect()->back()->withInput();
        }
    }

    /** Delete user record */
    public function userDelete(Request $request)
    {
        try {
            $user = User::findOrFail($request->id);

            // Soft delete the user
            $user->deleted_by = Auth::id();
            $user->save();
            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting user: ' . $e->getMessage()
            ]);
        }
    }

    /** Get roles for AJAX */
    public function getRoles()
    {
        $roles = Role::all();
        return response()->json($roles);
    }
}
