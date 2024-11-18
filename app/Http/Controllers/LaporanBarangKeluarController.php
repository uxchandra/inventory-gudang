<?php

namespace App\Http\Controllers;

use Dompdf\Dompdf;
use App\Models\Customer;
use App\Models\BarangKeluar;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LaporanBarangKeluarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('laporan-barang-keluar.index');
    }

    /**
     * Get Data 
     */
    public function getData(Request $request)
    {
        $tanggalMulai = $request->input('tanggal_mulai');
        $tanggalSelesai = $request->input('tanggal_selesai');
    
        $barangKeluar = BarangKeluar::query();
    
        if ($tanggalMulai && $tanggalSelesai) {
            $barangKeluar->whereBetween('tanggal_keluar', [$tanggalMulai, $tanggalSelesai]);
        }
    
        $data = $barangKeluar->get();

        if (empty($tanggalMulai) && empty($tanggalSelesai)) {
            $data = BarangKeluar::all();
        }
    
        return response()->json($data);
    }

    /**
     * Print DomPDF
     */
    public function printBarangKeluar(Request $request)
    {
        $tanggalMulai = $request->input('tanggal_mulai');
        $tanggalSelesai = $request->input('tanggal_selesai');
    
        $barangKeluar = BarangKeluar::query();
    
        if ($tanggalMulai && $tanggalSelesai) {
            $barangKeluar->whereBetween('tanggal_keluar', [$tanggalMulai, $tanggalSelesai]);
        }
    
        if ($tanggalMulai !== null && $tanggalSelesai !== null) {
            $data = $barangKeluar->get();
        } else {
            $data = BarangKeluar::all();
        }
        
        //Generate PDF
        $dompdf = new Dompdf();
        $html = view('/laporan-barang-keluar/print-barang-keluar', compact('data', 'tanggalMulai', 'tanggalSelesai'))->render();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream('print-barang-keluar.pdf', ['Attachment' => false]);
    }


}
