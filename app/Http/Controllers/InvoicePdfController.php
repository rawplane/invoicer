<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class InvoicePdfController extends Controller
{
    public function stream(int $id): Response
    {
        $invoice = Auth::user()->invoices()
            ->with(['client', 'items'])
            ->findOrFail($id);

        $pdf = Pdf::loadView('pdf.invoice', [
            'invoice' => $invoice,
            'user' => Auth::user(),
        ]);

        return $pdf->stream("Invoice-{$invoice->invoice_number}.pdf");
    }
}
