@extends('layouts.admin')

@section('title', 'Edit User')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Edit User</h1>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Update user profile and manage addresses.</p>
        </div>
        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.');">
            @csrf
            @method('DELETE')
            <x-admin.actions.button type="submit" variant="danger">
                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                Delete User
            </x-admin.actions.button>
        </form>
    </div>

    <div class="space-y-8">
        <!-- Form 1: User Information -->
        <form action="{{ route('admin.users.update', $user) }}" method="POST">
            @csrf
            @method('PUT')
            
            <x-admin.ui.card>
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            User Information
                        </h2>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Name -->
                        <div class="col-span-2 md:col-span-1">
                            <x-admin.form.group label="Full Name" for="name" required>
                                <x-admin.form.input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required />
                            </x-admin.form.group>
                        </div>

                        <!-- Email -->
                        <div class="col-span-2 md:col-span-1">
                            <x-admin.form.group label="Email Address" for="email" required>
                                <x-admin.form.input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required />
                            </x-admin.form.group>
                        </div>

                        <!-- Password (Optional) -->
                        <div class="col-span-2 md:col-span-1">
                            <x-admin.form.group label="New Password" for="password">
                                <x-slot:hint>(Leave blank to keep)</x-slot:hint>
                                <x-admin.form.input type="password" name="password" id="password" />
                            </x-admin.form.group>
                        </div>

                        <!-- Confirm Password -->
                        <div class="col-span-2 md:col-span-1">
                            <x-admin.form.group label="Confirm New Password" for="password_confirmation">
                                <x-admin.form.input type="password" name="password_confirmation" id="password_confirmation" />
                            </x-admin.form.group>
                        </div>

                        <!-- Role -->
                        <div class="col-span-2">
                            <x-admin.form.group label="Role" for="user_type" required>
                                <x-admin.form.select name="user_type" id="user_type" :options="['user' => 'User (Customer)', 'admin' => 'Administrator']" :selected="old('user_type', $user->user_type)" />
                            </x-admin.form.group>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <x-admin.actions.button type="submit" variant="primary">
                            Save Profile
                        </x-admin.actions.button>
                    </div>
                </div>
            </x-admin.ui.card>
        </form>

        <!-- Form 2: Address Book -->
        <form action="{{ route('admin.users.update', $user) }}" method="POST" x-data="userEditForm()">
            @csrf
            @method('PUT')
            
            <input type="hidden" name="delete_address_ids" :value="deletedAddressIds.join(',')">

            <x-admin.ui.card>
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            Address Book
                        </h2>
                        <button type="button" @click="addAddress()" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-primary bg-primary/10 hover:bg-primary/20 focus:outline-none transition-colors">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                            Add Address
                        </button>
                    </div>

                    <div class="space-y-4">
                        <template x-for="(address, index) in addresses" :key="address.id || address.tempId">
                            <div class="relative bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 border border-gray-200 dark:border-gray-600 transition-all hover:border-primary/50">
                                
                                <div class="absolute top-4 right-4">
                                    <button type="button" @click="removeAddress(index)" class="text-gray-400 hover:text-red-500 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <!-- Address inputs -->
                                    <div class="col-span-2">
                                        <x-admin.form.label x-bind:for="'address_line_1_' + index" value="Address Line 1" />
                                        <x-admin.form.input type="text" x-bind:name="'addresses[' + (address.id || address.tempId) + '][address_line_1]'" x-model="address.address_line_1" class="mt-1" x-bind:id="'address_line_1_' + index" />
                                    </div>
                                    <div class="col-span-2">
                                        <x-admin.form.label x-bind:for="'address_line_2_' + index" value="Address Line 2" />
                                        <x-admin.form.input type="text" x-bind:name="'addresses[' + (address.id || address.tempId) + '][address_line_2]'" x-model="address.address_line_2" class="mt-1" x-bind:id="'address_line_2_' + index" />
                                    </div>
                                    <div>
                                        <x-admin.form.label x-bind:for="'city_' + index" value="City" />
                                        <x-admin.form.input type="text" x-bind:name="'addresses[' + (address.id || address.tempId) + '][city]'" x-model="address.city" class="mt-1" x-bind:id="'city_' + index" />
                                    </div>
                                    <div>
                                        <x-admin.form.label x-bind:for="'state_' + index" value="State" />
                                        <x-admin.form.input type="text" x-bind:name="'addresses[' + (address.id || address.tempId) + '][state]'" x-model="address.state" class="mt-1" x-bind:id="'state_' + index" />
                                    </div>
                                    <div>
                                        <x-admin.form.label x-bind:for="'postal_code_' + index" value="Postal Code" />
                                        <x-admin.form.input type="text" x-bind:name="'addresses[' + (address.id || address.tempId) + '][postal_code]'" x-model="address.postal_code" class="mt-1" x-bind:id="'postal_code_' + index" />
                                    </div>
                                    <div>
                                        <x-admin.form.label x-bind:for="'country_' + index" value="Country" />
                                        <x-admin.form.input type="text" x-bind:name="'addresses[' + (address.id || address.tempId) + '][country]'" x-model="address.country" class="mt-1" x-bind:id="'country_' + index" />
                                    </div>
                                    <div>
                                        <x-admin.form.label x-bind:for="'phone_' + index" value="Phone" />
                                        <x-admin.form.input type="text" x-bind:name="'addresses[' + (address.id || address.tempId) + '][phone]'" x-model="address.phone" class="mt-1" x-bind:id="'phone_' + index" />
                                    </div>
                                    <div class="flex items-center mt-6">
                                        <input type="checkbox" :name="'addresses[' + (address.id || address.tempId) + '][is_default]'" value="1" x-model="address.is_default" class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                                        <label class="ml-2 block text-sm text-gray-700 dark:text-gray-300">Set as Default</label>
                                    </div>
                                </div>
                            </div>
                        </template>
                        
                        <div x-show="addresses.length === 0" class="text-center py-8 text-gray-500 dark:text-gray-400 text-sm italic">
                            No addresses found. Click "Add Address" to create one.
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <x-admin.actions.button type="submit" variant="primary">
                            Save Addresses
                        </x-admin.actions.button>
                    </div>
                </div>
            </x-admin.ui.card>
        </form>
    </div>

    <script>
        function userEditForm() {
            return {
                addresses: @json($user->addresses),
                deletedAddressIds: [],
                
                addAddress() {
                    this.addresses.push({
                        tempId: 'new_' + Date.now(),
                        address_line_1: '',
                        address_line_2: '',
                        city: '',
                        state: '',
                        postal_code: '',
                        country: '',
                        phone: '',
                        is_default: false
                    });
                },
                
                removeAddress(index) {
                    const address = this.addresses[index];
                    if (address.id) {
                        this.deletedAddressIds.push(address.id);
                    }
                    this.addresses.splice(index, 1);
                }
            }
        }
    </script>
@endsection
