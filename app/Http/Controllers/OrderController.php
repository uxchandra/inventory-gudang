<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Satuan;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class OrderController extends Controller
{
    public function index()
    {
        return view('permintaan-produk.index', [
            'orders' => Order::with('satuan')->get(), 
            'satuans' => Satuan::all(),
            'barangs' => Barang::all()
        ]);
    }

    public function getData()
    {
        // Mengambil semua data order
        $orders = Order::with('satuan')->get();

        return response()->json(['data' => $orders]);
    }

    public function store(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'nama_barang' => 'required|string|max:255',
            'jumlah_permintaan' => 'required|integer|min:1',
            'tanggal' => 'required|date'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Membuat order baru
        $order = Order::create([
            'user_id' => auth()->id(),
            'nama_barang' => $request->nama_barang,
            'jumlah_permintaan' => $request->jumlah_permintaan,
            'tanggal' => $request->tanggal,
            'status' => 'menunggu_konfirmasi' // Status default
        ]);

        return response()->json(['message' => 'Permintaan barang berhasil diajukan!', 'order' => $order], 201);
    }


    public function show($id)
    {
        // Menampilkan detail order berdasarkan ID
        $order = Order::with('satuans')->findOrFail($id);
        return response()->json($order);
    }


    public function destroy($id)
    {
        // Menghapus order
        $order = Order::findOrFail($id);
        $order->delete();

        return response()->json(['message' => 'Permintaan barang berhasil dihapus!']);
    }

    public function approve($id)
    {
        $order = Order::findOrFail($id);
        $order->status = 'diterima'; 
        $order->save();

        return response()->json(['message' => 'Permintaan barang telah diterima!']);
    }

    public function reject($id)
    {
        $order = Order::findOrFail($id);
        $order->status = 'ditolak'; 
        $order->save();

        return response()->json(['message' => 'Permintaan barang telah ditolak!']);
    }

    public function selesaikan($id)
    {

        $order = Order::findOrFail($id);

        $order->status = 'selesai'; 
        $order->save();


        return response()->json(['message' => 'Permintaan barang telah diselesaikan!']);
    }

    public function getAutoCompleteData(Request $request)
    {
        $barang = Barang::where('nama_barang', $request->nama_barang)->first();

        if($barang){
            return response()->json([
                'nama_barang'   => $barang->nama_barang,
                'satuan_id'     => $barang->satuan_id,
            ]);
        }
    }

    public function getSatuan()
    {
        $satuans = Satuan::all();
        
        return response()->json($satuans);
    }

    public function getBarangs(Request $request)
    {
        if ($request->has('q')) {
            $barangs = Barang::where('nama_barang', 'like', '%' . $request->input('q') . '%')->get();
            return response()->json($barangs);
        }

        return response()->json([]);
    }

}
