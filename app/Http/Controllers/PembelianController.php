<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembelian;
use App\Http\Resources\PembelianResource;

class PembelianController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $param = $request->param;
        $itemuser = $request->user();
        if ($param == 'cart') {
            $itemdata = Pembelian::where('user_pembeli', $itemuser->id)
                                ->where('status', 'cart')
                                ->paginate(20);
            $itemdata->withPath('/admin/pembelian?param=cart');
            $content = array('data' => PembelianResource::collection($itemdata),
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

        if ($param == 'checkout') {
            $itemdata = Pembelian::where('user_pembeli', $itemuser->id)
                                ->where('status', 'checkout')
                                ->paginate(20);
            $itemdata->withPath('/admin/pembelian?param=checkout');
            $content = array('data' => PembelianResource::collection($itemdata),
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
        $itemdata = Pembelian::where('user_pembeli', $itemuser->id)
                                ->paginate(20);
        $content = array('data' => PembelianResource::collection($itemdata),
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // $itemuser = $request->user();
        $itempembelian = Pembelian::findOrFail($id);
        $respon = [
            'status' => 'success',
            'msg' => 'Data berhasil ditemukan',
            'content'=> new PembelianResource($itempembelian),
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
        // hanya proses update status menjadi checkout
        $itemuser = $request->user();
        $itempembelian = Pembelian::where('status', 'cart')
                                ->where('id', $id)
                                ->where('user_pembeli', $itemuser->id)
                                ->first();
        if ($itempembelian) {
            $itempembelian->update(['status' => 'checkout']);
            $itempembelian->refresh();
            $respon = [
                'status' => 'success',
                'msg' => 'Data berhasil ditemukan',
                'content'=> new PembelianResource($itempembelian),
                'errors' => null
            ];
            return response()->json($respon, 200);
        }
        $respon = [
            'status' => 'error',
            'msg' => 'Data tidak ditemukan',
            'content'=> null,
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
        $itempembelian = Pembelian::where('status', 'cart')->where('id', $id)->first();
        if ($itempembelian) {
            $itempembelian->detail()->delete();
            if ($itempembelian->delete()) {
                $respon = [
                    'status' => 'success',
                    'msg' => 'Data berhasil dihapus',
                    'content'=> null,
                    'errors' => null
                ];
                return response()->json($respon, 200);
            }
        }
    }
}
