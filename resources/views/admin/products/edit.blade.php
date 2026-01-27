@extends('layouts.admin')

@section('header')
    Edit Product
@endsection

@section('content')
    <div class="max-w-3xl mx-auto">
        
        <div class="mb-6">
            <x-admin.actions.button href="{{ route('admin.products.index') }}" variant="secondary" size="sm">
                &larr; Back to Products
            </x-admin.actions.button>
        </div>

        <x-admin.ui.card>
            <form method="POST" action="{{ route('admin.products.update', $product) }}">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <!-- Name -->
                    <div>
                        <x-admin.form.label for="name" value="Product Name" />
                        <x-admin.form.input id="name" name="name" :value="old('name', $product->name)" required autofocus />
                        <x-admin.form.input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Box Price -->
                        <div>
                            <x-admin.form.label for="box_price" value="Box Price ($)" />
                            <x-admin.form.input id="box_price" name="box_price" type="number" step="0.01" :value="old('box_price', $product->box_price)" required />
                             <x-admin.form.input-error :messages="$errors->get('box_price')" class="mt-2" />
                        </div>

                        <!-- Unit Price -->
                        <div>
                            <x-admin.form.label for="unit_price" value="Unit Price ($)" />
                            <x-admin.form.input id="unit_price" name="unit_price" type="number" step="0.01" :value="old('unit_price', $product->unit_price)" required />
                             <x-admin.form.input-error :messages="$errors->get('unit_price')" class="mt-2" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                         <!-- Buying Price -->
                         <div>
                            <x-admin.form.label for="buying_price" value="Buying Price ($)" />
                            <x-admin.form.input id="buying_price" name="buying_price" type="number" step="0.01" :value="old('buying_price', $product->buying_price)" />
                             <x-admin.form.input-error :messages="$errors->get('buying_price')" class="mt-2" />
                        </div>
                    </div>

                     <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Weight -->
                        <div>
                            <x-admin.form.label for="weight" value="Weight (e.g. 248GM)" />
                            <x-admin.form.input id="weight" name="weight" :value="old('weight', $product->weight)" />
                             <x-admin.form.input-error :messages="$errors->get('weight')" class="mt-2" />
                        </div>

                        <!-- Packaging -->
                        <div>
                            <x-admin.form.label for="packaging" value="Packaging (e.g. BOX 24)" />
                            <x-admin.form.input id="packaging" name="packaging" :value="old('packaging', $product->packaging)" />
                             <x-admin.form.input-error :messages="$errors->get('packaging')" class="mt-2" />
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <x-admin.actions.button type="submit" variant="primary">
                            Update Product
                        </x-admin.actions.button>
                    </div>
                </div>
            </form>
        </x-admin.ui.card>
    </div>
@endsection
