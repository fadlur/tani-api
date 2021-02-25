<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembelianDetail extends Model
{
    use HasFactory;
    protected $table = 'pembelian_detail';
    protected $fillable = [
        'pembelian_id',
        'produk_id',
        'qty',
        'harga',
        'diskon',
        'subtotal',
    ];

    public function pembelian() {
        return $this->belongsTo('App\Models\Pembelian', 'pembelian_id');
    }

    public function produk() {
        return $this->belongsTo('App\Models\Produk', 'produk_id');
    }

    public function updatesubtotal($itemdetail, $qty, $subtotal) {
        $this->attributes['qty'] = $itemdetail->qty + $qty;
        $this->attributes['subtotal'] = $itemdetail->subtotal + $subtotal;
        self::save();
    }
}
