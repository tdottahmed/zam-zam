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
    /**
     * List only customers (admins are managed outside this CRUD).
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $status = $request->get('status');

        $users = User::query()
            ->where('user_type', 'user')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($status !== null && $status !== '', function ($query) use ($status) {
                $query->where('status', $status);
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
            'email' => ['nullable', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['nullable', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
            'status' => ['required', 'string', 'in:pending,approved'],

            // Profile validation
            'profile' => ['nullable', 'array'],
            'profile.contact_no' => ['nullable', 'string', 'max:255'],
            'profile.company_name' => ['nullable', 'string', 'max:255'],
            'profile.website' => ['nullable', 'string', 'max:255'],
            'profile.job_title' => ['nullable', 'string', 'max:255'],
            'profile.fax' => ['nullable', 'string', 'max:255'],
            'profile.tax_id' => ['nullable', 'string', 'max:255'],
            'profile.bank_name' => ['nullable', 'string', 'max:255'],
            'profile.bank_account_no' => ['nullable', 'string', 'max:255'],
            'profile.notes' => ['nullable', 'string'],

            // Address validation (multiple)
            'addresses' => ['nullable', 'array'],
            'addresses.*.type' => ['nullable', 'string', 'in:Business,Shipping,Billing'],
            'addresses.*.address_line_1' => ['required_with:addresses', 'string', 'max:255'],
            'addresses.*.city' => ['required_with:addresses', 'string', 'max:255'],
            'addresses.*.state' => ['nullable', 'string', 'max:255'],
            'addresses.*.postal_code' => ['nullable', 'string', 'max:20'],
            'addresses.*.country' => ['nullable', 'string', 'max:255'],
            'addresses.*.phone' => ['nullable', 'string', 'max:50'],
        ]);

        $email = $validated['email'] ?? null;
        if (!$email) {
            $nextId = User::max('id') + 1;
            $email = 'user' . $nextId . '@zamzamcanada.com';
            while (User::where('email', $email)->exists()) {
                $nextId++;
                $email = 'user' . $nextId . '@zamzamcanada.com';
            }
        }

        $password = $validated['password'] ?? '12345678';

        $user = User::create([
            'name' => $validated['name'],
            'email' => $email,
            'password' => Hash::make($password),
            'user_type' => 'user',
            'status' => $validated['status'],
            'email_verified_at' => now(),
        ]);

        // Create profile if provided
        if ($request->has('profile')) {
            $user->profile()->create($validated['profile']);
        }

        // Create addresses if provided
        if ($request->has('addresses')) {
            foreach ($request->addresses as $addressData) {
                $user->addresses()->create([
                    'type' => $addressData['type'] ?? 'shipping',
                    'name' => $user->name,
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
            ->with('success', 'Customer created successfully.');
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
        abort_if($user->user_type !== 'user', 404, 'Only customer accounts can be edited here.');

        $user->load('addresses', 'profile');
        $addressesForEdit = $user->addresses->map(function ($a) {
            return [
                'id' => $a->id,
                'type' => $a->type ?? 'Shipping',
                'address_line_1' => $a->address_line_1,
                'address_line_2' => $a->address_line_2 ?? '',
                'city' => $a->city,
                'state' => $a->state ?? '',
                'postal_code' => $a->postal_code ?? '',
                'country' => $a->country ?? '',
                'phone' => $a->phone ?? '',
                'is_default' => (bool) $a->is_default,
            ];
        })->values();
        return view('admin.users.edit', compact('user', 'addressesForEdit'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        abort_if($user->user_type !== 'user', 404, 'Only customer accounts can be updated here.');

        $updated = false;

        // Handle Customer Basic Information + Profile (main form submit)
        if ($request->has('name') || $request->has('email')) {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
                'password' => ['nullable', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
                'status' => ['required', 'string', 'in:pending,approved'],
            ]);

            $user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'status' => $validated['status'],
            ]);

            if ($request->filled('password')) {
                $user->update([
                    'password' => Hash::make($validated['password']),
                ]);
            }
            $updated = true;

            // Handle Profile Update
            if ($request->has('profile')) {
                $profileData = $request->validate([
                    'profile.contact_no' => ['nullable', 'string', 'max:255'],
                    'profile.company_name' => ['nullable', 'string', 'max:255'],
                    'profile.website' => ['nullable', 'string', 'max:255'],
                    'profile.job_title' => ['nullable', 'string', 'max:255'],
                    'profile.fax' => ['nullable', 'string', 'max:255'],
                    'profile.tax_id' => ['nullable', 'string', 'max:255'],
                    'profile.bank_name' => ['nullable', 'string', 'max:255'],
                    'profile.bank_account_no' => ['nullable', 'string', 'max:255'],
                    'profile.notes' => ['nullable', 'string'],
                ]);

                $user->profile()->updateOrCreate(
                    ['user_id' => $user->id],
                    $profileData['profile'] ?? []
                );
            }
        }

        // Handle Address Update (same request when submitted from single form)
        if ($request->has('addresses') || $request->filled('delete_address_ids')) {
            if ($request->filled('delete_address_ids')) {
                $user->addresses()->whereIn('id', explode(',', $request->delete_address_ids))->delete();
            }
            if ($request->has('addresses')) {
                foreach ($request->addresses as $id => $data) {
                    $data['is_default'] = isset($data['is_default']) && $data['is_default'];
                    if (is_string($id) && str_starts_with($id, 'new_')) {
                        $user->addresses()->create(array_merge($data, [
                            'name' => $user->name,
                            'email' => $user->email,
                        ]));
                    } elseif (is_numeric($id)) {
                        $user->addresses()->where('id', $id)->update($data);
                    }
                }
            }
            $updated = true;
        }

        if ($updated) {
            return back()->with('success', 'Customer updated successfully.');
        }

        return back()->with('warning', 'No changes were saved.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        abort_if($user->user_type !== 'user', 404, 'Only customer accounts can be deleted from here.');

        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete yourself.');
        }

        $user->delete();
        return redirect()->route('admin.users.index')
            ->with('success', 'Customer deleted successfully.');
    }
}
