<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PrintController extends Controller
{
    public function tandaTerima($id)
    {
        $data = Pesanan::with('user')->findOrFail($id);


        $pdf = Pdf::loadView('print.tanda-terima', compact('data'))
        ->setPaper([0, 0, 595, 467], 'portrait'); // 210x165 mm
        return $pdf->stream("tanda-terima-{$data->id}.pdf");
    }

    public function invoice($id)
    {
        // Load pesanan dengan invoice items (immutable snapshot)
        $data = Pesanan::with(['user', 'invoiceItems'])->findOrFail($id);

        $pdf = Pdf::loadView('print.invoice', compact('data'))
        ->setPaper('a4', 'portrait');
        return $pdf->stream("invoice-{$data->id}.pdf");
    }

}
