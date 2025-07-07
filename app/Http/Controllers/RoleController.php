<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::all();
        return view('role.index', compact('roles'));
    }

    public function create()
    {
        return view('role.create');
    }

public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255|unique:roles,name',
    ]);

    Role::create([
        'name' => $request->name,
        'guard_name' => 'web',
    ]);

    return redirect()->route('role.index')->with('success', 'Role created successfully.');
}


    public function show(string $id)
    {
        $role = Role::findOrFail($id);
        return view('role.show', compact('role'));
    }

    public function edit(string $id)
    {
        $role = Role::findOrFail($id);
        return view('role.edit', compact('role'));
    }

public function update(Request $request, string $id)
{
    $role = Role::findOrFail($id);

    $request->validate([
        'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
    ]);

    $role->update([
        'name' => $request->name,
        'guard_name' => 'web', 
    ]);

    return redirect()->route('role.index')->with('success', 'Role updated successfully.');
}


    public function destroy(string $id)
    {
        $role = Role::findOrFail($id);
        $role->delete();

        return redirect()->route('role.index')->with('success', 'Role deleted successfully.');
    }
}
