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
        <form method="POST" action="{{ route('admin.products.store') }}" id="product-form">
            @csrf

            <div class="space-y-8">
                <!-- Section 1: Basic Information -->
                <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm">
                    <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">Basic Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Product Name -->
                        <div class="md:col-span-2">
                            <x-admin.form.label for="name" value="Product Name *" />
                            <x-admin.form.input id="name" name="name" :value="old('name')" required placeholder="e.g. Premium Chocolate Biscuit" />
                            <x-admin.form.input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- Category -->
                        <div>
                            <x-admin.form.select-search 
                                name="category_id" 
                                label="Category" 
                                :options="$categories->pluck('name', 'id')" 
                                :selected="old('category_id')"
                                placeholder="Select Category"
                            />
                            <x-admin.form.input-error :messages="$errors->get('category_id')" class="mt-2" />
                        </div>

                        <!-- Brand -->
                        <div>
                            <x-admin.form.select-search 
                                name="brand_id" 
                                label="Brand" 
                                :options="$brands->pluck('name', 'id')" 
                                :selected="old('brand_id')"
                                placeholder="Select Brand"
                            />
                            <x-admin.form.input-error :messages="$errors->get('brand_id')" class="mt-2" />
                        </div>

                        <!-- Product Code -->
                        <div>
                            <x-admin.form.label for="product_code" value="Product Code (SKU)" />
                            <x-admin.form.input id="product_code" name="product_code" :value="old('product_code')" placeholder="e.g. PCB-001" />
                            <x-admin.form.input-error :messages="$errors->get('product_code')" class="mt-2" />
                        </div>
                        
                         <!-- Tax Category -->
                         <div>
                           <x-admin.form.select-search 
                                name="tax_id" 
                                label="Tax Category" 
                                :options="$taxes->pluck('name', 'id')" 
                                :selected="old('tax_id')"
                                placeholder="Select Tax Category"
                            />
                            <x-admin.form.input-error :messages="$errors->get('tax_id')" class="mt-2" />
                        </div>
                    </div>
                </div>

                <!-- Section 2: Packaging & Stock Management -->
                <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm">
                    <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">Packaging & Stock Management</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                         <!-- Unit Select -->
                        <div>
                            <x-admin.form.select-search 
                                name="unit_id" 
                                label="Unit" 
                                :options="$units->pluck('code', 'id')" 
                                :selected="old('unit_id')"
                                placeholder="Select Unit"
                            />
                            <x-admin.form.input-error :messages="$errors->get('unit_id')" class="mt-2" />
                        </div>

                        <!-- PC's In Carton -->
                        <div>
                            <x-admin.form.label for="pcs_in_ctn" value="Units per Carton *" />
                            <x-admin.form.input id="pcs_in_ctn" name="pcs_in_ctn" type="number" min="1" :value="old('pcs_in_ctn', 1)" required />
                            <p class="text-xs text-gray-500 mt-1">master_packaging</p>
                            <x-admin.form.input-error :messages="$errors->get('pcs_in_ctn')" class="mt-2" />
                        </div>

                        <!-- Initial Stock -->
                        <div>
                            <x-admin.form.label for="quantity" value="Initial Stock Quantity" />
                            <x-admin.form.input id="quantity" name="quantity" type="number" :value="old('quantity', 0)" />
                            <p class="text-xs text-gray-500 mt-1">Current stock on hand.</p>
                            <x-admin.form.input-error :messages="$errors->get('quantity')" class="mt-2" />
                        </div>

                        <!-- Alert Quantity -->
                        <div>
                            <x-admin.form.label for="alert_quantity" value="Low Stock Alert Level" />
                            <x-admin.form.input id="alert_quantity" name="alert_quantity" type="number" :value="old('alert_quantity')" placeholder="e.g. 10" />
                            <p class="text-xs text-gray-500 mt-1">Get notified when stock drops below this.</p>
                            <x-admin.form.input-error :messages="$errors->get('alert_quantity')" class="mt-2" />
                        </div>
                    </div>
                </div>

                <!-- Section 3: Wholesale Pricing Engine -->
                <div class="bg-blue-50 p-6 rounded-lg border border-blue-100 shadow-sm">
                    <div class="flex items-center justify-between mb-4 border-b border-blue-200 pb-2">
                         <h3 class="text-lg font-medium text-blue-900">Wholesale Pricing Engine</h3>
                         <span class="text-xs text-blue-700 bg-white px-3 py-1 rounded-full border border-blue-200">Default Margin: <span id="margin_display" class="font-bold">{{ $defaultProfitMargin }}</span>%</span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- COST SIDE -->
                        <div class="space-y-4">
                            <h4 class="font-semibold text-gray-700 uppercase text-xs tracking-wider">Cost Price (Buying)</h4>
                            
                            <div>
                                <x-admin.form.label for="buying_price" value="Cost per Unit" />
                                <div class="relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">$</span>
                                    </div>
                                    <x-admin.form.input id="cost_per_unit" name="buying_price" type="number" step="0.01" :value="old('buying_price')" class="pl-7 bg-white" placeholder="0.00" />
                                </div>
                            </div>

                            <div class="relative">
                                <div class="absolute inset-0 flex items-center" aria-hidden="true">
                                    <div class="w-full border-t border-gray-300"></div>
                                </div>
                                <div class="relative flex justify-center">
                                    <span class="px-2 bg-blue-50 text-xs text-gray-500">OR</span>
                                </div>
                            </div>

                            <div>
                                <x-admin.form.label for="cost_per_carton" value="Cost per Carton" />
                                <div class="relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">$</span>
                                    </div>
                                    <x-admin.form.input id="cost_per_carton" type="number" step="0.01" class="pl-7 bg-white" placeholder="0.00" />
                                </div>
                            </div>
                        </div>

                        <!-- SELLING SIDE -->
                        <div class="space-y-4 border-l pl-8 border-blue-200">
                             <h4 class="font-semibold text-gray-700 uppercase text-xs tracking-wider">Selling Price (Wholesale)</h4>
                            
                             <div>
                                <x-admin.form.label for="box_price" value="Price per Carton" />
                                <div class="relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">$</span>
                                    </div>
                                    <x-admin.form.input id="box_price" name="box_price" type="number" step="0.01" :value="old('box_price')" class="pl-7 bg-green-50 font-bold text-green-700 border-green-300 focus:border-green-500 focus:ring-green-500" placeholder="0.00" />
                                </div>
                                <p class="text-xs text-green-600 mt-1" id="profit_display">Profit: $0.00 (0%)</p>
                                <x-admin.form.input-error :messages="$errors->get('box_price')" class="mt-2" />
                            </div>

                            <div>
                                <x-admin.form.label for="unit_price" value="Price per Unit (Ref)" />
                                <div class="relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">$</span>
                                    </div>
                                    <x-admin.form.input id="unit_price" name="unit_price" type="number" step="0.01" :value="old('unit_price')" class="pl-7 bg-gray-50 text-gray-600" readonly />
                                </div>
                                <x-admin.form.input-error :messages="$errors->get('unit_price')" class="mt-2" />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 4: Additional -->
                <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm">
                    <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">Additional Details</h3>
                    <div>
                         <x-admin.form.label for="notes" value="Notes / Description" />
                         <x-admin.form.textarea id="notes" name="notes" rows="3" placeholder="Add product details...">{{ old('notes') }}</x-admin.form.textarea>
                         <x-admin.form.input-error :messages="$errors->get('notes')" class="mt-2" />
                    </div>
                </div>

                <div class="flex items-center justify-end mt-8 border-t pt-6">
                    <x-admin.actions.button type="submit" variant="primary" size="lg" class="px-8">
                        Create Product
                    </x-admin.actions.button>
                </div>
            </div>
        </form>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Elements
                const els = {
                    pcsInCtn: document.getElementById('pcs_in_ctn'),
                    costUnit: document.getElementById('cost_per_unit'),
                    costCarton: document.getElementById('cost_per_carton'),
                    sellCarton: document.getElementById('box_price'),
                    sellUnit: document.getElementById('unit_price'),
                    profitDisplay: document.getElementById('profit_display')
                };

                const defaultMargin = {{ $defaultProfitMargin }};

                // State
                let state = {
                    pcs: 1,
                    costUnit: 0,
                    margin: defaultMargin
                };

                function updateState() {
                    state.pcs = parseFloat(els.pcsInCtn.value) || 1;
                    state.costUnit = parseFloat(els.costUnit.value) || 0;
                }

                function calculateFromUnitCost() {
                    updateState();
                    // Update Carton Cost
                    const cartonCost = state.costUnit * state.pcs;
                    els.costCarton.value = cartonCost > 0 ? cartonCost.toFixed(2) : '';

                    // Calculate Selling Prices (Carton)
                    const sellCarton = cartonCost + (cartonCost * (state.margin / 100));
                    els.sellCarton.value = sellCarton > 0 ? sellCarton.toFixed(2) : '';

                    // Calculate Selling Prices (Unit)
                    const sellUnit = sellCarton / state.pcs;
                    els.sellUnit.value = sellUnit > 0 ? sellUnit.toFixed(2) : '';

                    updateProfit();
                }

                function calculateFromCartonCost() {
                    const cartonCost = parseFloat(els.costCarton.value) || 0;
                    state.pcs = parseFloat(els.pcsInCtn.value) || 1;
                    
                    // Update Unit Cost
                    const unitCost = cartonCost / state.pcs;
                    els.costUnit.value = unitCost > 0 ? unitCost.toFixed(2) : '';

                    calculateFromUnitCost(); // Cascade
                }

                function updateProfit() {
                    const cost = parseFloat(els.costCarton.value) || 0;
                    const sell = parseFloat(els.sellCarton.value) || 0;

                    if (cost > 0 && sell > 0) {
                        const profit = sell - cost;
                        const marginPercent = ((profit / cost) * 100).toFixed(1);
                        els.profitDisplay.textContent = `Profit: $${profit.toFixed(2)} (${marginPercent}%)`;
                    } else {
                        els.profitDisplay.textContent = 'Profit: $0.00 (0%)';
                    }
                }

                // Listeners
                els.pcsInCtn.addEventListener('input', calculateFromUnitCost); // Recalculate totals if pack size changes
                els.costUnit.addEventListener('input', calculateFromUnitCost);
                els.costCarton.addEventListener('input', calculateFromCartonCost);
                
                // Manual override of selling price
                els.sellCarton.addEventListener('input', function() {
                    const sellCarton = parseFloat(this.value) || 0;
                    state.pcs = parseFloat(els.pcsInCtn.value) || 1;
                    
                    // Update Unit Sell Price
                    const sellUnit = sellCarton / state.pcs;
                    els.sellUnit.value = sellUnit > 0 ? sellUnit.toFixed(2) : '';

                    updateProfit();
                });
            });
        </script>
    </x-admin.ui.card>
@endsection
