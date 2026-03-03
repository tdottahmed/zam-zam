<!DOCTYPE html>
<html>

<head>
  <title>Product List - {{ $company['name'] }}</title>
  <style>
    body {
      font-family: 'Helvetica', 'Arial', sans-serif;
      font-size: 11px;
      color: #000;
      margin: 0;
      padding: 0;
    }

    /* Reserve ample space for header and footer so body content never overlaps */
    @page {
      margin-top: 165px;
      margin-bottom: 60px;
      header: page-header;
      footer: page-footer;
    }

    @page :first {
      margin-top: 165px;
      margin-bottom: 60px;
      header: page-header;
      footer: page-footer;
    }

    .header-top {
      width: 100%;
      margin-bottom: 8px;
      display: table;
      table-layout: fixed;
    }

    .logo-container {
      display: table-cell;
      vertical-align: top;
      width: auto;
    }

    .logo {
      display: inline-block;
      height: 58px;
      width: auto;
      max-height: 58px;
    }

    .company-info {
      display: table-cell;
      vertical-align: top;
      text-align: right;
      font-size: 10px;
      line-height: 1.4;
      color: #333;
    }

    .company-name {
      font-size: 12px;
      font-weight: bold;
      margin-bottom: 2px;
      color: #000;
    }

    .header-separator {
      border-top: 1px solid #000;
      margin: 6px 0 0 0;
    }

    .report-title {
      font-size: 18px;
      font-weight: bold;
      margin: 12px 0;
      color: #333;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      font-size: 10px;
    }

    th,
    td {
      text-align: left;
      padding: 6px 5px;
      border-bottom: 1px solid #ccc;
    }

    th {
      font-weight: bold;
      background-color: #f5f5f5;
      color: #333;
    }

    .text-right {
      text-align: right;
    }

    .text-center {
      text-align: center;
    }

    .page-footer-content {
      text-align: center;
      font-size: 9px;
      border-top: 1px solid #000;
      padding-top: 6px;
      margin-top: 20px;
    }

    .product-img {
      width: 36px;
      height: 36px;
      object-fit: contain;
      border: 1px solid #eee;
    }
  </style>
</head>

<body>

  <htmlpageheader name="page-header">
    <div style="width: 100%;">
      <div class="header-top">
        <div class="logo-container">
          @if (file_exists(public_path('images/Zam_logo-120x99.png')))
            <img src="{{ public_path('images/Zam_logo-120x99.png') }}" alt="Logo" class="logo">
          @endif
        </div>
        <div class="company-info">
          <div class="company-name">{{ $company['name'] }}</div>
          <div>{!! strip_tags(str_replace(["\n", '<br />', '<br>', '<br/>'], ', ', $company['address'] ?? '')) !!}</div>
          <div>Phone: {{ $company['phone'] }} &nbsp;|&nbsp; Cell: {{ $company['cell'] }}</div>
          <div>Tax ID: {{ $company['tax_id'] }}</div>
          <div>Email: {{ $company['email'] }}</div>
        </div>
      </div>
      <div class="header-separator"></div>
    </div>
  </htmlpageheader>

  <htmlpagefooter name="page-footer">
    <div class="page-footer-content">
      <div>{{ $company['name'] }} | Phone: {{ $company['phone'] }} | Cell: {{ $company['cell'] }} | Email:
        {{ $company['email'] }} | HST: {{ $company['tax_id'] }}</div>
      <div style="margin-top: 4px;">Page {PAGENO} of {nbpg}</div>
    </div>
  </htmlpagefooter>

  <sethtmlpageheader name="page-header" value="on" />
  <sethtmlpagefooter name="page-footer" value="on" />

  <div style="padding-top: 15px;">
    <div class="report-title">Product List</div>
    <div style="font-size: 10px; color: #666; margin-bottom: 10px;">Generated on {{ $generatedAt }}</div>

    <table>
      <thead>
        <tr>
          <th style="width: 35px;">SL</th>
          <th style="width: 35%;">Product Name</th>
          <th style="width: 70px;" class="text-right">Box Price</th>
          <th style="width: 70px;" class="text-right">Unit Price</th>
          <th style="width: 55px;">Weight</th>
          <th style="width: 55px;" class="text-center">PCs/BAG</th>
          <th style="width: 50px;" class="text-center">Image</th>
          <th style="width: 50px;" class="text-center">Featured</th>
        </tr>
      </thead>
      <tbody>
        @forelse($products as $product)
          <tr>
            <td class="text-center">{{ $loop->iteration }}</td>
            <td>
              <span>{{ $product->name }}</span>
              @if ($product->product_code)
                <span class="font-mono" style="font-size: 9px; color: #666;"> ({{ $product->product_code }})</span>
              @endif
            </td>
            <td class="text-center">${{ number_format($product->box_price ?? 0, 2) }}</td>
            <td class="text-center">${{ number_format($product->buying_price ?? 0, 2) }}</td>
            <td>{{ $product->weight_display ?? '—' }}</td>
            <td class="text-center">{{ $product->pcs_in_ctn ?? '-' }}</td>
            <td class="text-center">
              @php
                $imgPath = $product->image ? public_path('storage/' . $product->image) : null;
              @endphp
              @if ($imgPath && file_exists($imgPath))
                <img src="{{ $imgPath }}" alt="" class="product-img">
              @else
                —
              @endif
            </td>
            <td class="text-center">{{ $product->is_featured ?? false ? 'Yes' : 'No' }}</td>
          </tr>
        @empty
          <tr>
            <td colspan="9" class="text-center" style="padding: 20px;">No products found.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

</body>

</html>
