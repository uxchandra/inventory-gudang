<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Jenis extends Model
{
    use HasFactory;

    protected $fillable = ['jenis_barang'];
    protected $guarded = [''];
    protected $ignoreChangedAttributes = ['updated_at'];

    // 1 Jenis, dimiliki oleh banyak barang
    public function barangs()
    {
        return $this->hasMany(Barang::class);
    }
}
