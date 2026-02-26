<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MailTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $templates = [
            [
                'type' => 'invoice',
                'subject' => 'Your Invoice from Zam Zam Import export Inc (Order #{order_id})',
                'content' => $this->getInvoiceHtml(),
                'is_active' => true,
            ],
            [
                'type' => 'credit_note',
                'subject' => 'Credit Note Issued for Order #{order_id}',
                'content' => $this->getCreditNoteHtml(),
                'is_active' => true,
            ],
            [
                'type' => 'order_confirmation',
                'subject' => 'Order Confirmation - #{order_id}',
                'content' => $this->getOrderConfirmationHtml(),
                'is_active' => true,
            ],
            [
                'type' => 'password_change',
                'subject' => 'Your Password Has Been Changed',
                'content' => $this->getPasswordChangeHtml(),
                'is_active' => true,
            ],
        ];

        foreach ($templates as $templateData) {
            \App\Models\MailTemplate::updateOrCreate(
                ['type' => $templateData['type']],
                $templateData
            );
        }
    }

    private function getInvoiceHtml(): string
    {
        return <<<HTML
<div style="font-family: Arial, sans-serif; color: #333; line-height: 1.6; max-width: 600px; margin: 0 auto; border: 1px solid #e0e0e0; border-radius: 8px; overflow: hidden;">
    <div style="background-color: #C41E3A; padding: 20px; text-align: center;">
        <h1 style="color: #ffffff; margin: 0; font-size: 24px;">Your Invoice is Ready</h1>
    </div>
    <div style="padding: 30px;">
        <p style="font-size: 16px;">Dear <strong>{user_name}</strong>,</p>
        <p style="font-size: 16px;">Thank you for your business. The invoice for your recent order <strong>#{order_id}</strong> is now available.</p>
        <p style="font-size: 16px;">Please find the details of your invoice attached to this email or accessible through your account dashboard.</p>
        
        <div style="margin-top: 30px; text-align: center;">
            <a href="{invoice_link}" style="background-color: #C41E3A; color: #ffffff; text-decoration: none; padding: 12px 25px; border-radius: 5px; font-weight: bold; display: inline-block;">View Invoice</a>
        </div>
        
        <hr style="border: none; border-top: 1px solid #eeeeee; margin: 30px 0;">
        <p style="font-size: 14px; color: #777;">If you have any questions regarding this invoice, please contact our support team.</p>
    </div>
    <div style="background-color: #f5f5f5; padding: 15px; text-align: center; font-size: 12px; color: #888;">
        &copy; {current_year} Zam Zam Import export Inc. All rights reserved.
    </div>
</div>
HTML;
    }

    private function getCreditNoteHtml(): string
    {
        return <<<HTML
<div style="font-family: Arial, sans-serif; color: #333; line-height: 1.6; max-width: 600px; margin: 0 auto; border: 1px solid #e0e0e0; border-radius: 8px; overflow: hidden;">
    <div style="background-color: #C41E3A; padding: 20px; text-align: center;">
        <h1 style="color: #ffffff; margin: 0; font-size: 24px;">Credit Note Issued</h1>
    </div>
    <div style="padding: 30px;">
        <p style="font-size: 16px;">Dear <strong>{user_name}</strong>,</p>
        <p style="font-size: 16px;">A credit note has been issued for your order <strong>#{order_id}</strong>.</p>
        <p style="font-size: 16px;">You can use this credit toward your future purchases or review the total applied amount from your account.</p>
        
        <div style="margin-top: 30px; text-align: center;">
            <a href="{credit_note_link}" style="background-color: #C41E3A; color: #ffffff; text-decoration: none; padding: 12px 25px; border-radius: 5px; font-weight: bold; display: inline-block;">View Details</a>
        </div>
        
        <hr style="border: none; border-top: 1px solid #eeeeee; margin: 30px 0;">
        <p style="font-size: 14px; color: #777;">Need help? Just reply to this email.</p>
    </div>
    <div style="background-color: #f5f5f5; padding: 15px; text-align: center; font-size: 12px; color: #888;">
        &copy; {current_year} Zam Zam Import export Inc.
    </div>
</div>
HTML;
    }

    private function getOrderConfirmationHtml(): string
    {
        return <<<HTML
<div style="font-family: Arial, sans-serif; color: #333; line-height: 1.6; max-width: 600px; margin: 0 auto; border: 1px solid #e0e0e0; border-radius: 8px; overflow: hidden;">
    <div style="background-color: #C41E3A; padding: 20px; text-align: center;">
        <h1 style="color: #ffffff; margin: 0; font-size: 24px;">Order Received</h1>
    </div>
    <div style="padding: 30px;">
        <p style="font-size: 16px;">Hi <strong>{user_name}</strong>,</p>
        <p style="font-size: 16px;">We have successfully received your order <strong>#{order_id}</strong>.</p>
        <p style="font-size: 16px;">We are currently processing it and will let you know once it has been shipped.</p>
        
        <div style="background-color: #fafafa; border-radius: 6px; padding: 15px; margin-top: 20px;">
            <p style="font-size: 14px; margin: 0;"><strong>Order Status:</strong> Pending</p>
            <p style="font-size: 14px; margin: 5px 0 0 0;"><strong>Total Amount:</strong> {order_total}</p>
        </div>

        <div style="margin-top: 30px; text-align: center;">
            <a href="{order_link}" style="background-color: #C41E3A; color: #ffffff; text-decoration: none; padding: 12px 25px; border-radius: 5px; font-weight: bold; display: inline-block;">View Order</a>
        </div>
    </div>
    <div style="background-color: #f5f5f5; padding: 15px; text-align: center; font-size: 12px; color: #888;">
        Thank you for shopping with us!
    </div>
</div>
HTML;
    }

    private function getPasswordChangeHtml(): string
    {
        return <<<HTML
<div style="font-family: Arial, sans-serif; color: #333; line-height: 1.6; max-width: 600px; margin: 0 auto; border: 1px solid #e0e0e0; border-radius: 8px; overflow: hidden;">
    <div style="background-color: #f8f9fa; padding: 20px; text-align: center; border-bottom: 2px solid #C41E3A;">
        <h1 style="color: #333333; margin: 0; font-size: 20px;">Security Alert</h1>
    </div>
    <div style="padding: 30px;">
        <p style="font-size: 16px;">Hello <strong>{user_name}</strong>,</p>
        <p style="font-size: 16px;">This is a confirmation that the password for your Zam Zam Import export Inc account was successfully changed recently.</p>
        <p style="font-size: 16px;">If you made this change, you don't need to do anything.</p>
        
        <div style="background-color: #fff3f3; border-left: 4px solid #C41E3A; padding: 15px; margin-top: 25px;">
            <p style="font-size: 14px; margin: 0; color: #bd362f;"><strong>Didn't change your password?</strong></p>
            <p style="font-size: 14px; margin: 5px 0 0 0;">Please contact our support team immediately to secure your account.</p>
        </div>
    </div>
    <div style="background-color: #f5f5f5; padding: 15px; text-align: center; font-size: 12px; color: #888;">
        This is an automated message, please do not reply directly to this email.
    </div>
</div>
HTML;
    }
}
