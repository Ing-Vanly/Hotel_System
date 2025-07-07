<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\User;
use App\Models\Role; // Add this line
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class UserManagementController extends Controller
{
    /** Show user list page */
    public function userList()
    {
        return view('usermanagement.listuser');
    }

    /** Show add new user form */
    public function userAddNew()
    {
        $roles = Role::all(); // Get all roles from the roles table
        return view('usermanagement.useraddnew', compact('roles'));
    }

    /** Show edit user form */
    public function userView($user_id)
    {
        $userData = User::where('user_id', $user_id)->firstOrFail();
        $roles = Role::all(); // Get roles for edit form too
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
            'role_id'      => 'required|exists:roles,id', // Changed to role_id
            'gender'       => 'nullable|in:male,female,other',
            'age'          => 'nullable|integer|min:0',
            'address'      => 'nullable|string|max:255',
            'image'        => 'nullable|image|max:2048',
        ]);

        DB::beginTransaction();
        try {
            $user = User::where('user_id', $request->user_id)->firstOrFail();
            $role = Role::findOrFail($request->role_id);

            // Update fields
            $user->name         = $request->name;
            $user->email        = $request->email;
            $user->phone_number = $request->phone_number;
            $user->role         = $role->name; // Store role name
            $user->role_id      = $role->id;   // Store role ID
            $user->gender       = $request->gender;
            $user->age          = $request->age;
            $user->address      = $request->address;

            // Handle image upload if exists
            if ($request->hasFile('image')) {
                if ($user->image && Storage::disk('public')->exists($user->image)) {
                    Storage::disk('public')->delete($user->image);
                }
                $path = $request->file('image')->store('user_images', 'public');
                $user->image = $path;
            }

            $user->save();

            DB::commit();
            Toastr::success('Updated record successfully :)', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error('Update record failed: ' . $e->getMessage(), 'Error');
            return redirect()->back();
        }
    }

    // ... keep the rest of your controller methods the same ...
}
