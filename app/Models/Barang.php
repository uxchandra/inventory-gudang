<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Barang extends Model
{
    use HasFactory;

    protected $fillable = ['kode_barang', 'nama_barang', 'deskripsi', 'gambar', 'stok_minimum', 'jenis_id', 'stok', 'satuan_id'];
    protected $guarded = [''];
    protected $ignoreChangedAttributes = ['updated_at'];


    // Satu Barang memiliki 1 jenis
    public function jenis()
    {
        return $this->belongsTo(Jenis::class, 'jenis_id');
    }

    // Satu Barang memiliki 1 satuan
    public function satuan()
    {
        return $this->belongsTo(Satuan::class, 'satuan_id');
    }
}
