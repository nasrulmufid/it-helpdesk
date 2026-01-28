<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    // User Management
    public function users()
    {
        $users = User::latest()->paginate(20);
        return view('admin.users.index', compact('users'));
    }

    public function createUser()
    {
        return view('admin.users.create');
    }

    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,technician,user,general_manager',
            'phone' => 'nullable|string|max:20',
            'department' => 'nullable|string|max:100',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('admin.users')
            ->with('success', 'Pengguna berhasil ditambahkan!');
    }

    public function editUser(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function updateUser(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,technician,user,general_manager',
            'phone' => 'nullable|string|max:20',
            'department' => 'nullable|string|max:100',
        ]);

        if ($request->filled('password')) {
            $request->validate([
                'password' => 'string|min:8|confirmed',
            ]);
            $validated['password'] = Hash::make($request->password);
        }

        $user->update($validated);

        return redirect()->route('admin.users')
            ->with('success', 'Pengguna berhasil diperbarui!');
    }

    public function destroyUser(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun sendiri!');
        }

        $user->delete();

        return back()->with('success', 'Pengguna berhasil dihapus!');
    }

    // Category Management
    public function categories()
    {
        $categories = Category::latest()->paginate(20);
        return view('admin.categories.index', compact('categories'));
    }

    public function createCategory()
    {
        return view('admin.categories.create');
    }

    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:10',
            'is_active' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        Category::create($validated);

        return redirect()->route('admin.categories')
            ->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function editCategory(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function updateCategory(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:10',
            'is_active' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $category->update($validated);

        return redirect()->route('admin.categories')
            ->with('success', 'Kategori berhasil diperbarui!');
    }

    public function destroyCategory(Category $category)
    {
        if ($category->tickets()->count() > 0) {
            return back()->with('error', 'Kategori tidak dapat dihapus karena masih memiliki tiket!');
        }

        $category->delete();

        return back()->with('success', 'Kategori berhasil dihapus!');
    }
}
