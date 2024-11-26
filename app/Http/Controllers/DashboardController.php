<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Barang;
use App\Models\BarangMasuk;
use App\Models\BarangKeluar;
use App\Http\Controllers\Controller;
use App\Models\Order;

class DashboardController extends Controller
{
    public function index()
    {
        $barangCount        = Barang::all()->count();
        $barangMasukCount   = BarangMasuk::all()->count();
        $barangKeluarCount  = BarangKeluar::all()->count();
        $userCount          = User::all()->count();
        $barangMasukPerBulan = BarangMasuk::selectRaw('DATE_FORMAT(tanggal_masuk, "%Y-%m") as date, SUM(jumlah_masuk) as total')
            ->groupBy('date')
            ->get()
            ->map(function ($data) {
                $data->date = date('Y-m', strtotime($data->date));
                $data->total = (int) $data->total;
                return $data;
        });
        $barangKeluarPerBulan = BarangKeluar::selectRaw('DATE_FORMAT(tanggal_keluar, "%Y-%m") as date, SUM(jumlah_keluar) as total')
            ->groupBy('date')
            ->get()
            ->map(function ($data) {
                $data->date = date('Y-m', strtotime($data->date));
                $data->total = (int) $data->total;
                return $data;
        });
    
        $barangMinimum = Barang::where('stok', '<=', 10)->get();

        $jumlahPermintaan = Order::count(); // Total permintaan
        $barangPalingBanyakDiminta = Order::select('nama_barang', \DB::raw('SUM(jumlah_permintaan) as total'))
        ->where('status', 'selesai')
        ->groupBy('nama_barang')
        ->orderBy('total', 'desc')
        ->take(5) 
        ->get();

        $total = $barangPalingBanyakDiminta->pluck('total')->map(function ($value) {
            return (int) $value; 
        })->toArray();
        $label = $barangPalingBanyakDiminta->pluck('nama_barang')->values()->all();

        // Debug data
        \Log::info('Chart Data:', ['total' => $total, 'label' => $label]);

        $orders = Order::where('status', 'menunggu_konfirmasi')->get(); 
        
        $ordersAdmin = Order::where('status', 'diterima')->get();
        
        return view('dashboard', [
            'barang'            => $barangCount,
            'barangMasuk'       => $barangMasukCount,
            'barangKeluar'      => $barangKeluarCount,
            'user'              => $userCount,
            'barangMasukData'   => $barangMasukPerBulan,
            'barangKeluarData'  => $barangKeluarPerBulan,
            'barangMinimum'     => $barangMinimum,
            'jumlahPermintaan'  => $jumlahPermintaan,
            'barangPalingBanyakDiminta' => $barangPalingBanyakDiminta,
            'orders'            => $orders,
            'ordersAdmin'       => $ordersAdmin,
            'total'             => $total, 
            'label'             => $label, 
        ]);
    }

}
