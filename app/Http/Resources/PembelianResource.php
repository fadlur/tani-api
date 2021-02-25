<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PembelianResource extends JsonResource
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
            'user_mitra' => $this->user_mitra,
            'user_mitra_id' => $this->mitra->id,
            'user_mitra_name' => $this->mitra->name,
            'user_mitra_phone' => $this->mitra->phone,
            'user_mitra_email' => $this->mitra->email,
            'user_mitra_role' => $this->mitra->role,
            'user_mitra_status' => $this->mitra->status,
            'user_mitra_pembeli' => $this->user_pembeli,
            'user_pembeli_id' => $this->pembeli->id,
            'user_pembeli_name' => $this->pembeli->name,
            'user_pembeli_phone' => $this->pembeli->phone,
            'user_pembeli_email' => $this->pembeli->email,
            'user_pembeli_role' => $this->pembeli->role,
            'user_pembeli_status' => $this->pembeli->status,
            'no_invoice' => $this->no_invoice,
            'subtotal' => $this->subtotal,
            'diskon' => $this->diskon,
            'total' => $this->total,
            'tanggal_transaksi' => $this->tanggal_transaksi,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'detail' => PembelianDetailResource::collection($this->detail)
        ];
    }
}
