<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Exports\UsersExport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index()
    {
        return view('admin.users.index-gridjs');
    }

    /**
     * Get users data for Grid.js (AJAX)
     */
    public function getData(Request $request)
    {
        $query = User::with('roles');
        
        // Search
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('uid', 'like', "%{$search}%");
            });
        }
        
        // Pagination - Grid.js uses 0-based page indexing for server.url
        $page = max(0, $request->get('page', 0));
        $limit = $request->get('limit', 10);
        $offset = $page * $limit;
        
        $total = $query->count();
        $users = $query->skip($offset)->take($limit)->get();
        
        $data = $users->map(function($user) {
            $roles = $user->roles->pluck('name')->map(function($role) {
                $badgeClass = $role === 'super-admin' ? 'danger' : ($role === 'admin' ? 'primary' : 'secondary');
                return '<span class="badge bg-' . $badgeClass . '">' . ucfirst(str_replace('-', ' ', $role)) . '</span>';
            })->implode(' ');
            
            return [
                $user->id,
                $user->name,
                $user->username ?: '-',
                $user->email,
                $roles,
                $user->created_at->format('d M Y'),
            ];
        });
        
        return response()->json([
            'data' => $data,
            'total' => $total,
        ]);
    }

    /**
     * Export users to Excel
     */
    public function export()
    {
        return Excel::download(new UsersExport, 'users-' . date('Y-m-d-His') . '.xlsx');
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        $roles = \Spatie\Permission\Models\Role::all();
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'nullable|string|max:255|unique:users',
            'uid' => 'nullable|string|max:255|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'roles' => 'array|exists:roles,name',
        ]);

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'uid' => $request->uid,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        if ($request->has('roles')) {
            $user->syncRoles($request->roles);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil dibuat');
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        $user->load('roles');
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        $roles = \Spatie\Permission\Models\Role::all();
        $user->load('roles');
        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'nullable|string|max:255|unique:users,username,' . $user->id,
            'uid' => 'nullable|string|max:255|unique:users,uid,' . $user->id,
            'email' => 'required|email|unique:users,email,' . $user->id,
            'roles' => 'array|exists:roles,name',
        ]);

        $user->update([
            'name' => $request->name,
            'username' => $request->username,
            'uid' => $request->uid,
            'email' => $request->email,
        ]);

        if ($request->has('roles')) {
            $user->syncRoles($request->roles);
        }

        return redirect()->route('admin.users.show', $user)
            ->with('success', 'User berhasil diupdate');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        // Prevent deleting super-admin users
        if ($user->hasRole('super-admin')) {
            return response()->json(['error' => 'Tidak dapat menghapus user super-admin'], 403);
        }
        
        // Prevent deleting the authenticated user
        if ($user->id === auth()->id()) {
            return response()->json(['error' => 'Tidak dapat menghapus user yang sedang login'], 403);
        }

        $user->delete();

        return response()->json(['success' => 'User berhasil dihapus']);
    }

    /**
     * Reset user password.
     */
    public function resetPassword(User $user)
    {
        $newPassword = Str::random(12);

        $user->update([
            'password' => Hash::make($newPassword),
            'password_reset_token' => $newPassword,
        ]);

        // Don't log the new plaintext password. Log that password was reset.
        activity()
            ->causedBy(auth()->user())
            ->performedOn($user)
            ->withProperties(['action' => 'reset-password'])
            ->log('reset password for user');

        return redirect()->route('admin.users.show', $user)
            ->with('success', "Password berhasil direset. Password baru: <strong>$newPassword</strong>");
    }
}
