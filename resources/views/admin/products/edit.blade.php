@extends('layouts.admin')

@section('header')
    Edit Product
@endsection

@section('content')        
    <div class="mb-6">
        <x-admin.actions.button href="{{ route('admin.products.index') }}" variant="secondary" size="sm">
            &larr; Back to Products
        </x-admin.actions.button>
    </div>
    <x-admin.ui.card>
        <form method="POST" action="{{ route('admin.products.update', $product) }}" id="product-form" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="space-y-8">
                <!-- Section 1: Basic Information -->
                <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm">
                    <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">Basic Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Product Name -->
                        <div class="md:col-span-2">
                            <x-admin.form.label for="name" value="Product Name *" />
                            <x-admin.form.input id="name" name="name" :value="old('name', $product->name)" required />
                            <x-admin.form.input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- Slug -->
                        <div class="md:col-span-2">
                            <x-admin.form.label for="slug" value="Slug (URL Friendly)" />
                            <x-admin.form.input id="slug" name="slug" :value="old('slug', $product->slug)" placeholder="Auto-generated from name" />
                            <p class="text-xs text-gray-500 mt-1">Leave blank to auto-generate.</p>
                            <x-admin.form.input-error :messages="$errors->get('slug')" class="mt-2" />
                        </div>

                        <!-- Category -->
                        <div>
                            <x-admin.form.select-search 
                                name="category_id" 
                                label="Category" 
                                :options="$categories->pluck('name', 'id')" 
                                :selected="old('category_id', $product->category_id)"
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
                                :selected="old('brand_id', $product->brand_id)"
                                placeholder="Select Brand"
                            />
                            <x-admin.form.input-error :messages="$errors->get('brand_id')" class="mt-2" />
                        </div>

                        <!-- Product Image -->
                        <div class="md:col-span-2">
                             <x-admin.form.file-upload 
                                name="image" 
                                label="Product Image" 
                                :preview="$product->image ? asset('storage/' . $product->image) : null" 
                             />
                             <x-admin.form.input-error :messages="$errors->get('image')" class="mt-2" />
                        </div>

                        <!-- Product Code -->
                        <div>
                            <x-admin.form.label for="product_code" value="Product Code (SKU)" />
                            <div class="mt-1 flex rounded-md shadow-sm">
                                <div class="relative flex-grow focus-within:z-10">
                                    <x-admin.form.input 
                                        id="product_code" 
                                        name="product_code" 
                                        :value="old('product_code', $product->product_code)" 
                                        placeholder="e.g. PCB-001" 
                                        class="rounded-r-none" 
                                    />
                                </div>
                                <button 
                                    type="button" 
                                    id="generate_sku_btn" 
                                    class="-ml-px relative inline-flex items-center space-x-2 px-4 py-2 border border-gray-300 text-sm font-medium rounded-r-md text-gray-700 bg-gray-50 hover:bg-gray-100 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200"
                                >
                                    <svg class="h-4 w-4 text-gray-400 group-hover:text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                                    </svg>
                                    <span>Generate</span>
                                </button>
                            </div>
                            <p class="mt-1 text-xs text-gray-500">Leave blank to auto-generate or click Generate.</p>
                            <x-admin.form.input-error :messages="$errors->get('product_code')" class="mt-2" />
                        </div>

                         <!-- Tax Category -->
                         <div>
                            <x-admin.form.label for="tax_id" value="Tax Category" />
                            <select id="tax_id" name="tax_id" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="">None (0%)</option>
                                @foreach($taxes as $tax)
                                    <option value="{{ $tax->id }}" {{ old('tax_id', $product->tax_id) == $tax->id ? 'selected' : '' }}>
                                        {{ $tax->name }} ({{ number_format($tax->value, 2) }}{{ $tax->type == 'percentage' ? '%' : '' }})
                                    </option>
                                @endforeach
                            </select>
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
                                :selected="old('unit_id', $product->unit_id)"
                                placeholder="Select Unit"
                            />
                            <x-admin.form.input-error :messages="$errors->get('unit_id')" class="mt-2" />
                        </div>

                        <!-- PC's In Carton -->
                        <div>
                            <x-admin.form.label for="pcs_in_ctn" value="Units per Carton *" />
                            <x-admin.form.input id="pcs_in_ctn" name="pcs_in_ctn" type="number" min="1" :value="old('pcs_in_ctn', $product->pcs_in_ctn)" required />
                            <p class="text-xs text-gray-500 mt-1">master_packaging</p>
                            <x-admin.form.input-error :messages="$errors->get('pcs_in_ctn')" class="mt-2" />
                        </div>

                        <!-- Initial Stock -->
                        <div>
                            <x-admin.form.label for="quantity" value="Initial Stock Quantity" />
                            <x-admin.form.input id="quantity" name="quantity" type="number" :value="old('quantity', $product->quantity)" />
                            <p class="text-xs text-gray-500 mt-1">Current stock on hand.</p>
                            <x-admin.form.input-error :messages="$errors->get('quantity')" class="mt-2" />
                        </div>

                        <!-- Alert Quantity -->
                        <div>
                            <x-admin.form.label for="alert_quantity" value="Low Stock Alert Level" />
                            <x-admin.form.input id="alert_quantity" name="alert_quantity" type="number" :value="old('alert_quantity', $product->alert_quantity)" placeholder="e.g. 10" />
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
                                    <x-admin.form.input id="cost_per_unit" name="buying_price" type="number" step="0.01" :value="old('buying_price', $product->buying_price)" class="pl-7 bg-white" placeholder="0.00" />
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
                                    <x-admin.form.input id="box_price" name="box_price" type="number" step="0.01" :value="old('box_price', $product->box_price)" class="pl-7 bg-green-50 font-bold text-green-700 border-green-300 focus:border-green-500 focus:ring-green-500" placeholder="0.00" />
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
                                    <x-admin.form.input id="unit_price" name="unit_price" type="number" step="0.01" :value="old('unit_price', $product->unit_price)" class="pl-7 bg-gray-50 text-gray-600" readonly />
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
                         <x-admin.form.rich-text name="notes" label="Notes / Description" :value="old('notes', $product->notes)" height="h-96" />
                         <x-admin.form.input-error :messages="$errors->get('notes')" class="mt-2" />
                    </div>
                </div>

                <div class="flex items-center justify-end mt-8 border-t pt-6">
                    <x-admin.actions.button type="submit" variant="primary" size="lg" class="px-8">
                        Update Product
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
                    profitDisplay: document.getElementById('profit_display'),
                    skuInput: document.getElementById('product_code'),
                    generateSkuBtn: document.getElementById('generate_sku_btn')
                };

                // SKU Generation
                if (els.generateSkuBtn) {
                    els.generateSkuBtn.addEventListener('click', function() {
                        const btn = this;
                        const icon = btn.querySelector('svg');
                        
                        // Add spin class
                        icon.classList.add('animate-spin');
                        btn.disabled = true;

                        fetch('{{ route("admin.products.next-sku") }}')
                            .then(response => response.json())
                            .then(data => {
                                if (data.sku) {
                                    els.skuInput.value = data.sku;
                                }
                            })
                            .catch(error => console.error('Error generating SKU:', error))
                            .finally(() => {
                                icon.classList.remove('animate-spin');
                                btn.disabled = false;
                            });
                    });
                }

                const defaultMargin = {!! json_encode($defaultProfitMargin) !!} || 0;

                // State
                let state = {
                    pcs: parseFloat(els.pcsInCtn.value) || 1,
                    costUnit: parseFloat(els.costUnit.value) || 0,
                    margin: defaultMargin
                };
                
                // Initialize Calculated Fields (Cost Carton)
                if(state.costUnit > 0 && state.pcs > 0) {
                     els.costCarton.value = (state.costUnit * state.pcs).toFixed(2);
                     updateProfit();
                }

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
