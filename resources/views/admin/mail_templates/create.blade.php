@extends('layouts.admin')

@section('header')
    Add Mail Template
@endsection

@section('content')
    <div class="mx-auto max-w-8xl">
        <div class="mb-6">
            <x-admin.actions.button href="{{ route('admin.mail-templates.index') }}" variant="secondary" size="sm">
                &larr; Back to Templates
            </x-admin.actions.button>
        </div>

        <x-admin.ui.card>
            <form method="POST" action="{{ route('admin.mail-templates.store') }}">
                @csrf

                <div class="space-y-6">
                    {{-- Active Status Toggle --}}
                    <div class="flex items-center justify-between">
                        <div>
                            <x-admin.form.label for="is_active" value="Status" />
                            <p class="text-sm text-gray-500">Enable or disable this template</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="is_active" id="is_active" class="sr-only peer" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-focus:ring-4 peer-focus:ring-[#C41E3A]/30 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#C41E3A]"></div>
                        </label>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Template Type --}}
                        <div>
                            <x-admin.form.label for="type" value="Template Type" />
                            <select id="type" name="type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#C41E3A] focus:ring focus:ring-[#C41E3A] focus:ring-opacity-50 text-sm" required>
                                <option value="" disabled selected>Select a type</option>
                                <option value="invoice" {{ old('type') == 'invoice' ? 'selected' : '' }}>Invoice</option>
                                <option value="credit_note" {{ old('type') == 'credit_note' ? 'selected' : '' }}>Credit Note</option>
                                <option value="order_confirmation" {{ old('type') == 'order_confirmation' ? 'selected' : '' }}>Order Confirmation</option>
                                <option value="password_change" {{ old('type') == 'password_change' ? 'selected' : '' }}>Password Change</option>
                            </select>
                            <p class="mt-1 text-xs text-gray-500">System event that triggers this email.</p>
                            <x-admin.form.input-error :messages="$errors->get('type')" class="mt-2" />
                        </div>

                        {{-- Subject --}}
                        <div>
                            <x-admin.form.label for="subject" value="Subject Line" />
                            <x-admin.form.input
                                id="subject"
                                name="subject"
                                value="{{ old('subject') }}"
                                placeholder="e.g. Your Invoice is Ready"
                                required
                            />
                            <p class="mt-1 text-xs text-gray-500">The subject line of the email.</p>
                            <x-admin.form.input-error :messages="$errors->get('subject')" class="mt-2" />
                        </div>
                    </div>

                    {{-- Content --}}
                    <div>
                        <x-admin.form.label for="content" value="Email Content" />
                        <x-admin.form.editor
                            name="content"
                            :value="old('content', '')"
                            height="h-72"
                        />
                        <p class="mt-2 text-xs text-gray-500">
                            Use placeholders like <code class="bg-gray-100 px-1 py-0.5 rounded">{user_name}</code>, <code class="bg-gray-100 px-1 py-0.5 rounded">{order_number}</code> as needed.
                        </p>
                        <x-admin.form.input-error :messages="$errors->get('content')" class="mt-2" />
                    </div>

                    {{-- Actions --}}
                    <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                        <x-admin.actions.button href="{{ route('admin.mail-templates.index') }}" variant="secondary">
                            Cancel
                        </x-admin.actions.button>
                        <x-admin.actions.button type="submit" variant="primary">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Create Template
                        </x-admin.actions.button>
                    </div>
                </div>
            </form>
        </x-admin.ui.card>
    </div>
@endsection
