<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Satuan extends Model
{
    use HasFactory;

    protected $table = 'satuans'; 
    protected $fillable = ['satuan'];
    protected $guarded = [''];
    protected $ignoreChangedAttributes = ['updated_at'];

    
    // 1 satuan, dimiliki oleh banyak barang
    public function barangs()
    {
        return $this->hasMany(Barang::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
