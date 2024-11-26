<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Order extends Model
{

    use HasFactory;

    protected $table = 'orders';


    protected $fillable = [
        'user_id',
        'nama_barang',
        'jumlah_permintaan',
        'satuan_id',
        'status',
        'tanggal'
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function satuan()
    {
        return $this->belongsTo(Satuan::class, 'satuan_id');
    }
}
