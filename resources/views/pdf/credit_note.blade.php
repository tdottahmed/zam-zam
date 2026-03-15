<!DOCTYPE html>
<html>

<head>
  <title>Credit Note</title>
  <style>
    body {
      font-family: 'Helvetica', 'Arial', sans-serif;
      font-size: 12px;
      color: #000;
    }

    @page {
      header: page-header;
      footer: page-footer;
      margin-top: 100px;
    }

    @page :first {
      header: page-header;
      footer: page-footer;
    }

    htmlpagefooter {
      text-align: center;
      font-size: 10px;
      color: #777;
    }

    .page-number:before {
      content: "Page " counter(page);
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
      height: 80px;
      width: auto;
      max-height: none;
      margin-top: 10px;
    }

    .invoice-title {
      font-size: 18px;
      font-weight: bold;
      margin: 10px 0;
      color: #555;
      text-transform: uppercase;
    }

    /* Table */
    table {
      width: 100%;
      border-collapse: collapse;
      font-size: 11px;
    }

    th,
    td {
      text-align: left;
      padding: 8px 5px;
      border-bottom: 1px solid #ccc;
    }

    th {
      font-weight: bold;
      color: #333;
      background-color: #f9f9f9;
    }

    .text-right {
      text-align: right;
    }

    .text-center {
      text-align: center;
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
    <div style="padding-bottom: 12px; padding-top: 10px;">
      <table style="width: 100%; border-collapse: collapse; border: none;">
        <tr style="border:none;">
          <!-- Left Side: Logo -->
          <td style="width: 60%; vertical-align: middle; text-align: left; padding: 0;">
            <img src="{{ public_path('images/zamzam-new-logo.png') }}" alt="Zamzam Logo"
                 style="height: 80px; width: auto;">
          </td>
          <!-- Right Side: Motto -->
          <td style="width: 40%; vertical-align: middle; text-align: right; padding: 0;">
            <div style="display: inline-block; text-align: right;">
              <div
                   style="margin: 0; font-size: 10px; font-style: italic; font-weight: 900; color: #cc0000; letter-spacing: 2px; text-transform: uppercase;">
                PURE DESHI TASTE
              </div>
              <div style="border-bottom: 3px solid #d35400; margin-top: 4px;"></div>
            </div>
          </td>
        </tr>
      </table>
    </div>
  </htmlpageheader>

  <!-- Define Footer -->
  <htmlpagefooter name="page-footer">
    <div class="page-footer-content">
      <div>{{ $data['company']['name'] }} | {!! strip_tags(str_replace(['<br />', '<br>', '<br/>'], ', ', $data['company']['address'])) !!} | Phone: {{ $data['company']['phone'] }} | Cell:
        {{ $data['company']['cell'] }}</div>
      <div>Email: {{ $data['company']['email'] }} | Web: http://www.zamzamcanada.com | HST:
        {{ $data['company']['tax_id'] }}
      </div>
      <div style="margin-top: 5px;">Page: {PAGENO} / {nbpg}</div>
    </div>
  </htmlpagefooter>

  <!-- Content Body -->
  <sethtmlpageheader name="page-header" value="on" />

  <div class="invoice-title">Credit Note {{ $data['credit_note_number'] }}</div>

  <table style="width: 100%; margin-bottom: 10px; border-spacing: 0;">
    <tr style="border: none;">
      <td
          style="width: 48%; vertical-align: top; background-color: #f9f9f9; padding: 15px; border-radius: 5px; border: 1px solid #eee;">
        <div
             style="font-size: 10px; text-transform: uppercase; color: #888; font-weight: bold; margin-bottom: 10px; letter-spacing: 1px;">
          Customer Details</div>
        <div style="font-size: 14px; font-weight: bold; margin-bottom: 5px; color: #333;">
          {{ $data['customer']['name'] }}</div>
        <div style="color: #555; line-height: 1.4;">
          {{ $data['customer']['email'] }}
        </div>
        @if (!empty($data['customer']['phone']))
          <div style="margin-top: 8px; color: #555;">
            <span style="font-weight: bold;">Phone:</span> {{ $data['customer']['phone'] }}
          </div>
        @endif
      </td>
      <td style="width: 4%;">&nbsp;</td>
      <td
          style="width: 48%; vertical-align: top; background-color: #f9f9f9; padding: 15px; border-radius: 5px; border: 1px solid #eee;">
        <div
             style="font-size: 10px; text-transform: uppercase; color: #888; font-weight: bold; margin-bottom: 10px; letter-spacing: 1px;">
          Credit Note Information</div>
        <div style="margin-bottom: 5px; color: #333;">
          <span style="font-weight: bold; display: inline-block; width: 100px;">Date Generated:</span>
          {{ $data['date'] }}
        </div>
        <div style="margin-bottom: 5px; color: #333;">
          <span style="font-weight: bold; display: inline-block; width: 100px;">Status:</span>
          {{ ucfirst($data['status']) }}
        </div>
        <div style="margin-bottom: 5px; color: #333;">
          <span style="font-weight: bold; display: inline-block; width: 100px;">Reason:</span> {{ $data['reason'] }}
        </div>
      </td>
    </tr>
  </table>

  <br>

  <table>
    <thead>
      <tr class="items-header">
        <th style="width: 30px;">S.No</th>
        <th style="width: 250px;">Product Details</th>
        <th class="text-center" style="width: 60px;">Source Order</th>
        <th class="text-center" style="width: 80px;">Reason</th>
        <th class="text-center" style="width: 50px;">Qty</th>
        <th class="text-right" style="width: 80px;">Unit Price</th>
        <th class="text-right">Total Amount</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($data['items'] as $item)
        <tr>
          <td>{{ $loop->iteration }}</td>
          <td>
            <div style="font-weight: bold;">{{ $item['product_name'] }}</div>
            <div style="font-size: 9px; color: #666; margin-top: 2px;">Code: {{ $item['product_code'] }}</div>
          </td>
          <td class="text-center">
            @if ($item['order_id'])
              #{{ $item['order_id'] }}
            @else
              Independent
            @endif
          </td>
          <td class="text-center">{{ $item['reason'] ?? 'Standard' }}</td>
          <td class="text-center">{{ $item['quantity'] }}</td>
          <td class="text-right">${{ number_format($item['unit_price'], 2) }}</td>
          <td class="text-right">${{ number_format($item['amount'], 2) }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>

  <div class="totals-section">
    <div style="border-top: 1px solid #ccc; padding-top: 10px; margin-top: 10px;"></div>

    <div style="float: left; width: 50%;">
      <div>Total Items Refunded: {{ $data['total_items'] }}</div>
      @if (!empty($data['admin_notes']))
        <div style="margin-top: 20px;">
          <strong>Admin Notes:</strong><br>
          {{ $data['admin_notes'] }}
        </div>
      @endif
    </div>

    <div class="amounts">
      <div style="padding-bottom: 5px; margin-bottom: 5px;">
        <table style="width: 100%; font-size: 15px; font-weight: bold;">
          <tr>
            <td>Total Credit</td>
            <td class="text-right">${{ number_format($data['total_amount'], 2) }}</td>
          </tr>
        </table>
      </div>
    </div>
    <div class="clearfix"></div>
  </div>

</body>

</html>
