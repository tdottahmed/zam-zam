@extends('layouts.admin')

@section('title', 'Add Customer')

@section('content')
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Add Customer</h1>
        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Create a customer account with profile and addresses.</p>
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
                            Account Information
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
                            <x-admin.form.group label="Email Address (Optional)" for="email">
                                <x-admin.form.input type="email" name="email" id="email" value="{{ old('email') }}" placeholder="Leave blank to auto-generate" />
                            </x-admin.form.group>
                        </div>

                        <!-- Password -->
                        <div class="col-span-2 md:col-span-1" x-data="{ show: false }">
                            <x-admin.form.group label="Password (Optional)" for="password">
                                <div class="relative">
                                    <x-admin.form.input x-bind:type="show ? 'text' : 'password'" name="password" id="password" placeholder="Leave blank to auto-generate default" />
                                    <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3 flex items-center z-10 text-gray-400 hover:text-gray-600 focus:outline-none focus:text-gray-600">
                                        <svg x-show="!show" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        <svg x-show="show" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: none;">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                        </svg>
                                    </button>
                                </div>
                            </x-admin.form.group>
                        </div>

                        <!-- Confirm Password -->
                        <div class="col-span-2 md:col-span-1" x-data="{ show: false }">
                            <x-admin.form.group label="Confirm Password (Optional)" for="password_confirmation">
                                <div class="relative">
                                    <x-admin.form.input x-bind:type="show ? 'text' : 'password'" name="password_confirmation" id="password_confirmation" />
                                    <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3 flex items-center z-10 text-gray-400 hover:text-gray-600 focus:outline-none focus:text-gray-600">
                                        <svg x-show="!show" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        <svg x-show="show" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: none;">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                        </svg>
                                    </button>
                                </div>
                            </x-admin.form.group>
                        </div>

                        <!-- Status (new customers can be approved or pending) -->
                        <div class="col-span-2 md:col-span-1">
                            <x-admin.form.group label="Account Status" for="status" required>
                                <x-admin.form.select name="status" id="status" :options="['pending' => 'Pending approval', 'approved' => 'Approved']" :selected="old('status', 'approved')" />
                            </x-admin.form.group>
                        </div>
                    </div>

                    <!-- Submit Button for Account Section -->
                    <div class="mt-6 flex justify-end">
                        <x-admin.actions.button type="submit" variant="primary">
                            Create Customer
                        </x-admin.actions.button>
                    </div>
                </div>
            </x-admin.ui.card>

            <!-- Contact & Profession -->
            <x-admin.ui.card>
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            Contact & Profession
                        </h2>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="col-span-2 md:col-span-1">
                            <x-admin.form.group label="Contact Number" for="profile_contact_no">
                                <x-admin.form.input type="text" name="profile[contact_no]" id="profile_contact_no" value="{{ old('profile.contact_no') }}" placeholder="Primary phone number" />
                            </x-admin.form.group>
                        </div>
                        <div class="col-span-2 md:col-span-1">
                            <x-admin.form.group label="Job Title" for="profile_job_title">
                                <x-admin.form.input type="text" name="profile[job_title]" id="profile_job_title" value="{{ old('profile.job_title') }}" placeholder="e.g. Procurement Manager" />
                            </x-admin.form.group>
                        </div>
                        <div class="col-span-2">
                            <x-admin.form.group label="Notes" for="profile_notes">
                                <textarea name="profile[notes]" id="profile_notes" rows="3" class="w-full rounded-lg border-gray-300 focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-20 transition shadow-sm">{{ old('profile.notes') }}</textarea>
                            </x-admin.form.group>
                        </div>
                    </div>
                </div>
            </x-admin.ui.card>

            <!-- Company Details -->
            <x-admin.ui.card>
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            Company Details
                        </h2>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="col-span-2 md:col-span-1">
                            <x-admin.form.group label="Company Name" for="profile_company_name">
                                <x-admin.form.input type="text" name="profile[company_name]" id="profile_company_name" value="{{ old('profile.company_name') }}" />
                            </x-admin.form.group>
                        </div>
                        <div class="col-span-2 md:col-span-1">
                            <x-admin.form.group label="Tax ID" for="profile_tax_id">
                                <x-admin.form.input type="text" name="profile[tax_id]" id="profile_tax_id" value="{{ old('profile.tax_id') }}" />
                            </x-admin.form.group>
                        </div>
                        <div class="col-span-2 md:col-span-1">
                            <x-admin.form.group label="Website" for="profile_website">
                                <x-admin.form.input type="url" name="profile[website]" id="profile_website" value="{{ old('profile.website') }}" placeholder="https://example.com" />
                            </x-admin.form.group>
                        </div>
                        <div class="col-span-2 md:col-span-1">
                            <x-admin.form.group label="Fax" for="profile_fax">
                                <x-admin.form.input type="text" name="profile[fax]" id="profile_fax" value="{{ old('profile.fax') }}" />
                            </x-admin.form.group>
                        </div>
                    </div>
                </div>
            </x-admin.ui.card>

            <!-- Bank Information -->
            <x-admin.ui.card>
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Bank Information
                        </h2>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="col-span-2 md:col-span-1">
                            <x-admin.form.group label="Bank Name" for="profile_bank_name">
                                <x-admin.form.input type="text" name="profile[bank_name]" id="profile_bank_name" value="{{ old('profile.bank_name') }}" />
                            </x-admin.form.group>
                        </div>
                        <div class="col-span-2 md:col-span-1">
                            <x-admin.form.group label="Bank Account No / Details" for="profile_bank_account_no">
                                <x-admin.form.input type="text" name="profile[bank_account_no]" id="profile_bank_account_no" value="{{ old('profile.bank_account_no') }}" />
                            </x-admin.form.group>
                        </div>
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
                                    <div class="col-span-2">
                                        <x-admin.form.label x-bind:for="'type_' + index" value="Address Type" />
                                        <select x-bind:name="'addresses[' + index + '][type]'" x-model="address.type" class="mt-1 w-full rounded-lg border-gray-300 focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-20 transition shadow-sm" x-bind:id="'type_' + index" required>
                                            <option value="Shipping">Shipping Address</option>
                                            <option value="Billing">Billing Address</option>
                                            <option value="Business">Business Address</option>
                                        </select>
                                    </div>
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
                            Create Customer
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
                        type: 'Shipping',
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
