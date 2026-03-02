<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333333;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
        }
        .wrapper {
            width: 100%;
            table-layout: fixed;
            background-color: #f4f4f4;
            padding: 40px 0;
        }
        .main-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }
        .header {
            background-color: #1a1a1a;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            color: #ffffff;
            margin: 0;
            font-size: 24px;
            font-weight: 500;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        .content {
            padding: 40px 30px;
        }
        .greeting {
            font-size: 18px;
            margin-bottom: 20px;
            color: #1a1a1a;
        }
        .message {
            font-size: 15px;
            color: #555555;
            margin-bottom: 30px;
        }
        .invoice-card {
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 25px;
            margin-bottom: 30px;
        }
        .invoice-card-title {
            font-size: 12px;
            text-transform: uppercase;
            color: #6b7280;
            font-weight: 600;
            letter-spacing: 0.5px;
            margin-bottom: 15px;
        }
        .invoice-detail {
            display: table;
            width: 100%;
            margin-bottom: 10px;
        }
        .detail-label {
            display: table-cell;
            color: #6b7280;
            font-size: 14px;
            width: 40%;
        }
        .detail-value {
            display: table-cell;
            color: #111827;
            font-size: 15px;
            font-weight: 500;
            text-align: right;
            width: 60%;
        }
        .amount-row {
            border-top: 1px solid #e5e7eb;
            padding-top: 15px;
            margin-top: 15px;
        }
        .amount-value {
            font-size: 20px;
            color: #C41E3A;
            font-weight: 700;
        }
        .cta-container {
            text-align: center;
            margin: 40px 0;
        }
        .btn {
            display: inline-block;
            background-color: #C41E3A;
            color: #ffffff;
            text-decoration: none;
            padding: 14px 28px;
            border-radius: 4px;
            font-size: 15px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .btn:hover {
            background-color: #a01830;
        }
        .attachment-note {
            font-size: 13px;
            color: #6b7280;
            text-align: center;
            margin-bottom: 20px;
            display: block;
        }
        .footer {
            background-color: #f9fafb;
            padding: 30px;
            text-align: center;
            border-top: 1px solid #e5e7eb;
        }
        .footer-text {
            font-size: 13px;
            color: #6b7280;
            margin: 0;
        }
        .bold {
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="main-container">
            <div class="header">
                <h1>{{ config('app.name', 'Zam Zam Import Export') }}</h1>
            </div>
            
            <div class="content">
                @php
                    $customerName = $invoice->order->user->name ?? ($invoice->order->shipping_address['name'] ?? 'Customer');
                @endphp
                
                <div class="greeting">Hello {{ $customerName }},</div>
                
                <div class="message">
                    Thank you for your business. We have generated an invoice for your recent order. Please review the details below.
                </div>
                
                <div class="invoice-card">
                    <div class="invoice-card-title">Invoice Summary</div>
                    
                    <div class="invoice-detail">
                        <span class="detail-label">Invoice Number</span>
                        <span class="detail-value">{{ $invoice->invoice_number }}</span>
                    </div>
                    
                    <div class="invoice-detail">
                        <span class="detail-label">Order Number</span>
                        <span class="detail-value">#{{ $invoice->order->order_number ?? $invoice->order->id }}</span>
                    </div>
                    
                    <div class="invoice-detail">
                        <span class="detail-label">Invoice Date</span>
                        <span class="detail-value">{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('M d, Y') }}</span>
                    </div>
                    
                    <div class="invoice-detail">
                        <span class="detail-label">Due Date</span>
                        <span class="detail-value">{{ \Carbon\Carbon::parse($invoice->due_date)->format('M d, Y') }}</span>
                    </div>
                    
                    <div class="invoice-detail amount-row">
                        <span class="detail-label">Amount Due</span>
                        <span class="detail-value amount-value">${{ number_format($invoice->total, 2) }}</span>
                    </div>
                </div>
                
                <div class="cta-container">
                    <a href="{{ route('orders.download-invoice', $invoice->order->id) }}" class="btn">View Online Invoice</a>
                </div>
                
                <span class="attachment-note">
                    A PDF version of your invoice is securely attached to this email.
                </span>
            </div>
            
            <div class="footer">
                <p class="footer-text">
                    This is an automated message. For inquiries regarding this invoice, please reply to this email or contact support.<br><br>
                    &copy; {{ date('Y') }} {{ config('app.name', 'Zam Zam Import Export Inc.') }}. All Rights Reserved.
                </p>
            </div>
        </div>
    </div>
</body>
</html>
