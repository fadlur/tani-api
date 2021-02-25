<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PembelianDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'pembelian_id' => $this->pembelian_id,
            'pembelian_subtotal' => $this->pembelian->subtotal,
            'pembelian_diskon' => $this->pembelian->diskon,
            'pembelian_total' => $this->pembelian->total,
            'produk_id' => $this->produk_id,
            'qty' => $this->qty,
            'harga' => $this->harga,
            'diskon' => $this->diskon,
            'subtotal' => $this->subtotal,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
