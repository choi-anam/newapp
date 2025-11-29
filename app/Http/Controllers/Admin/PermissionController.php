<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        if (!$user->can('view permissions')) {
            abort(403, 'Unauthorized - You do not have permission to view permissions.');
        }

        $permissions = Permission::paginate(15);
        return view('admin.permissions.index', compact('permissions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = auth()->user();
        if (!$user->can('create permissions')) {
            abort(403, 'Unauthorized - You do not have permission to create permissions.');
        }

        return view('admin.permissions.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        if (!$user->can('create permissions')) {
            abort(403, 'Unauthorized - You do not have permission to create permissions.');
        }

        $validated = $request->validate([
            'name' => 'required|string|unique:permissions|max:255',
            'description' => 'nullable|string|max:500',
        ]);

        $permission = Permission::create($validated);

        return redirect()->route('admin.permissions.index')
            ->with('success', 'Permission berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Permission $permission)
    {
        $user = auth()->user();
        if (!$user->can('view permissions')) {
            abort(403, 'Unauthorized - You do not have permission to view permissions.');
        }

        return view('admin.permissions.show', compact('permission'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Permission $permission)
    {
        $user = auth()->user();
        if (!$user->can('update permissions')) {
            abort(403, 'Unauthorized - You do not have permission to edit permissions.');
        }

        return view('admin.permissions.edit', compact('permission'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Permission $permission)
    {
        $user = auth()->user();
        if (!$user->can('update permissions')) {
            abort(403, 'Unauthorized - You do not have permission to update permissions.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name,' . $permission->id,
            'description' => 'nullable|string|max:500',
        ]);

        $permission->update($validated);

        return redirect()->route('admin.permissions.index')
            ->with('success', 'Permission berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Permission $permission)
    {
        $user = auth()->user();
        if (!$user->can('delete permissions')) {
            abort(403, 'Unauthorized - You do not have permission to delete permissions.');
        }

        $permission->delete();

        return redirect()->route('admin.permissions.index')
            ->with('success', 'Permission berhasil dihapus.');
    }
}
