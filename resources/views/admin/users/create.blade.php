@extends('layouts.admin')

@section('title', 'Create User')

@section('content')
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Create New User</h1>
        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Add a new user to the system with their addresses.</p>
    </div>

    <!-- Wrapper Form -->
    <form action="{{ route('admin.users.store') }}" method="POST" x-data="userCreateForm()">
        @csrf

        <div class="space-y-8">
            <!-- User Information Card -->
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
                                <x-admin.form.input type="text" name="name" id="name" value="{{ old('name') }}" required />
                            </x-admin.form.group>
                        </div>

                        <!-- Email -->
                        <div class="col-span-2 md:col-span-1">
                            <x-admin.form.group label="Email Address" for="email" required>
                                <x-admin.form.input type="email" name="email" id="email" value="{{ old('email') }}" required />
                            </x-admin.form.group>
                        </div>

                        <!-- Password -->
                        <div class="col-span-2 md:col-span-1">
                            <x-admin.form.group label="Password" for="password" required>
                                <x-admin.form.input type="password" name="password" id="password" required />
                            </x-admin.form.group>
                        </div>

                        <!-- Confirm Password -->
                        <div class="col-span-2 md:col-span-1">
                            <x-admin.form.group label="Confirm Password" for="password_confirmation" required>
                                <x-admin.form.input type="password" name="password_confirmation" id="password_confirmation" required />
                            </x-admin.form.group>
                        </div>

                        <!-- Role -->
                        <div class="col-span-2">
                            <x-admin.form.group label="Role" for="user_type" required>
                                <x-admin.form.select name="user_type" id="user_type" :options="['user' => 'User (Customer)', 'admin' => 'Administrator']" :selected="old('user_type')" />
                            </x-admin.form.group>
                        </div>
                    </div>

                    <!-- Submit Button for User Info Section -->
                    <div class="mt-6 flex justify-end">
                        <x-admin.actions.button type="submit" variant="primary">
                            Create User
                        </x-admin.actions.button>
                    </div>
                </div>
            </x-admin.ui.card>

            <!-- Address Book Card (Dynamic) -->
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
                        <template x-for="(address, index) in addresses" :key="address.tempId">
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
                                        <x-admin.form.input type="text" x-bind:name="'addresses[' + index + '][address_line_1]'" x-model="address.address_line_1" class="mt-1" x-bind:id="'address_line_1_' + index" placeholder="Street address, P.O. box, etc." required />
                                    </div>
                                    <div class="col-span-2">
                                        <x-admin.form.label x-bind:for="'address_line_2_' + index" value="Address Line 2" />
                                        <x-admin.form.input type="text" x-bind:name="'addresses[' + index + '][address_line_2]'" x-model="address.address_line_2" class="mt-1" x-bind:id="'address_line_2_' + index" placeholder="Apartment, suite, unit, etc." />
                                    </div>
                                    <div>
                                        <x-admin.form.label x-bind:for="'city_' + index" value="City" />
                                        <x-admin.form.input type="text" x-bind:name="'addresses[' + index + '][city]'" x-model="address.city" class="mt-1" x-bind:id="'city_' + index" required />
                                    </div>
                                    <div>
                                        <x-admin.form.label x-bind:for="'state_' + index" value="State" />
                                        <x-admin.form.input type="text" x-bind:name="'addresses[' + index + '][state]'" x-model="address.state" class="mt-1" x-bind:id="'state_' + index" />
                                    </div>
                                    <div>
                                        <x-admin.form.label x-bind:for="'postal_code_' + index" value="Postal Code" />
                                        <x-admin.form.input type="text" x-bind:name="'addresses[' + index + '][postal_code]'" x-model="address.postal_code" class="mt-1" x-bind:id="'postal_code_' + index" />
                                    </div>
                                    <div>
                                        <x-admin.form.label x-bind:for="'country_' + index" value="Country" />
                                        <x-admin.form.input type="text" x-bind:name="'addresses[' + index + '][country]'" x-model="address.country" class="mt-1" x-bind:id="'country_' + index" />
                                    </div>
                                    <div>
                                        <x-admin.form.label x-bind:for="'phone_' + index" value="Phone" />
                                        <x-admin.form.input type="text" x-bind:name="'addresses[' + index + '][phone]'" x-model="address.phone" class="mt-1" x-bind:id="'phone_' + index" />
                                    </div>
                                    <div class="flex items-center mt-6">
                                        <input type="checkbox" :name="'addresses[' + index + '][is_default]'" value="1" x-model="address.is_default" class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                                        <label class="ml-2 block text-sm text-gray-700 dark:text-gray-300">Set as Default</label>
                                    </div>
                                </div>
                            </div>
                        </template>
                        
                        <div x-show="addresses.length === 0" class="text-center py-8 text-gray-500 dark:text-gray-400 text-sm italic">
                            No addresses added yet. Click "Add Address" to add one.
                        </div>
                    </div>

                    <!-- Submit Button for Address Section -->
                    <div class="mt-6 flex justify-end">
                        <x-admin.actions.button type="submit" variant="primary">
                            Create User (with Addresses)
                        </x-admin.actions.button>
                     </div>
                </div>
            </x-admin.ui.card>
        </div>
    </form>

    <script>
        function userCreateForm() {
            return {
                addresses: [],
                
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
                        is_default: this.addresses.length === 0 
                    });
                },
                
                removeAddress(index) {
                    this.addresses.splice(index, 1);
                }
            }
        }
    </script>
@endsection
