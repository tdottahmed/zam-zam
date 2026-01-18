<!DOCTYPE html>
<html>

<head>
  <title>Invoice</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      font-size: 10px;
      background-color: #ccffcc;
      /* Light green background matching screenshot */
      color: #000;
    }

    @page {
      header: page-header;
      footer: page-footer;
      background-color: #ccffcc;
    }

    @page :first {
      header: page-header;
      footer: page-footer;
      background-color: #ccffcc;
    }

    /* Header Styles */
    htmlpageheader {
      background-color: #ccffcc;
    }

    .header-top {
      width: 100%;
      margin-bottom: 10px;
      display: table;
      table-layout: fixed;
      background-color: #ccffcc;
    }

    .logo-container {
      display: table-cell;
      vertical-align: top;
      width: auto;
    }

    .logo {
      display: inline-block;
      height: 20px;
      width: auto;
      max-height: 60px;
    }

    .flavor-text {
      display: table-cell;
      text-align: right;
      vertical-align: top;
      font-size: 10px;
      padding-top: 5px;
    }

    .header-separator {
      width: 100%;
      height: 0;
      border-top: 1px solid #000;
      margin: 8px 0;
    }

    .address-section {
      width: 100%;
      padding-bottom: 5px;
      margin-bottom: 10px;
      overflow: hidden;
      background-color: #ccffcc;
    }

    .address-separator {
      width: 100%;
      height: 0;
      border-top: 1px solid #000;
      margin-top: 10px;
      margin-bottom: 0;
    }

    .partner-ref {
      width: 48%;
      float: left;
      padding-right: 2%;
    }

    .delivery-addr {
      width: 48%;
      float: right;
      text-align: right;
      padding-left: 2%;
    }

    .section-title {
      font-weight: bold;
      margin-bottom: 2px;
      font-size: 11px;
    }

    /* Invoice Details */
    .invoice-title {
      font-size: 18px;
      font-weight: bold;
      margin: 10px 0;
      color: #555;
    }

    .info-bar {
      width: 100%;
      margin-bottom: 15px;
    }

    .info-label {
      font-weight: bold;
    }

    .payment-instructions {
      background-color: #d2b48c;
      /* Tan/Brownish color */
      padding: 5px;
      display: inline-block;
      width: 200px;
    }

    /* Table */
    table {
      width: 100%;
      border-collapse: collapse;
      font-size: 9px;
    }

    th {
      text-align: left;
      padding: 5px 2px;
      font-weight: normal;
      color: #333;
      border-bottom: 1px solid #ccc;
    }

    td {
      padding: 5px 2px;
      vertical-align: top;
    }

    .text-right {
      text-align: right;
    }

    /* Footer */
    .page-footer-content {
      text-align: center;
      font-size: 9px;
      border-top: 1px solid #000;
      padding-top: 5px;
      width: 100%;
    }

    .totals-section {
      margin-top: 20px;
      margin-bottom: 20px;
      width: 100%;
      padding-top: 10px;
      clear: both;
      display: block;
      position: relative;
    }

    .amounts {
      width: 300px;
      float: right;
    }

    .clearfix {
      clear: both;
    }

    .signature-section {
      margin-top: 50px;
      text-align: right;
      padding-right: 20px;
    }
  </style>
</head>

