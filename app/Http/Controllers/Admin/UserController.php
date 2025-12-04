<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

/**
 * @property User $user
 */
class UserController extends Controller
{
   public function index(Request $request)
    {
        $search = $request->input('search');
        $role   = $request->input('role', 'all');

        $users = User::query()

            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%");
                });
            })

            ->when($role !== 'all', function ($query) use ($role) {
                $query->where('role', $role);
            })

            ->orderBy('id', 'asc') // 🔥 sorrendezés ID szerint
            ->paginate(10);

        return view('admin.users.index', compact('users', 'search', 'role'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'role'  => 'required|in:admin,user',
        ]);
    
        // Admin önmagát NEM minősítheti vissza
       if ($user->getKey() === auth()->id() && $request->role !== 'admin') {
            return back()->with('error', 'You cannot change your own role.');
        }
    
        $user->update([
            'name'  => $request->name,
            'email' => $request->email,
            'role'  => $request->role,
        ]);
    
        return redirect()->route('admin.users.index')
                         ->with('success', 'User updated successfully.');
    }
    

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User deleted');
    }

}
