<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\OfflinePaymentMethod;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $methods = [
            [
                'name' => 'E-Transfer',
                'description' => "Please send your payment via E-Transfer and provide the code number.\n\nSend E-Transfer to payments@example.com.",
                'required_fields' => [
                    ['name' => 'code_number', 'label' => 'Code / Reference Number', 'type' => 'text', 'is_required' => true],
                    ['name' => 'receipt', 'label' => 'Receipt Screenshot', 'type' => 'file', 'is_required' => false],
                ],
                'is_active' => true,
            ],
            [
                'name' => 'Bank Cheques',
                'description' => "Please provide the cheque number you are mailing to us.\n\nMail cheque to 123 Main St, City, Country.",
                'required_fields' => [
                    ['name' => 'cheque_number', 'label' => 'Cheque Number', 'type' => 'text', 'is_required' => true],
                    ['name' => 'image', 'label' => 'Cheque Image', 'type' => 'file', 'is_required' => false],
                ],
                'is_active' => true,
            ],
            [
                'name' => 'Bank Transfer',
                'description' => "Direct bank transfer. Please provide bank name and 12-digit transaction ID.\n\nTransfer to Acc: 000-1234-5678, Bank: TestBank.",
                'required_fields' => [
                    ['name' => 'bank_name', 'label' => 'Bank Name', 'type' => 'text', 'is_required' => true],
                    ['name' => 'transaction_id', 'label' => '12 Digit Transaction ID', 'type' => 'text', 'is_required' => true],
                    ['name' => 'receipt', 'label' => 'Bank Receipt', 'type' => 'file', 'is_required' => false],
                ],
                'is_active' => true,
            ],
        ];

        foreach ($methods as $method) {
            OfflinePaymentMethod::updateOrCreate(
                ['name' => $method['name']],
                $method
            );
        }
    }
}
