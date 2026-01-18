<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mccarlosen\LaravelMpdf\Facades\LaravelMpdf as PDF;

class InvoiceController extends Controller
{
    public function generatePdf()
    {
        $json = file_get_contents(resource_path('dummy-data/invoice.json'));
        $data = json_decode($json, true);

        $pdf = PDF::loadView('pdf.invoice', compact('data'), [], [
             'margin_top' => 55, // Increased to clear the header content
             'margin_bottom' => 20, 
             'margin_left' => 10,
             'margin_right' => 10,
             'margin_header' => 10,
             'margin_footer' => 10,
        ]);
        
        return $pdf->stream('invoice.pdf');
    }
}
