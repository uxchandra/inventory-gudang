<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Dompdf\Dompdf;

class LaporanPermintaanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Mengakses view untuk laporan permintaan
        return view('laporan-permintaan.index');
    }

    /**
     * Get Data 
     */
    public function getData(Request $request)
    {
        $tanggalMulai = $request->input('tanggal_mulai');
        $tanggalSelesai = $request->input('tanggal_selesai');

        $permintaan = Order::where('status', 'selesai'); 

        // Filter data berdasarkan rentang tanggal
        if ($tanggalMulai && $tanggalSelesai) {
            $permintaan->whereBetween('tanggal', [$tanggalMulai, $tanggalSelesai]);
        }

        // Ambil data
        $data = $permintaan->get();

        return response()->json($data);
    }


    /**
     * Print DomPDF
     */
    public function printPermintaan(Request $request)
    {
        $tanggalMulai = $request->input('tanggal_mulai');
        $tanggalSelesai = $request->input('tanggal_selesai');

        $permintaan = Order::where('status', 'selesai'); 

        // Filter data berdasarkan rentang tanggal
        if ($tanggalMulai && $tanggalSelesai) {
            $permintaan->whereBetween('tanggal', [$tanggalMulai, $tanggalSelesai]);
        }

        $data = $permintaan->get();

        // Generate PDF
        $dompdf = new Dompdf();
        $html = view('laporan-permintaan.print-permintaan', compact('data', 'tanggalMulai', 'tanggalSelesai'))->render();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream('print-permintaan.pdf', ['Attachment' => false]);
    }

}