<?php

namespace App\Http\Controllers;

use Dompdf\Dompdf;
use App\Models\Supplier;
use App\Models\BarangMasuk;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LaporanBarangMasukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('laporan-barang-masuk.index');
    }

    /**
     * Get Data 
     */
    public function getData(Request $request)
    {
        $tanggalMulai = $request->input('tanggal_mulai');
        $tanggalSelesai = $request->input('tanggal_selesai');
    
        $barangMasuk = BarangMasuk::query();
    
        if ($tanggalMulai && $tanggalSelesai) {
            $barangMasuk->whereBetween('tanggal_masuk', [$tanggalMulai, $tanggalSelesai]);
        }
    
        $data = $barangMasuk->get();

        if (empty($tanggalMulai) && empty($tanggalSelesai)) {
            $data = BarangMasuk::all();
        }
    
        return response()->json($data);
    }
    
    /**
     * Print DomPDF
     */
    public function printBarangMasuk(Request $request)
    {
        $tanggalMulai = $request->input('tanggal_mulai');
        $tanggalSelesai = $request->input('tanggal_selesai');
    
        $barangMasuk = BarangMasuk::query();
    
        if ($tanggalMulai && $tanggalSelesai) {
            $barangMasuk->whereBetween('tanggal_masuk', [$tanggalMulai, $tanggalSelesai]);
        }
    
        if ($tanggalMulai !== null && $tanggalSelesai !== null) {
            $data = $barangMasuk->get();
        } else {
            $data = BarangMasuk::all();
        }
        
        //Generate PDF
        $dompdf = new Dompdf();
        $html = view('/laporan-barang-masuk/print-barang-masuk', compact('data', 'tanggalMulai', 'tanggalSelesai'))->render();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream('print-barang-masuk.pdf', ['Attachment' => false]);
    }
    

}
