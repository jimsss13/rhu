<?php

namespace App\Http\Controllers;

use App\Services\ReceiptService;
use App\Support\PesoAmount;
use Illuminate\Support\Facades\Storage;

class ReceiptController extends Controller
{
    public function generateImage()
    {
        return view('receipt');
    }

    public function generateReceipt(ReceiptService $receipts)
    {
        $path = $receipts->generate([
            'r_date' => now()->format('m/d/Y'), 'r_no' => 'SAMPLE', 'r_series' => 'SAMPLE',
            'r_agency' => config('app.name'), 'r_payor' => 'Sample Payor',
            'r_nature' => 'Sample receipt', 'r_acc_code' => 'SAMPLE',
            'r_amount' => '1,000.00', 'r_total' => '1,000.00',
            'r_amount_word' => PesoAmount::inWords(1000),
            'r_teller' => config('services.receipt.teller', 'Teller Name'),
            'r_officer' => config('services.receipt.officer', 'Collecting Officer'),
        ]);

        return Storage::disk('public')->download($path, 'receipt.pdf');
    }
}
