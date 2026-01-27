@extends('layouts.admin')

@section('header')
    Add Product
@endsection

@section('content')
    <div class="mb-6">
        <x-admin.actions.button href="{{ route('admin.products.index') }}" variant="secondary" size="sm">
            &larr; Back to Products
        </x-admin.actions.button>
    </div>
    <x-admin.ui.card>
        <form method="POST" action="{{ route('admin.products.store') }}">
            @csrf

            <div class="space-y-6">
                <!-- Product Code -->
                <div>
                    <x-admin.form.label for="product_code" value="Product Code :" />
                    <x-admin.form.input id="product_code" name="product_code" :value="old('product_code')" />
                    <x-admin.form.input-error :messages="$errors->get('product_code')" class="mt-2" />
                </div>

                <!-- Product Name -->
                <div>
                    <x-admin.form.label for="name" value="Product Name :" />
                    <x-admin.form.input id="name" name="name" :value="old('name')" required />
                    <x-admin.form.input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Weight -->
                    <div>
                        <x-admin.form.label for="weight" value="Weight(GM/ML):" />
                        <x-admin.form.input id="weight" name="weight" :value="old('weight')" />
                            <x-admin.form.input-error :messages="$errors->get('weight')" class="mt-2" />
                    </div>

                    <!-- PC's In (CTN/BAG) -->
                    <div>
                        <x-admin.form.label for="pcs_in_ctn" value="PC's In (CTN/BAG) :" />
                        <x-admin.form.input id="pcs_in_ctn" name="pcs_in_ctn" :value="old('pcs_in_ctn')" />
                            <x-admin.form.input-error :messages="$errors->get('pcs_in_ctn')" class="mt-2" />
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Box Price -->
                    <div>
                        <x-admin.form.label for="box_price" value="Box Price :" />
                        <x-admin.form.input id="box_price" name="box_price" type="number" step="0.01" :value="old('box_price')" />
                            <x-admin.form.input-error :messages="$errors->get('box_price')" class="mt-2" />
                    </div>

                    <!-- Unit Price -->
                    <div>
                        <x-admin.form.label for="unit_price" value="Unit Price :" />
                        <x-admin.form.input id="unit_price" name="unit_price" type="number" step="0.01" :value="old('unit_price')" />
                            <x-admin.form.input-error :messages="$errors->get('unit_price')" class="mt-2" />
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Tax -->
                    <div>
                        <x-admin.form.label for="tax" value="Tax :" />
                        <x-admin.form.input id="tax" name="tax" type="number" step="0.01" :value="old('tax')" />
                            <x-admin.form.input-error :messages="$errors->get('tax')" class="mt-2" />
                    </div>

                        <!-- Buying Price -->
                        <div>
                        <x-admin.form.label for="buying_price" value="Buying Price :" />
                        <x-admin.form.input id="buying_price" name="buying_price" type="number" step="0.01" :value="old('buying_price')" />
                            <x-admin.form.input-error :messages="$errors->get('buying_price')" class="mt-2" />
                    </div>
                </div>
                
                <!-- Notes -->
                <div>
                        <x-admin.form.label for="notes" value="notes :" />
                        <x-admin.form.textarea id="notes" name="notes" rows="3">{{ old('notes') }}</x-admin.form.textarea>
                        <x-admin.form.input-error :messages="$errors->get('notes')" class="mt-2" />
                </div>

                <div class="flex items-center justify-start mt-8">
                    <x-admin.actions.button type="submit" variant="primary" class="w-24">
                        ADD
                    </x-admin.actions.button>
                </div>
            </div>
        </form>
    </x-admin.ui.card>
@endsection
