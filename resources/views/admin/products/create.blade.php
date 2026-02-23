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
        <form method="POST" action="{{ route('admin.products.store') }}" id="product-form" enctype="multipart/form-data">
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

                        <!-- Slug -->
                        <div class="md:col-span-2">
                            <x-admin.form.label for="slug" value="Slug (URL Friendly)" />
                            <x-admin.form.input id="slug" name="slug" :value="old('slug')" placeholder="Auto-generated from name" />
                            <p class="text-xs text-gray-500 mt-1">Leave blank to auto-generate.</p>
                            <x-admin.form.input-error :messages="$errors->get('slug')" class="mt-2" />
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
                            <div class="mt-1 flex rounded-md shadow-sm">
                                <div class="relative flex-grow focus-within:z-10">
                                    <x-admin.form.input 
                                        id="product_code" 
                                        name="product_code" 
                                        :value="old('product_code')" 
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
                           <x-admin.form.select-search 
                                name="tax_id" 
                                label="Tax Category" 
                                :options="$taxes->pluck('name', 'id')" 
                                :selected="old('tax_id')"
                                placeholder="Select Tax Category"
                            />
                            <x-admin.form.input-error :messages="$errors->get('tax_id')" class="mt-2" />
                        </div>

                        <!-- Product Image -->
                        <div class="md:col-span-2">
                             <x-admin.form.file-upload name="image" label="Product Image" />
                             <x-admin.form.input-error :messages="$errors->get('image')" class="mt-2" />
                        </div>

                       
                    </div>
                </div>

                <!-- Section 2: Packaging & Stock Management -->
                <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm">
                    <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">Packaging & Stock Management</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <!-- Weight (GM/ML) -->
                        <div>
                            <x-admin.form.label for="unit_value" value="Weight (GM/ML)" />
                            <x-admin.form.input id="unit_value" name="unit_value" type="number" step="0.01" min="0" :value="old('unit_value')" placeholder="e.g. 248" />
                            <p class="text-xs text-gray-500 mt-1">Numeric value; unit selected below (e.g. 248 + GM).</p>
                            <x-admin.form.input-error :messages="$errors->get('unit_value')" class="mt-2" />
                        </div>

                        <!-- Unit Select (Weight/Both units only) -->
                        <div>
                            <x-admin.form.select-search 
                                name="unit_id" 
                                label="Weight Unit (GM, ML, etc.)" 
                                :options="$units->whereIn('type', ['weight', 'both'])->where('is_active', true)->pluck('code', 'id')" 
                                :selected="old('unit_id')"
                                placeholder="Select Weight Unit"
                            />
                            <p class="text-xs text-gray-500 mt-1">Only Weight &amp; Both-type units are shown.</p>
                            <x-admin.form.input-error :messages="$errors->get('unit_id')" class="mt-2" />
                        </div>

                        <!-- PC's In (CTN/BAG) -->
                        <div>
                            <x-admin.form.label for="pcs_in_ctn" value="PC's In (CTN/BAG) *" />
                            <x-admin.form.input id="pcs_in_ctn" name="pcs_in_ctn" type="number" min="1" :value="old('pcs_in_ctn', 1)" required />
                            <p class="text-xs text-gray-500 mt-1">Pieces per box or bag.</p>
                            <x-admin.form.input-error :messages="$errors->get('pcs_in_ctn')" class="mt-2" />
                        </div>

                        <!-- Initial Stock + Stock unit -->
                        <div>
                            <x-admin.form.label for="quantity" value="Initial Stock Quantity" />
                            <div class="mt-1 flex gap-2">
                                <x-admin.form.input id="quantity" name="quantity" type="number" min="0" :value="old('quantity', 0)" class="flex-1 min-w-0" />
                                <select id="stock_unit" name="stock_unit" class="block w-28 rounded-md border-gray-300 shadow-sm focus:border-[#C41E3A] focus:ring-[#C41E3A] sm:text-sm">
                                    @foreach(\App\Models\Product::stockUnitOptions() as $value => $label)
                                        <option value="{{ $value }}" {{ old('stock_unit', 'piece') == $value ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Current stock on hand (in the selected unit: piece, dozen, or box).</p>
                            <x-admin.form.input-error :messages="$errors->get('quantity')" class="mt-2" />
                            <x-admin.form.input-error :messages="$errors->get('stock_unit')" class="mt-2" />
                        </div>

                    </div>
                </div>

                <!-- Section 3: Wholesale Pricing Engine (prices per stock unit: piece / dozen / box) -->
                <div class="bg-blue-50 p-6 rounded-lg border border-blue-100 shadow-sm">
                    <div class="flex items-center justify-between mb-4 border-b border-blue-200 pb-2">
                        <h3 class="text-lg font-medium text-blue-900">Wholesale Pricing Engine</h3>
                        <span class="text-xs text-blue-700 bg-white px-3 py-1 rounded-full border border-blue-200">Default margin: <span id="default_margin_badge" class="font-bold">{{ $defaultProfitMargin ?? '—' }}</span>%</span>
                    </div>
                    <p class="text-sm text-blue-800/90 mb-4">Prices are <strong>per stock unit</strong> (same as above: piece, dozen, or box). Enter buying price and selling is suggested using the default margin; change selling to see your custom profit margin.</p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                        <div>
                            <label for="buying_price_stock_unit" id="label_buying" class="block text-sm font-medium text-gray-700">Buying price ($/<span id="price_unit_label">piece</span>)</label>
                            <div class="relative rounded-md shadow-sm mt-1">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">$</span>
                                </div>
                                <input type="number" step="0.01" min="0" name="buying_price_stock_unit" id="buying_price_stock_unit" value="{{ old('buying_price_stock_unit') }}" placeholder="0.00" class="block w-full pl-7 rounded-md border-gray-300 shadow-sm focus:border-[#C41E3A] focus:ring-[#C41E3A] sm:text-sm" />
                            </div>
                            <x-admin.form.input-error :messages="$errors->get('buying_price_stock_unit')" class="mt-2" />
                        </div>
                        <div>
                            <label for="selling_price_stock_unit" id="label_selling" class="block text-sm font-medium text-gray-700">Selling price ($/<span id="selling_unit_label">piece</span>)</label>
                            <div class="relative rounded-md shadow-sm mt-1">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">$</span>
                                </div>
                                <input type="number" step="0.01" min="0" name="selling_price_stock_unit" id="selling_price_stock_unit" value="{{ old('selling_price_stock_unit') }}" placeholder="0.00" class="block w-full pl-7 rounded-md border-green-400 text-green-800 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm" />
                            </div>
                            <x-admin.form.input-error :messages="$errors->get('selling_price_stock_unit')" class="mt-2" />
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center gap-4 p-4 bg-white/70 rounded-lg border border-blue-200">
                        <div>
                            <span class="text-xs font-medium text-gray-500 uppercase tracking-wider">Profit margin</span>
                            <p id="profit_display" class="text-lg font-bold text-green-700 mt-0.5">— Enter buying price (selling will use default margin)</p>
                        </div>
                    </div>
                </div>

                <!-- Section 4: Additional -->
                <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm">
                    <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">Additional Details</h3>
                    <div>
                         <x-admin.form.rich-text name="notes" label="Notes / Description" :value="old('notes')" height="h-96" />
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
                const els = {
                    pcsInCtn: document.getElementById('pcs_in_ctn'),
                    buyingCarton: document.getElementById('buying_price_carton'),
                    sellCarton: document.getElementById('box_price'),
                    unitPriceHidden: document.getElementById('unit_price'),
                    profitDisplay: document.getElementById('profit_display'),
                    unitPriceDisplay: document.getElementById('unit_price_display'),
                    skuInput: document.getElementById('product_code'),
                    generateSkuBtn: document.getElementById('generate_sku_btn'),
                    nameInput: document.querySelector('input[name="name"]'),
                    slugInput: document.querySelector('input[name="slug"]')
                };

                // Auto-generate Slug
                if (els.nameInput && els.slugInput) {
                    els.nameInput.addEventListener('input', function() {
                        if (!els.slugInput.value || els.slugInput.dataset.auto === 'true') {
                            let slug = this.value.toLowerCase()
                                .replace(/[^\w\s-]/g, '')
                                .replace(/\s+/g, '-')
                                .replace(/-+/g, '-')
                                .replace(/^-+|-+$/g, '');
                            els.slugInput.value = slug;
                            els.slugInput.dataset.auto = 'true';
                        }
                    });
                    els.slugInput.addEventListener('input', function() { this.dataset.auto = 'false'; });
                }

                if (els.generateSkuBtn) {
                    els.generateSkuBtn.addEventListener('click', function() {
                        const btn = this;
                        const icon = btn.querySelector('svg');
                        icon.classList.add('animate-spin');
                        btn.disabled = true;
                        fetch('{{ route("admin.products.next-sku") }}')
                            .then(r => r.json())
                            .then(data => { if (data.sku) els.skuInput.value = data.sku; })
                            .catch(e => console.error('Error generating SKU:', e))
                            .finally(() => { icon.classList.remove('animate-spin'); btn.disabled = false; });
                    });
                }

                // Pricing per stock unit: default margin applied when entering buying; custom margin when user edits selling
                const defaultMargin = parseFloat({!! json_encode($defaultProfitMargin) !!}) || 0;
                const stockUnitEl = document.getElementById('stock_unit');
                const buyingEl = document.getElementById('buying_price_stock_unit');
                const sellingEl = document.getElementById('selling_price_stock_unit');
                const priceUnitLabel = document.getElementById('price_unit_label');
                const sellingUnitLabel = document.getElementById('selling_unit_label');

                function getMultiplier() {
                    const su = (stockUnitEl && stockUnitEl.value) || 'piece';
                    const pcs = parseFloat(els.pcsInCtn?.value) || 1;
                    if (su === 'dozen') return 12;
                    if (su === 'box') return pcs;
                    return 1;
                }

                function getUnitLabel() {
                    const su = (stockUnitEl && stockUnitEl.value) || 'piece';
                    return su;
                }

                function updatePriceLabels() {
                    const label = getUnitLabel();
                    if (priceUnitLabel) priceUnitLabel.textContent = label;
                    if (sellingUnitLabel) sellingUnitLabel.textContent = label;
                }

                function updatePricing() {
                    const buy = parseFloat(buyingEl?.value) || 0;
                    const sell = parseFloat(sellingEl?.value) || 0;

                    if (buy > 0 && sell > 0) {
                        const profit = sell - buy;
                        const marginPercent = ((profit / buy) * 100).toFixed(1);
                        els.profitDisplay.textContent = marginPercent + '%';
                        els.profitDisplay.classList.remove('text-gray-500');
                        els.profitDisplay.classList.add('text-green-700');
                    } else if (buy > 0) {
                        els.profitDisplay.textContent = 'Enter selling price or leave to use default margin (' + defaultMargin + '%)';
                        els.profitDisplay.classList.add('text-gray-500');
                        els.profitDisplay.classList.remove('text-green-700');
                    } else {
                        els.profitDisplay.textContent = '— Enter buying price (selling will use default margin)';
                        els.profitDisplay.classList.add('text-gray-500');
                        els.profitDisplay.classList.remove('text-green-700');
                    }
                }

                function onBuyingInput() {
                    const buy = parseFloat(buyingEl?.value) || 0;
                    if (buy > 0 && defaultMargin > 0 && sellingEl) {
                        const suggested = buy * (1 + defaultMargin / 100);
                        if (!sellingEl.dataset.userEdited) {
                            sellingEl.value = suggested.toFixed(2);
                        }
                    }
                    updatePricing();
                }

                if (buyingEl) buyingEl.addEventListener('input', onBuyingInput);
                if (sellingEl) {
                    sellingEl.addEventListener('input', function() {
                        this.dataset.userEdited = '1';
                        updatePricing();
                    });
                }
                if (stockUnitEl) stockUnitEl.addEventListener('change', function() { updatePriceLabels(); updatePricing(); });
                if (els.pcsInCtn) els.pcsInCtn.addEventListener('input', updatePriceLabels);

                updatePriceLabels();
                updatePricing();
            });
        </script>
    </x-admin.ui.card>
@endsection
