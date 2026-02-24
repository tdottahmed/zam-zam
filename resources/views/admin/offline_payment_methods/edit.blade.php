@extends('layouts.admin')

@section('header')
    Edit Offline Payment Method
@endsection

@section('content')
    <div class="mb-6">
        <x-admin.actions.button href="{{ route('admin.offline-payment-methods.index') }}" variant="secondary" size="sm">
            &larr; Back to Payment Methods
        </x-admin.actions.button>
    </div>

    <form method="POST" action="{{ route('admin.offline-payment-methods.update', $offlinePaymentMethod) }}" x-data="paymentMethodForm()">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-6">
                <x-admin.ui.card>
                    <div class="p-6 space-y-6">
                        <h3 class="text-lg font-bold text-gray-900 border-b border-gray-100 pb-4">General Information</h3>
                        
                        <!-- Name -->
                        <div>
                            <x-admin.form.label for="name" value="Method Name" />
                            <x-admin.form.input id="name" name="name" :value="old('name', $offlinePaymentMethod->name)" required autofocus />
                            <x-admin.form.input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- Description -->
                        <div>
                            <x-admin.form.label for="description" value="Description / Instructions" />
                            <textarea id="description" name="description" rows="3" class="w-full rounded-lg border-gray-300 focus:border-[#C41E3A] focus:ring focus:ring-[#C41E3A] focus:ring-opacity-20 transition shadow-sm text-sm">{{ old('description', $offlinePaymentMethod->description) }}</textarea>
                            <x-admin.form.input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>
                    </div>
                </x-admin.ui.card>

                <x-admin.ui.card>
                    <div class="p-6">
                        <div class="flex justify-between items-center border-b border-gray-100 pb-4 mb-6">
                            <div>
                                <h3 class="text-lg font-bold text-gray-900">Required Fields</h3>
                                <p class="text-sm text-gray-500 mt-1">Define fields customers must fill when using this method.</p>
                            </div>
                            <button type="button" @click="addField()" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-full shadow-sm text-white bg-[#C41E3A] hover:bg-[#a01830] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#C41E3A]">
                                + Add Field
                            </button>
                        </div>
                        
                        <div class="space-y-4">
                            <template x-if="fields.length === 0">
                                <div class="text-center py-6 bg-gray-50 rounded-lg border border-dashed border-gray-200">
                                    <p class="text-sm text-gray-500">No required fields defined. Customer will only see instructions.</p>
                                </div>
                            </template>

                            <template x-for="(field, index) in fields" :key="index">
                                <div class="flex flex-col gap-4 p-4 bg-gray-50 rounded-xl border border-gray-200 relative group transition-all">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-xs font-semibold text-gray-700 mb-1">Field Label</label>
                                                <input type="text" x-model="field.label" @input="updateName(index)" :name="'required_fields['+index+'][label]'" class="w-full rounded-lg border-gray-300 focus:border-[#C41E3A] focus:ring focus:ring-[#C41E3A] focus:ring-opacity-20 text-sm py-2" placeholder="e.g. Transaction ID" required>
                                            </div>
                                            <div>
                                                <label class="block text-xs font-semibold text-gray-700 mb-1">Type</label>
                                                <select x-model="field.type" :name="'required_fields['+index+'][type]'" class="w-full rounded-lg border-gray-300 focus:border-[#C41E3A] focus:ring focus:ring-[#C41E3A] focus:ring-opacity-20 text-sm py-2">
                                                    <option value="text">Text / String</option>
                                                    <option value="number">Number</option>
                                                    <option value="file">File / Image Document</option>
                                                </select>
                                            </div>
                                            <div>
                                                <label class="block text-xs font-semibold text-gray-700 mb-1">Field Name (Internal)</label>
                                                <input type="text" x-model="field.name" :name="'required_fields['+index+'][name]'" class="w-full rounded-lg border-gray-300 bg-gray-100 text-gray-500 focus:border-[#C41E3A] focus:ring focus:ring-[#C41E3A] focus:ring-opacity-20 text-sm py-2" readonly placeholder="e.g. transaction_id">
                                            </div>
                                            <div class="flex items-center mt-6">
                                                <label class="inline-flex items-center cursor-pointer">
                                                    <input type="checkbox" x-model="field.is_required" :name="'required_fields['+index+'][is_required]'" value="1" class="rounded border-gray-300 text-[#C41E3A] shadow-sm focus:ring-[#C41E3A]" :checked="field.is_required == 1 || field.is_required == true">
                                                    <span class="ml-2 text-sm text-gray-700 font-medium">Is Required?</span>
                                                </label>
                                                <input type="hidden" :name="'required_fields['+index+'][is_required]'" x-bind:value="field.is_required ? '1' : '0'">
                                            </div>
                                        </div>
                                        <button type="button" @click="removeField(index)" class="text-red-500 hover:text-red-700 p-2 hover:bg-red-50 rounded-lg ml-4 mt-6 transition-colors" title="Remove Field">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </x-admin.ui.card>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <x-admin.ui.card>
                    <div class="p-6">
                        <h3 class="text-sm font-bold text-gray-900 border-b border-gray-100 pb-3 mb-4">Status</h3>
                        <label for="is_active" class="inline-flex items-center cursor-pointer mb-6">
                            <input id="is_active" type="checkbox" class="rounded border-gray-300 text-[#C41E3A] shadow-sm focus:ring-[#C41E3A]" name="is_active" value="1" {{ old('is_active', $offlinePaymentMethod->is_active) ? 'checked' : '' }}>
                            <span class="ml-2 text-sm text-gray-700 font-medium">Active</span>
                        </label>
                        
                        <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-3 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-[#C41E3A] hover:bg-[#a01830] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#C41E3A] transition-colors">
                            Update Method
                        </button>
                    </div>
                </x-admin.ui.card>
            </div>
        </div>
    </form>

    <script>
        function paymentMethodForm() {
            return {
                fields: @json(old('required_fields', $offlinePaymentMethod->required_fields ?? [])),
                addField() {
                    this.fields.push({ label: '', name: '', type: 'text', is_required: true });
                },
                removeField(index) {
                    this.fields.splice(index, 1);
                },
                updateName(index) {
                    this.fields[index].name = this.fields[index].label
                        .toLowerCase()
                        .replace(/[^a-z0-9]+/g, '_')
                        .replace(/(^_|_$)/g, '');
                }
            }
        }
    </script>
@endsection
