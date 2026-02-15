<!DOCTYPE html>
<html>

<head>
  <title>Invoice</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      font-size: 12px;
      
      /* Light green background matching screenshot */
      color: #000;
    }

    @page {
      header: page-header;
      footer: page-footer;
      margin-top: 100px; /* Adjust for larger header */
    }

    @page :first {
      header: page-header;
      footer: page-footer;
      
    }

    /* Header Styles */
    htmlpageheader {
      
    }

    .header-top {
      width: 100%;
      margin-bottom: 20px;
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
      height: 80px; /* Increased from 20px */
      width: auto;
      max-height: none;
      margin-top:10px;
    }

    .flavor-text {
      display: table-cell;
      text-align: right;
      vertical-align: top;
      font-size: 14px; /* Increased from 12px for better readability */
      padding-top: 15px; /* Aligned with larger logo */
      color: #555;
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
      font-size: 12px;
    }

    /* Invoice Details */
    .invoice-title {
      font-size: 18px;
      font-weight: bold;
      margin: 10px 0;
      color: #555;
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
      font-size: 11px;
    }

    th, td {
      text-align: left;
      padding: 8px 5px; /* Increased padding */
      border-bottom: 1px solid #ccc;
    }

    th {
      font-weight: bold; /* Changed from normal */
      color: #333;
      background-color: #f9f9f9; /* Added background */
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
    <div style="width: 100%;">
      <div class="header-top">
        <div class="logo-container">
          <img src="{{ public_path('images/Zam_logo-120x99.png') }}" alt="Zam Zam Logo" class="logo">
        </div>
        <div class="flavor-text">Flavors of the World.</div>
      </div>
    </div>
  </htmlpageheader>

  <!-- Define Footer -->
  <htmlpagefooter name="page-footer">
    <div class="page-footer-content">
      <div>Zam Zam Import export Inc | 8905 Hwy 50 , Unit 7, Vaughan , Ontario (CA) L4H 5A1 , Canada | +1 416-746-5550</div>
      <div>Email: hello@superasia.ca | Web: http://www.superasia.ca | HST:847720521RT0001</div>
      <div style="margin-top: 5px;">Page: {PAGENO} / {nbpg}</div>
    </div>
  </htmlpagefooter>

  <!-- Content Body -->
  <sethtmlpageheader name="page-header" value="on" />
  
  <div class="invoice-title">Invoice {{ $data['invoice_number'] }}</div>

  <table style="width: 100%; margin-bottom: 10px; border-spacing: 0;">
    <tr>
      <td style="width: 48%; vertical-align: top; background-color: #f9f9f9; padding: 15px; border-radius: 5px; border: 1px solid #eee;">
          <div style="font-size: 10px; text-transform: uppercase; color: #888; font-weight: bold; margin-bottom: 10px; letter-spacing: 1px;">Partner Reference</div>
          <div style="font-size: 14px; font-weight: bold; margin-bottom: 5px; color: #333;">{{ $data['partner']['name'] }}</div>
          <div style="color: #555; line-height: 1.4;">
              {{ $data['partner']['address_1'] }}<br>
              @if (!empty($data['partner']['address_2']))
                {{ $data['partner']['address_2'] }}<br>
              @endif
              {{ $data['partner']['country'] }}
          </div>
          @if (!empty($data['partner']['phone']))
            <div style="margin-top: 8px; color: #555;">
                <span style="font-weight: bold;">Phone:</span> {{ $data['partner']['phone'] }}
            </div>
          @endif
      </td>
      <td style="width: 4%;">&nbsp;</td>
      <td style="width: 48%; vertical-align: top; background-color: #f9f9f9; padding: 15px; border-radius: 5px; border: 1px solid #eee;">
          <div style="font-size: 10px; text-transform: uppercase; color: #888; font-weight: bold; margin-bottom: 10px; letter-spacing: 1px;">Delivery Address</div>
          <div style="font-size: 14px; font-weight: bold; margin-bottom: 5px; color: #333;">{{ $data['delivery']['name'] }}</div>
          <div style="color: #555; line-height: 1.4;">
              {{ $data['delivery']['address_1'] }}<br>
              @if (!empty($data['delivery']['address_2']))
                {{ $data['delivery']['address_2'] }}<br>
              @endif
              {{ $data['delivery']['country'] }}
          </div>
          @if (!empty($data['delivery']['phone']))
            <div style="margin-top: 8px; color: #555;">
                <span style="font-weight: bold;">Phone:</span> {{ $data['delivery']['phone'] }}
            </div>
          @endif
      </td>
    </tr>
  </table>

  <div class="info-bar" style="margin-bottom: 10px;">
    <table style="width: 100%; border-collapse: separate; border-spacing: 0;">
      <tr>
        <td style="background-color: #f5f5f5; padding: 10px; border-radius: 5px; width: 14%;">
          <div style="font-size: 9px; text-transform: uppercase; color: #888; font-weight: bold;">Invoice Date</div>
          <div style="font-size: 11px; font-weight: bold; color: #333; margin-top: 3px;">{{ $data['invoice_date'] }}</div>
        </td>
        <td style="background-color: #f5f5f5; padding: 10px; border-radius: 5px; width: 14%;">
          <div style="font-size: 9px; text-transform: uppercase; color: #888; font-weight: bold;">Due Date</div>
          <div style="font-size: 11px; font-weight: bold; color: #333; margin-top: 3px;">{{ $data['due_date'] }}</div>
        </td>
        <td style="background-color: #f5f5f5; padding: 10px; border-radius: 5px; width: 14%;">
          <div style="font-size: 9px; text-transform: uppercase; color: #888; font-weight: bold;">Source</div>
          <div style="font-size: 11px; font-weight: bold; color: #333; margin-top: 3px;">{{ $data['source'] }}</div>
        </td>
        <td style="background-color: #f5f5f5; padding: 10px; border-radius: 5px; width: 14%;">
          <div style="font-size: 9px; text-transform: uppercase; color: #888; font-weight: bold;">PO #</div>
          <div style="font-size: 11px; font-weight: bold; color: #333; margin-top: 3px;">{{ $data['purchase_order'] ?: '-' }}</div>
        </td>
        <td style="background-color: #f5f5f5; padding: 10px; border-radius: 5px; width: 14%;">
          <div style="font-size: 9px; text-transform: uppercase; color: #888; font-weight: bold;">Salesperson</div>
          <div style="font-size: 11px; font-weight: bold; color: #333; margin-top: 3px;">{{ $data['salesperson'] }}</div>
        </td>
        <td style="width: 2%;"></td>
        <td style="background-color: #fff4e5; padding: 10px; border-radius: 5px; border: 1px solid #ffe0b2;">
          <div style="font-size: 9px; text-transform: uppercase; color: #d35400; font-weight: bold;">Payment Instructions</div>
          <div style="font-size: 10px; color: #555; margin-top: 3px; line-height: 1.3;">
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
        <th style="width: 50px; text-align: center;">Image</th>
        <th style="width: 40px; text-align: center;">Qty</th>
        <th style="width: 200px;">Sales Description</th>
        <th>UPC</th>
        <th>U.O.M.</th>
        <th>Box Price</th>
        <th>Per Unit Price</th>
        <th>Discount</th>
        <th class="text-right">Amount</th>
        <th>Tax</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($data['items'] as $item)
        <tr>
          <td>{{ $item['s_no'] }}</td>
          <td style="text-align: center;">
              @if(file_exists($item['image_path']))
                  <img src="{{ $item['image_path'] }}" style="width: 40px; height: 40px; object-fit: contain;">
              @else
                  -
              @endif
          </td>
          <td style="text-align: center;">{{ $item['quantity'] }}</td>
          <td>{{ $item['description'] }}</td>
          <td>{{ $item['upc'] }}</td>
          <td>{{ $item['uom'] }}</td>
          <td>${{ number_format($item['box_price'], 2) }}</td>
          <td>${{ number_format($item['unit_price'], 2) }}</td>
          <td>
            @if($item['discount'] > 0)
                ${{ number_format($item['discount'], 2) }}
            @else
                -
            @endif
          </td>
          <td class="text-right">${{ number_format($item['amount'], 2) }}</td>
          <td>
            @if($item['tax'] > 0)
                ${{ number_format($item['tax'], 2) }}
            @else
                -
            @endif
          </td>
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
