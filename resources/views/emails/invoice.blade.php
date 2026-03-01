<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 24px; }
        .header { border-bottom: 2px solid #C41E3A; padding-bottom: 16px; margin-bottom: 24px; }
        .amount { font-size: 1.25rem; font-weight: 700; color: #C41E3A; }
        .footer { margin-top: 32px; padding-top: 16px; font-size: 0.875rem; color: #6b7280; }
        a { color: #C41E3A; text-decoration: none; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="header">
        <h1 style="margin: 0; font-size: 1.5rem;">Invoice {{ $invoice->invoice_number }}</h1>
        <p style="margin: 8px 0 0 0; color: #6b7280;">{{ $invoice->invoice_date->format('F d, Y') }}</p>
    </div>

    <p>Hello {{ $invoice->order->user->name ?? ($invoice->order->shipping_address['name'] ?? 'Customer') }},</p>

    <p>Please find your invoice <strong>{{ $invoice->invoice_number }}</strong> attached to this email.</p>

    <p><strong>Amount due:</strong> <span class="amount">${{ number_format($invoice->total, 2) }}</span></p>
    <p><strong>Due date:</strong> {{ $invoice->due_date->format('F d, Y') }}</p>

    <p>If you have any questions about this invoice, please reply to this email.</p>

    <div class="footer">
        <p style="margin: 0;">Thank you for your business.</p>
        <p style="margin: 8px 0 0 0;">{{ config('app.name', 'Zam Zam Import Export') }}</p>
    </div>
</body>
</html>
