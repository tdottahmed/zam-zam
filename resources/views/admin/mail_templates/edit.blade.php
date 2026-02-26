@extends('layouts.admin')

@section('header')
    Edit Mail Template
@endsection

@section('content')
    <div class="mx-auto max-w-8xl">
        <div class="mb-6 flex items-center justify-between">
            <x-admin.actions.button href="{{ route('admin.mail-templates.index') }}" variant="secondary" size="sm">
                &larr; Back to Templates
            </x-admin.actions.button>
            <form method="POST" action="{{ route('admin.mail-templates.destroy', $mailTemplate) }}" onsubmit="return confirm('Delete this template? This cannot be undone.')">
                @csrf
                @method('DELETE')
                <x-admin.actions.button type="submit" variant="danger" size="sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    Delete Template
                </x-admin.actions.button>
            </form>
        </div>

        <x-admin.ui.card>
            <form method="POST" action="{{ route('admin.mail-templates.update', $mailTemplate) }}">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    {{-- Active Status Toggle --}}
                    <div class="flex items-center justify-between">
                        <div>
                            <x-admin.form.label for="is_active" value="Status" />
                            <p class="text-sm text-gray-500">Enable or disable this template</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="is_active" id="is_active" class="sr-only peer" value="1" {{ old('is_active', $mailTemplate->is_active) ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-focus:ring-4 peer-focus:ring-[#C41E3A]/30 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#C41E3A]"></div>
                        </label>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Template Type --}}
                        <div>
                            <x-admin.form.label for="type" value="Template Type" />
                            <x-admin.form.input
                                id="type"
                                name="type"
                                value="{{ old('type', $mailTemplate->type) }}"
                                required
                                readonly
                                class="bg-gray-50 cursor-not-allowed"
                            />
                            <p class="mt-1 text-xs text-gray-500">Type cannot be changed after creation.</p>
                            <x-admin.form.input-error :messages="$errors->get('type')" class="mt-2" />
                        </div>

                        {{-- Subject --}}
                        <div>
                            <x-admin.form.label for="subject" value="Subject Line" />
                            <x-admin.form.input
                                id="subject"
                                name="subject"
                                value="{{ old('subject', $mailTemplate->subject) }}"
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
                            :value="old('content', $mailTemplate->content)"
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
                            Update Template
                        </x-admin.actions.button>
                    </div>
                </div>
            </form>
        </x-admin.ui.card>
    </div>
@endsection