<body>

  <!-- Define Header -->
  <htmlpageheader name="page-header">
    <div style="background-color: #ccffcc; width: 100%;">
      <div class="header-top">
        <div class="logo-container">
          <img src="{{ public_path('images/logo.jpg') }}" alt="Super Asia Logo" class="logo">
        </div>
        <div class="flavor-text">Flavors of the World.</div>
      </div>

      <div class="header-separator"></div>

      <div class="address-section">
        <div class="partner-ref">
          <div class="section-title">Partner Reference:</div>
          <div>{{ $data['partner']['name'] }}</div>
          <div>{{ $data['partner']['address_1'] }}</div>
          @if (!empty($data['partner']['address_2']))
            <div>{{ $data['partner']['address_2'] }}</div>
          @endif
          <div>{{ $data['partner']['country'] }}</div>
          @if (!empty($data['partner']['phone']))
            <div>&#9742; {{ $data['partner']['phone'] }}</div>
          @endif
        </div>
        <div class="delivery-addr">
          <div class="section-title">Delivery Address:</div>
          <div>{{ $data['delivery']['name'] }}</div>
          <div>{{ $data['delivery']['address_1'] }}</div>
          @if (!empty($data['delivery']['address_2']))
            <div>{{ $data['delivery']['address_2'] }}</div>
          @endif
          <div>{{ $data['delivery']['country'] }}</div>
          @if (!empty($data['delivery']['phone']))
            <div>&#9742; {{ $data['delivery']['phone'] }}</div>
          @endif
        </div>
        <div class="clearfix"></div>
      </div>

      <div class="address-separator"></div>
    </div>
  </htmlpageheader>

  <!-- Define Footer -->
  <htmlpagefooter name="page-footer">
    <div class="page-footer-content">
      <div>Super Asia Foods | 8905 Hwy 50 , Unit 7, Vaughan , Ontario (CA) L4H 5A1 , Canada | +1 416-746-5550</div>
      <div>Email: hello@superasia.ca | Web: http://www.superasia.ca | HST:847720521RT0001</div>
      <div style="margin-top: 5px;">Page: {PAGENO} / {nbpg}</div>
    </div>
  </htmlpagefooter>

  <!-- Content Body -->
  <sethtmlpageheader name="page-header" value="on" />
  <div class="invoice-title">Invoice {{ $data['invoice_number'] }}</div>

  <div class="info-bar">
    <table style="width: 100%; border: none; margin-bottom: 0;">
      <tr>
        <td style="width: 10%;">
          <span class="info-label">Invoice Date:</span><br> {{ $data['invoice_date'] }}
        </td>
        <td style="width: 10%;">
          <span class="info-label">Due Date:</span><br> {{ $data['due_date'] }}
        </td>
        <td style="width: 10%;">
          <span class="info-label">Source:</span><br> {{ $data['source'] }}
        </td>
        <td style="width: 15%;">
          <span class="info-label">Purchase Order #:</span><br> {{ $data['purchase_order'] }}
        </td>
        <td style="width: 15%;">
          <span class="info-label">Salesperson:</span><br> {{ $data['salesperson'] }}
        </td>
        <td style="vertical-align: top; text-align: right;">
          <div class="payment-instructions" style="text-align: left;">
            <strong>Payment Instructions:</strong><br>
            {{ $data['payment_instructions'] }}
          </div>
        </td>
      </tr>
    </table>
  </div>

  <table>
    <thead>
      <tr class="items-header">
        <th style="width: 30px;">S.No</th>
        <th style="width: 40px; text-align: center;">Quantity</th>
        <th style="width: 250px;">Sales Description</th>
        <th>UPC</th>
        <th>U.O.M.</th>
        <th>Box Price</th>
        <th>Per Unit Price</th>
        <th class="text-right">Amount</th>
        <th>Taxes</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($data['items'] as $item)
        <tr>
          <td>{{ $item['s_no'] }}</td>
          <td style="text-align: center;">{{ $item['quantity'] }}</td>
          <td>{{ $item['description'] }}</td>
          <td>{{ $item['upc'] }}</td>
          <td>{{ $item['uom'] }}</td>
          <td>${{ number_format($item['box_price'], 2) }}</td>
          <td>${{ number_format($item['unit_price'], 2) }}</td>
          <td class="text-right">${{ number_format($item['amount'], 2) }}</td>
          <td>{{ $item['taxes'] }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>

  <div class="totals-section">
    <div style="border-top: 1px solid #ccc; padding-top: 10px; margin-top: 10px;"></div>

    <div style="float: left; width: 50%;">
      <div>Total Shipped Quantity: {{ $data['total_shipped_qty'] }}</div>

      <div style="margin-top: 20px;">
        Please use the following communication for your payment : {{ $data['invoice_number'] }}
      </div>
      <div style="margin-top: 10px;">
        CHQ ILL RCV
      </div>
    </div>

    <div class="amounts">
      <div style="border-bottom: 1px solid #ccc; padding-bottom: 5px; margin-bottom: 5px;">
        <table style="width: 100%;">
          <tr>
            <td>Subtotal</td>
            <td class="text-right">${{ number_format($data['subtotal'], 2) }}</td>
          </tr>
        </table>
      </div>
      <div style="border-bottom: 1px solid #ccc; padding-bottom: 5px; margin-bottom: 5px;">
        <table style="width: 100%;">
          <tr>
            <td>HST 13% on $ {{ number_format($data['hst_base'], 2) }}</td>
            <td class="text-right">${{ number_format($data['hst'], 2) }}</td>
          </tr>
        </table>
      </div>
      <div style="margin-bottom: 5px;">
        <table style="width: 100%;">
          <tr>
            <td style="font-weight: bold;">Total</td>
            <td class="text-right" style="font-weight: bold;">${{ number_format($data['total'], 2) }}</td>
          </tr>
        </table>
      </div>
    </div>
    <div class="clearfix"></div>

    <div style="text-align: center; margin-top: 20px; font-size: 9px;">
      **No claims after 7 days of delivery.**
    </div>

    <div class="signature-section">
      <br><br>
      Customer Signature
    </div>
  </div>

</body>

</html>
