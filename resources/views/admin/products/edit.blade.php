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
                            <x-admin.form.input id="slug" name="slug" :value="old('slug', $product->slug)" placeholder="Auto-generated from name" data-auto="true" />
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

                <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm">
                    <h3 class="text-lg font-medium text-gray-900 mb-5 border-b pb-2">Packaging &amp; Stock Management</h3>

                    {{-- Row 1: Weight / Packaging --}}
                    <div class="mb-6">
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-3 flex items-center gap-2">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" /></svg>
                            Weight &amp; Packaging
                        </p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Weight Value -->
                            <div>
                                <x-admin.form.label for="unit_value" value="Weight / Volume Value" />
                                <x-admin.form.input id="unit_value" name="unit_value" type="number" step="0.01" min="0" :value="old('unit_value', $product->unit_value)" placeholder="e.g. 248" />
                                <p class="text-xs text-gray-500 mt-1">Numeric value paired with the unit below (e.g. 248 + GM).</p>
                                <x-admin.form.input-error :messages="$errors->get('unit_value')" class="mt-2" />
                            </div>

                            <!-- Weight Unit -->
                            <div>
                                <x-admin.form.select-search
                                    name="unit_id"
                                    label="Weight Unit (GM, ML, KG…)"
                                    :options="$units->whereIn('type', ['weight', 'both'])->where('is_active', true)->pluck('code', 'id')"
                                    :selected="old('unit_id', $product->unit_id)"
                                    placeholder="Select Weight Unit"
                                />
                                <p class="text-xs text-gray-500 mt-1">Only Weight &amp; Both-type units shown.</p>
                                <x-admin.form.input-error :messages="$errors->get('unit_id')" class="mt-2" />
                            </div>
                        </div>
                    </div>

                    {{-- Row 2: Stock --}}
                    <div class="pt-5 border-t border-gray-100">
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-3 flex items-center gap-2">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                            Stock
                        </p>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- PC's In (CTN/BAG) -->
                            <div>
                                <x-admin.form.label for="pcs_in_ctn" value="PC's In (CTN/BAG) *" />
                                <x-admin.form.input id="pcs_in_ctn" name="pcs_in_ctn" type="number" min="1" :value="old('pcs_in_ctn', $product->pcs_in_ctn)" required />
                                <p class="text-xs text-gray-500 mt-1">Pieces per box or bag.</p>
                                <x-admin.form.input-error :messages="$errors->get('pcs_in_ctn')" class="mt-2" />
                            </div>

                            <!-- Initial Stock Quantity -->
                            <div>
                                <x-admin.form.label for="quantity" value="Stock Quantity" />
                                <x-admin.form.input id="quantity" name="quantity" type="number" min="0" :value="old('quantity', $product->quantity)" class="mt-1" />
                                <p class="text-xs text-gray-500 mt-1">Current stock on hand.</p>
                                <x-admin.form.input-error :messages="$errors->get('quantity')" class="mt-2" />
                            </div>

                            <!-- Stock Unit -->
                            @php
                                $stockUnitOptions = $units
                                    ->whereIn('type', ['stock', 'both'])
                                    ->where('is_active', true)
                                    ->mapWithKeys(fn($u) => [$u->name => $u->name . ' (' . $u->code . ')']);
                            @endphp
                            <div>
                                <x-admin.form.select-search
                                    name="stock_unit"
                                    label="Stock Unit"
                                    :options="$stockUnitOptions"
                                    :selected="old('stock_unit', $product->stock_unit)"
                                    placeholder="Select Stock Unit"
                                />
                                <p class="text-xs text-gray-500 mt-1">Unit the stock quantity is measured in.</p>
                                <x-admin.form.input-error :messages="$errors->get('stock_unit')" class="mt-2" />
                            </div>
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

                    @php
                        $su = old('stock_unit', $product->stock_unit ?? 'piece');
                        $pcs = (int) old('pcs_in_ctn', $product->pcs_in_ctn) ?: 1;
                        $buyingStockUnit = old('buying_price_stock_unit');
                        if ($buyingStockUnit === null && $product->buying_price !== null) {
                            $buyingStockUnit = round($product->buying_price, 2);
                        }
                        $sellingStockUnit = old('selling_price_stock_unit');
                        if ($sellingStockUnit === null && $product->unit_price !== null) {
                            $sellingStockUnit = round($product->unit_price, 2);
                        }
                    @endphp
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                        <div>
                            <label for="buying_price_stock_unit" id="label_buying" class="block text-sm font-medium text-gray-700">Buying price ($/<span id="price_unit_label">{{ $su }}</span>)</label>
                            <div class="relative rounded-md shadow-sm mt-1">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">$</span>
                                </div>
                                <input type="number" step="0.01" min="0" name="buying_price_stock_unit" id="buying_price_stock_unit" value="{{ $buyingStockUnit }}" placeholder="0.00" class="block w-full pl-7 rounded-md border-gray-300 shadow-sm focus:border-[#C41E3A] focus:ring-[#C41E3A] sm:text-sm" />
                            </div>
                            <x-admin.form.input-error :messages="$errors->get('buying_price_stock_unit')" class="mt-2" />
                        </div>
                        <div>
                            <label for="selling_price_stock_unit" id="label_selling" class="block text-sm font-medium text-gray-700">Selling price ($/<span id="selling_unit_label">{{ $su }}</span>)</label>
                            <div class="relative rounded-md shadow-sm mt-1">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">$</span>
                                </div>
                                <input type="number" step="0.01" min="0" name="selling_price_stock_unit" id="selling_price_stock_unit" value="{{ $sellingStockUnit }}" placeholder="0.00" class="block w-full pl-7 rounded-md border-green-400 text-green-800 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm" />
                            </div>
                            <x-admin.form.input-error :messages="$errors->get('selling_price_stock_unit')" class="mt-2" />
                        </div>
                    </div>

                    <div class="flex flex-col gap-4 p-4 bg-white/70 rounded-lg border border-blue-200 transition-all duration-300">
                        <!-- Margin Header -->
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="text-xs font-medium text-gray-500 uppercase tracking-wider">Profit margin</span>
                                <p id="profit_display" class="text-lg font-bold text-gray-500 mt-0.5">— Enter buying price</p>
                            </div>
                            <div id="profit_badge" class="hidden px-3 py-1 rounded-full text-sm font-bold bg-green-100 text-green-800"></div>
                        </div>

                        <!-- Dynamic Breakdowns -->
                        <div id="price_breakdown" style="display: none;" class="grid grid-cols-1 sm:grid-cols-3 gap-5 border-t border-blue-100 pt-4 mt-2">
                            <!-- Unit Price -->
                            <div class="bg-white p-3 rounded-md shadow-sm border border-gray-100">
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider flex items-center gap-1.5 mb-2">
                                    <svg class="w-3.5 h-3.5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" /></svg>
                                    Per Unit (Piece)
                                </span>
                                <div class="flex flex-col gap-1">
                                    <div class="flex justify-between items-center text-sm"><span class="text-gray-500">Buy:</span> <strong class="text-gray-900">$<span id="calc_buy_piece">0.00</span></strong></div>
                                    <div class="flex justify-between items-center text-sm"><span class="text-gray-500">Sell:</span> <strong class="text-green-600">$<span id="calc_sell_piece">0.00</span></strong></div>
                                </div>
                            </div>
                            
                            <!-- Carton Price -->
                            <div class="bg-white p-3 rounded-md shadow-sm border border-gray-100">
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider flex items-center gap-1.5 mb-2">
                                    <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                                    Carton (<span id="calc_ctn_pcs">1</span> PCs)
                                </span>
                                <div class="flex flex-col gap-1">
                                    <div class="flex justify-between items-center text-sm"><span class="text-gray-500">Buy:</span> <strong class="text-gray-900">$<span id="calc_buy_ctn">0.00</span></strong></div>
                                    <div class="flex justify-between items-center text-sm"><span class="text-gray-500">Sell:</span> <strong class="text-green-600">$<span id="calc_sell_ctn">0.00</span></strong></div>
                                </div>
                            </div>
                            
                            <!-- Net Profit -->
                            <div class="bg-blue-50/50 p-3 rounded-md border border-blue-50 flex flex-col justify-center">
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider flex items-center gap-1.5 mb-1">
                                    <svg class="w-3.5 h-3.5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    Net Profit (per <span id="calc_profit_unit_label" class="lowercase truncate inline-block align-bottom max-w-[50px]">unit</span>)
                                </span>
                                <p class="text-xl font-black text-green-600 mt-1">$<span id="calc_profit_amount">0.00</span></p>
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
                const els = {
                    pcsInCtn: document.getElementById('pcs_in_ctn'),
                    profitDisplay: document.getElementById('profit_display'),
                    skuInput: document.getElementById('product_code'),
                    generateSkuBtn: document.getElementById('generate_sku_btn'),
                    nameInput: document.querySelector('input[name="name"]'),
                    slugInput: document.querySelector('input[name="slug"]')
                };

                // Auto-generate slug from name (same as create)
                if (els.nameInput && els.slugInput) {
                    els.nameInput.addEventListener('input', function() {
                        if (!els.slugInput.value || els.slugInput.dataset.auto === 'true') {
                            var slug = this.value.toLowerCase()
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

                const defaultMargin = parseFloat({!! json_encode($defaultProfitMargin) !!}) || 0;
                const stockUnitEl = document.getElementById('stock_unit');
                const buyingEl = document.getElementById('buying_price_stock_unit');
                const sellingEl = document.getElementById('selling_price_stock_unit');
                const priceUnitLabel = document.getElementById('price_unit_label');
                const sellingUnitLabel = document.getElementById('selling_unit_label');
                
                const breakdownEl = document.getElementById('price_breakdown');
                const calcBuyPiece = document.getElementById('calc_buy_piece');
                const calcSellPiece = document.getElementById('calc_sell_piece');
                const calcBuyCtn = document.getElementById('calc_buy_ctn');
                const calcSellCtn = document.getElementById('calc_sell_ctn');
                const calcCtnPcs = document.getElementById('calc_ctn_pcs');
                const calcProfitAmount = document.getElementById('calc_profit_amount');
                const calcProfitUnitLabel = document.getElementById('calc_profit_unit_label');
                const profitBadge = document.getElementById('profit_badge');

                function getMultiplier() {
                    let su = (stockUnitEl && stockUnitEl.value) || '';
                    su = su.toLowerCase();
                    const pcs = parseFloat(els.pcsInCtn?.value) || 1;
                    if (su.includes('dozen')) return 12;
                    if (su.includes('box') || su.includes('carton') || su.includes('ctn')) return pcs;
                    return 1;
                }

                function getUnitLabel() {
                    let su = (stockUnitEl && stockUnitEl.value) || 'piece';
                    return su.split(' ')[0]; // Extract primary unit name
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
                        els.profitDisplay.innerHTML = `<span class="${profit >= 0 ? 'text-green-700' : 'text-red-600'}">${marginPercent}%</span> <span class="text-sm font-normal text-gray-500 ml-1">(${profit >= 0 ? 'Profit' : 'Loss'})</span>`;
                        profitBadge.textContent = marginPercent + '%';
                        profitBadge.classList.remove('hidden');
                        profitBadge.className = `px-3 py-1 rounded-full text-sm font-bold ${profit >= 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}`;
                    } else if (buy > 0) {
                        els.profitDisplay.textContent = 'Leave selling empty to use default margin (' + defaultMargin + '%)';
                        profitBadge.classList.add('hidden');
                        profitBadge.classList.remove('bg-green-100', 'text-green-800', 'bg-red-100', 'text-red-800');
                    } else {
                        els.profitDisplay.textContent = '— Enter buying price';
                        profitBadge.classList.add('hidden');
                        profitBadge.classList.remove('bg-green-100', 'text-green-800', 'bg-red-100', 'text-red-800');
                    }
                    
                    // Show detailed breakdown calculation
                    if (buy > 0 || sell > 0) {
                        if(breakdownEl) breakdownEl.style.display = 'grid';
                        
                        const multiplier = getMultiplier();
                        const pcsInCtn = parseFloat(els.pcsInCtn?.value) || 1;
                        
                        // Per piece prices
                        const buyPiece = buy / multiplier;
                        const sellPiece = sell / multiplier;
                        
                        // Per carton prices
                        const buyCtn = buyPiece * pcsInCtn;
                        const sellCtn = sellPiece * pcsInCtn;
                        
                        if(calcBuyPiece) calcBuyPiece.textContent = buyPiece.toFixed(2);
                        if(calcSellPiece) calcSellPiece.textContent = sellPiece.toFixed(2);
                        
                        if(calcBuyCtn) calcBuyCtn.textContent = buyCtn.toFixed(2);
                        if(calcSellCtn) calcSellCtn.textContent = sellCtn.toFixed(2);
                        
                        if(calcCtnPcs) calcCtnPcs.textContent = pcsInCtn;
                        
                        if(calcProfitAmount) calcProfitAmount.textContent = Math.max(0, sell - buy).toFixed(2);
                        if(calcProfitUnitLabel) calcProfitUnitLabel.textContent = getUnitLabel();
                    } else {
                        if(breakdownEl) breakdownEl.style.display = 'none';
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
