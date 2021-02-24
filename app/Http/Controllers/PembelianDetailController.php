<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembelian;
use App\Models\PembelianDetail;
use App\Models\Produk;
use App\Http\Resources\PembelianDetailResource;

class PembelianDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'produk_id' => 'required',
            'qty' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            $respon = [
                'status' => 'error',
                'msg' => 'Validasi gagal',
                'content'=> null,
                'errors' => $validator->errors()
            ];
            return response()->json($respon, 200);
        } else {
            $itemuser = $request->user();//ambil data user yang login
            $itemproduk = Produk::findOrFail($request->produk_id);// ambil data produk
            $cart = Pembelian::where('user_pembeli', $itemuser->id)
                            ->where('status', 'cart')
                            ->first();
            if ($cart) {// langsung input pembelian detail
                $itemcart = $cart;
            } else {// bikin cart dulu
                $no_invoice = Pembelian::where('user_mitra', $itemproduk->user_id)->count();
                $itemcart = Pembelian::create([
                    'user_pembeli' => $itemuser->id,
                    'user_mitra' => $itemproduk->user_id,
                    'tanggal_transaksi' => date('Y-m-d'),
                    'no_invoice' => str_pad($no_invoice, '3', '0')
                ]);
            }
            $subtotal = $request->qty * $itemproduk->harga;//hitung subtotal
            $itemdetail = PembelianDetail::where('pembelian_id', $itemcart->id)
                                        ->where('produk_id', $itemproduk->id)
                                        ->first();
            if ($itemdetail) {//update qty sama subtotal aja
                // tambah qty item
                $itemdetail->updatesubtotal($itemdetail, $request->qty, $subtotal);
                $itemdetail->pembelian->updatetotal($itemdetail->pembelian, $subtotal);
                $itemdetail->refresh();
                $respon = [
                    'status' => 'success',
                    'msg' => 'Produk berhasil disimpan',
                    'content'=> new PembelianDetailResource($itemdetail),
                    'errors' => null
                ];
                return response()->json($respon, 200);
            } else {//input item baru ke cart
                $inputan['pembelian_id'] = $itemcart->id;
                $inputan['produk_id'] = $itemproduk->id;
                $inputan['qty'] = $request->qty;
                $inputan['harga'] = $itemproduk->harga;
                $inputan['subtotal'] = $subtotal;
                $itempembeliandetail = PembelianDetail::create($inputan);
                $itempembeliandetail->pembelian->updatetotal($itempembeliandetail->pembelian, $subtotal);
                $itempembeliandetail->refresh();
                $respon = [
                    'status' => 'success',
                    'msg' => 'Produk berhasil disimpan',
                    'content'=> new PembelianDetailResource($itempembeliandetail),
                    'errors' => null
                ];
                return response()->json($respon, 200);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = \Validator::make($request->all(), [
            'qty' => 'required|numeric'
        ]);
        $itempembeliandetail = PembelianDetail::findOrFail($id);
        // hapus dulu semua qty dan subtotalnya
        $subtotallama = $itempembeliandetail->subtotal;
        $itempembeliandetail->updatesubtotal($itempembeliandetail, '-'.$itempembeliandetail->qty, '-'.$subtotallama);
        $itempembeliandetail->pembelian->updatetotal($itempembeliandetail->pembelian, '-'.$subtotallama);
        // end hapus dulu semua qty dan subtotalnya
        $itempembeliandetail->refresh();
        $subtotal = $itempembeliandetail->harga * $request->qty;
        $itempembeliandetail->updatesubtotal($itempembeliandetail, $request->qty, $subtotal);
        $itempembeliandetail->pembelian->updatetotal($itempembeliandetail->pembelian, $subtotal);
        $itempembeliandetail->refresh();
        $respon = [
            'status' => 'success',
            'msg' => 'Produk berhasil disimpan',
            'content'=> new PembelianDetailResource($itempembeliandetail),
            'errors' => null
        ];
        return response()->json($respon, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $itempembeliandetail = PembelianDetail::findOrFail($id);
        $itempembeliandetail->pembelian->updatetotal($itempembeliandetail->pembelian, '-'.$itempembeliandetail->subtotal);
        $itempembeliandetail->delete();
        $respon = [
            'status' => 'success',
            'msg' => 'Item berhasil dihapus',
            'content'=> null,
            'errors' => null
        ];
        return response()->json($respon, 200);
    }
}
