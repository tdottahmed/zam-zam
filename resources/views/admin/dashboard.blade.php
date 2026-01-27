@extends('layouts.admin')

@section('header')
    Component Styleguide
@endsection

@section('content')
    
    <x-admin.ui.section-header>
        Styleguide & Components
        <x-slot:description>
            A showcase of all reusable admin components.
        </x-slot:description>
        <x-slot:actions>
            <x-admin.actions.button variant="outline" size="sm">Download Documentation</x-admin.actions.button>
            <x-admin.actions.button variant="primary" size="sm">Create New</x-admin.actions.button>
        </x-slot:actions>
    </x-admin.ui.section-header>

    <div class="space-y-8">
        
        <!-- Typography & Cards -->
        <x-admin.ui.card>
            <h3 class="text-lg font-bold mb-4">Cards & Typography</h3>
            <p class="text-gray-600 mb-4">This is a standard card component. It wraps content in a white box with shadow.</p>
            <div class="flex flex-wrap gap-2">
                <x-admin.ui.badge type="success">Success Badge</x-admin.ui.badge>
                <x-admin.ui.badge type="warning">Warning Badge</x-admin.ui.badge>
                <x-admin.ui.badge type="error">Error Badge</x-admin.ui.badge>
                <x-admin.ui.badge type="info">Info Badge</x-admin.ui.badge>
                <x-admin.ui.badge type="neutral">Neutral Badge</x-admin.ui.badge>
            </div>
        </x-admin.ui.card>

        <!-- Buttons -->
        <x-admin.ui.card>
            <h3 class="text-lg font-bold mb-4">Buttons</h3>
            <div class="flex flex-wrap gap-4 items-center mb-6">
                <x-admin.actions.button variant="primary">Primary</x-admin.actions.button>
                <x-admin.actions.button variant="secondary">Secondary</x-admin.actions.button>
                <x-admin.actions.button variant="danger">Danger</x-admin.actions.button>
                <x-admin.actions.button variant="outline">Outline</x-admin.actions.button>
                <x-admin.actions.button variant="ghost">Ghost</x-admin.actions.button>
            </div>
            <div class="flex flex-wrap gap-4 items-center">
                <x-admin.actions.button size="sm">Small</x-admin.actions.button>
                <x-admin.actions.button size="md">Medium</x-admin.actions.button>
                <x-admin.actions.button size="lg">Large</x-admin.actions.button>
                <x-admin.actions.button disabled>Disabled</x-admin.actions.button>
            </div>
        </x-admin.ui.card>

        <!-- Forms -->
        <x-admin.ui.card>
            <h3 class="text-lg font-bold mb-4">Form Elements</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <x-admin.form.label>Text Input</x-admin.form.label>
                    <x-admin.form.input name="example_text" placeholder="Enter text..." />
                </div>
                
                <div>
                    <x-admin.form.label>Input with Error</x-admin.form.label>
                    <x-admin.form.input name="example_error" value="Invalid Value" error="This field is required." />
                </div>

                <div>
                    <x-admin.form.label>Select Option</x-admin.form.label>
                    <x-admin.form.select name="example_select" :options="['1' => 'Option One', '2' => 'Option Two']" />
                </div>

                <div>
                    <x-admin.form.label>Checkbox & Toggles</x-admin.form.label>
                    <div class="flex items-center space-x-6 mt-2">
                        <x-admin.form.checkbox label="Remember Me" />
                        <x-admin.form.toggle label="Enable Notifications" enabled="true" />
                    </div>
                </div>

                <div class="md:col-span-2">
                    <x-admin.form.label>Textarea</x-admin.form.label>
                    <x-admin.form.textarea placeholder="Enter description..." rows="3" />
                </div>
            </div>
        </x-admin.ui.card>

        <!-- Advanced Forms -->
        <x-admin.ui.card>
            <h3 class="text-lg font-bold mb-4">Advanced Inputs</h3>
            <div class="space-y-6">
                <div>
                     <x-admin.form.select-search 
                        name="country" 
                        label="Searchable Country Select" 
                        :options="['US' => 'United States', 'CA' => 'Canada', 'GB' => 'United Kingdom', 'BD' => 'Bangladesh', 'IN' => 'India']" 
                    />
                </div>
                
                <div>
                    <x-admin.form.editor name="content" label="Rich Text Content" />
                </div>
            </div>
        </x-admin.ui.card>

        <!-- Tables -->
        <x-admin.ui.card>
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold">Data Table with Search</h3>
                <div class="flex gap-2">
                     <x-admin.actions.icon-button>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                     </x-admin.actions.icon-button>
                </div>
            </div>
            
            <x-admin.ui.table>
                <x-slot:search>
                    <div class="flex items-center gap-4 w-full">
                        <div class="flex-1 max-w-xs">
                           <x-admin.form.input name="search" placeholder="Search users..." />
                        </div>
                        <div class="w-48">
                            <x-admin.form.select name="role" :options="['admin' => 'Admin', 'user' => 'User']" placeholder="Filter Role" />
                        </div>
                    </div>
                </x-slot:search>

                <x-slot:head>
                    <x-admin.ui.th>User</x-admin.ui.th>
                    <x-admin.ui.th>Status</x-admin.ui.th>
                    <x-admin.ui.th>Actions</x-admin.ui.th>
                </x-slot:head>
                <x-slot:body>
                    <tr>
                        <x-admin.ui.td>John Doe</x-admin.ui.td>
                        <x-admin.ui.td><x-admin.ui.badge type="success">Active</x-admin.ui.badge></x-admin.ui.td>
                        <x-admin.ui.td>
                            <div class="flex gap-2">
                                <x-admin.actions.button size="sm" variant="secondary">Edit</x-admin.actions.button>
                                <x-admin.actions.icon-button variant="danger" size="sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </x-admin.actions.icon-button>
                            </div>
                        </x-admin.ui.td>
                    </tr>
                </x-slot:body>
            </x-admin.ui.table>
        </x-admin.ui.card>

        <!-- Modals -->
        <x-admin.ui.card>
             <h3 class="text-lg font-bold mb-4">Interactive Elements</h3>
             
             <div x-data>
                 <x-admin.actions.button @click="$dispatch('open-modal', 'demo-modal')">Open Demo Modal</x-admin.actions.button>
             </div>

             <x-admin.ui.modal name="demo-modal" title="Example Modal">
                 <p class="text-sm text-gray-500">
                     This is a reusable modal component. You can pass content here.
                 </p>
                 <x-slot:footer>
                     <x-admin.actions.button variant="primary" @click="show = false">Confirm</x-admin.actions.button>
                     <x-admin.actions.button variant="secondary" @click="show = false" class="mr-3">Cancel</x-admin.actions.button>
                 </x-slot:footer>
             </x-admin.ui.modal>
        </x-admin.ui.card>

    </div>

@endsection
