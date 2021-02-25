<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use App\Http\Resources\ProdukResource;

class ProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $itemuser = $request->user();
        if ($itemuser->role == 'mitra') {
            $itemdata = Produk::where('user_id', $itemuser->id)
                                ->latest()
                                ->paginate(20);            
            $content = array('data' => ProdukResource::collection($itemdata),
                            'meta'=> [
                                'current_page' => $itemdata->currentPage(),
                                'first_item' => $itemdata->firstItem(),
                                'last_item' => $itemdata->lastItem(),
                                "next_page_url" => $itemdata->nextPageUrl(),
                                "prev_page_url" => $itemdata->previousPageUrl(),
                                'current_page_url' => $itemdata->url($itemdata->currentPage()),
                                'last_page' => $itemdata->lastPage(),
                                "per_page" => $itemdata->perPage(),
                                "total" => $itemdata->total(),
                            ],
                        );
            $respon = [
                'status' => 'success',
                'msg' => 'Data ditemukan',
                'content'=> $content,
                'errors' => null
            ];
            return response()->json($respon, 200);
        }
        $itemdata = Produk::latest()->paginate(20);
        $content = array('data' => ProdukResource::collection($itemdata),
                            'meta'=> [
                                'current_page' => $itemdata->currentPage(),
                                'first_item' => $itemdata->firstItem(),
                                'last_item' => $itemdata->lastItem(),
                                "next_page_url" => $itemdata->nextPageUrl(),
                                "prev_page_url" => $itemdata->previousPageUrl(),
                                'current_page_url' => $itemdata->url($itemdata->currentPage()),
                                'last_page' => $itemdata->lastPage(),
                                "per_page" => $itemdata->perPage(),
                                "total" => $itemdata->total(),
                            ],
                        );
        $respon = [
            'status' => 'success',
            'msg' => 'Data ditemukan',
            'content'=> $content,
            'errors' => null
        ];
        return response()->json($respon, 200);
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
            'kode' => 'required|unique:produk',//validasi kode unique, kalau sudah ada maka akan error
            'nama' => 'required',
            'harga' => 'required',
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
            // kode nanti secara otomatis ditambahkan id user dari frontend
            $itemuser = $request->user();
            $inputan = $request->all();
            $inputan['harga'] = str_replace(',','', $request->harga);
            $inputan['user_id'] = $itemuser->id;
            $itemproduk = Produk::create($inputan);
            $itemproduk->refresh();
            $respon = [
                'status' => 'success',
                'msg' => 'Data berhasil diinput',
                'content'=> new ProdukResource($itemproduk),
                'errors' => null
            ];
            return response()->json($respon, 200);
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
        $itemproduk = Produk::findOrFail($id);
        $respon = [
            'status' => 'success',
            'msg' => 'Data berhasil diinput',
            'content'=> new ProdukResource($itemproduk),
            'errors' => null
        ];
        return response()->json($respon, 200);
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
            'kode' => 'required|unique:produk,kode,'.$id.',id',//validasi kode unique, tapi kalau id-nya sama dengan $id ya tetep diupdate
            'nama' => 'required',
            'harga' => 'required',
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
            $itemproduk = Produk::findOrFail($id);
            $itemuser = $request->user();
            $inputan = $request->all();
            $inputan['harga'] = str_replace(',','', $request->harga);
            $inputan['user_id'] = $itemuser->id;
            $itemproduk->update($inputan);
            $itemproduk->refresh();
            $respon = [
                'status' => 'success',
                'msg' => 'Data berhasil diupdate',
                'content'=> new ProdukResource($itemproduk),
                'errors' => null
            ];
            return response()->json($respon, 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $itemproduk = Produk::findOrFail($id);
        if ($itemproduk->delete()) {
            $respon = [
                'status' => 'success',
                'msg' => 'Data berhasil dihapus',
                'content'=> null,
                'errors' => null
            ];
            return response()->json($respon, 200);
        } else {
            $respon = [
                'status' => 'error',
                'msg' => 'Data gagal dihapus',
                'content'=> null,
                'errors' => null
            ];
            return response()->json($respon, 200);
        }
    }
}
