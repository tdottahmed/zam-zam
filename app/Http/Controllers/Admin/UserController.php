<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        
        $users = User::query()
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
            'user_type' => ['required', 'string', 'in:user,admin'],
            
            // Address validation (multiple)
            'addresses' => ['nullable', 'array'],
            'addresses.*.address_line_1' => ['required_with:addresses', 'string', 'max:255'],
            'addresses.*.city' => ['required_with:addresses', 'string', 'max:255'],
            'addresses.*.state' => ['nullable', 'string', 'max:255'],
            'addresses.*.postal_code' => ['nullable', 'string', 'max:20'],
            'addresses.*.country' => ['nullable', 'string', 'max:255'],
            'addresses.*.phone' => ['nullable', 'string', 'max:20'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'user_type' => $validated['user_type'],
            'email_verified_at' => now(),
        ]);

        // Create addresses if provided
        if ($request->has('addresses')) {
            foreach ($request->addresses as $addressData) {
                $user->addresses()->create([
                    'type' => 'shipping', // Default
                    'name' => $user->name, // Default to user name/email if not specified in address form (Edit view didn't have specific name/email per address, assuming same user)
                    'email' => $user->email,
                    'phone' => $addressData['phone'] ?? null,
                    'address_line_1' => $addressData['address_line_1'],
                    'address_line_2' => $addressData['address_line_2'] ?? null,
                    'city' => $addressData['city'],
                    'state' => $addressData['state'] ?? null,
                    'postal_code' => $addressData['postal_code'] ?? null,
                    'country' => $addressData['country'] ?? null,
                    'is_default' => isset($addressData['is_default']) ? (bool)$addressData['is_default'] : false,
                ]);
            }
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $user->load('addresses');
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        // Handle User Basic Information Update
        if ($request->has('name') || $request->has('email')) {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
                'password' => ['nullable', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
                'user_type' => ['required', 'string', 'in:user,admin'],
            ]);

            $user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'user_type' => $validated['user_type'],
            ]);

            if ($request->filled('password')) {
                $user->update([
                    'password' => Hash::make($validated['password']),
                ]);
            }

            return back()->with('success', 'User profile updated successfully.');
        }
        
        // Handle Address Update
        if ($request->has('addresses') || $request->filled('delete_address_ids')) {
             if ($request->has('addresses')) {
                 foreach ($request->addresses as $id => $data) {
                     if (str_starts_with($id, 'new_')) {
                         $user->addresses()->create($data);
                     } else {
                         $user->addresses()->where('id', $id)->update($data);
                     }
                 }
             }
        
            if ($request->filled('delete_address_ids')) {
                $user->addresses()->whereIn('id', explode(',', $request->delete_address_ids))->delete();
            }

            return back()->with('success', 'Address book updated successfully.');
        }

        return back()->with('warning', 'No changes were saved.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete yourself.');
        }
        
        $user->delete();
        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }
}
