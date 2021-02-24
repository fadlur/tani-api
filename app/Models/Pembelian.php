<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembelian extends Model
{
    use HasFactory;
    protected $table = 'pembelian';
    protected $fillable = [
        'user_mitra',
        'user_pembeli',
        'no_invoice',
        'subtotal',
        'diskon',
        'total',
        'tanggal_transaksi',
        'status',
    ];

    public function mitra() {
        return $this->belongsTo('App\Models\User', 'user_mitra');
    }

    public function pembeli() {
        return $this->belongsTo('App\Models\User', 'user_pembeli');
    }

    public function detail() {
        return $this->hasMany('App\Models\PembelianDetail', 'pembelian_id');
    }

    public function updatetotal($itempembelian, $subtotal) {
        $this->attributes['subtotal'] = $itempembelian->subtotal + $subtotal;
        $this->attributes['total'] = $itempembelian->total + $subtotal;
        self::save();
    }
}
