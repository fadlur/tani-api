<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;
    protected $table = 'produk';
    protected $fillable = [
        'user_id',
        'kode',
        'nama',
        'harga',
        'deskripsi',
    ];

    public function user() {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
}
