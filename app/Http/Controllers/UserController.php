<?php

namespace App\Http\Controllers;
use App\Http\Resources\UserResource;
use App\Models\User;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request) {
        $param = $request->param;
        $itemuser = $request->user();
        if ($param == 'pembeli') {
            $itemdata = User::where('role', 'pembeli')->latest()->paginate(20);
            $itemdata->withPath('/admin/user?param=pembeli');
            $content = array('data' => UserResource::collection($itemdata),
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

        if ($param == 'mitra') {
            $itemdata = User::where('role', 'mitra')->latest()->paginate(20);
            $itemdata->withPath('/admin/user?param=mitra');
            $content = array('data' => UserResource::collection($itemdata),
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

        $itemdata = User::latest()->where('id', '!=', $itemuser->id)->paginate(20);
        $content = array('data' => UserResource::collection($itemdata),
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
    public function register(Request $request) {
        $validator = \Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|unique:users,email|email',
            'password' => 'min:6|required',
            'phone' => 'required',
            'role' => 'required',
        ]);

        if ($validator->fails()) {
            $respon = [
                'status' => 'error',
                'msg' => 'Validation error',
                'content' => null,
                'errors' => $validator->errors()
            ];
            return response()->json($respon, 200);
        } else {
            $inputan = $request->all();
            $inputan['password'] = \Hash::make($request->password);
            $inputan['status'] = 'nonaktif';
            $itemuser = User::create($inputan);
            $itemuser->refresh();
            // dispatch(new RegisterJob($itemuser));
            $respon = [
                'status' => 'success',
                'msg' => 'Registrasi berhasil',
                'content' => $itemuser,
                'errors' => null
            ];
            return response()->json($respon, 200);
        }
    }

    public function profil(Request $request) {
        $itemuser = $request->user();
        $respon = new UserResource($itemuser);
        return response()->json($respon, 200);
    }

    public function updateprofil(Request $request) {
        $validator = \Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'required',
        ]);

        if ($validator->fails()) {
            $respon = [
                'status' => 'error',
                'msg' => 'Lengkapi form profil',
                'content' => null,
                'errors' => null
            ];
            return response()->json($respon, 200);
        } else {
            $itemuser = $request->user();
            $inputan['name'] = $request->name;
            $inputan['phone'] = $request->phone;
            $itemuser->update($inputan);
            $itemuser->refresh();
            $respon = [
                'status' => 'success',
                'msg' => 'Profil berhasil diupdate',
                'content' => new UserResource($itemuser),
                'errors' => null
            ];
            return response()->json($respon, 200);
        }
    }

    public function updatepassword(Request $request) {
        $validator = \Validator::make($request->all(), [
            'password_baru' => 'required|min:6',
            'password_baru_confirm' => 'required|min:6|same:password_baru',
        ]);

        if ($validator->fails()) {
            $respon = [
                'status' => 'error',
                'msg' => 'Lengkapi form profil',
                'content' => $validator->errors(),
                'errors' => null
            ];
            return response()->json($respon, 200);
        } else {
            $itemuser = $request->user();
            $password = \Hash::make($request->password_baru);
            $inputan['password'] = $password;
            $itemuser->update($inputan);
            $itemuser->tokens()->delete();//hapus semua token
            $respon = [
                'status' => 'success',
                'msg' => 'Password berhasil diupdate',
                'content' => null,
                'errors' => null
            ];
            return response()->json($respon, 200);
        }
    }

    public function status(Request $request, $id) {
        $itemuser = User::findOrFail($id);
        $itemuser->update(['status' => $request->status]);
        $itemuser->refresh();
        $respon = [
            'status' => 'success',
            'msg' => 'User berhasil diupdate',
            'content' => new UserResource($itemuser),
            'errors' => null
        ];
        return response()->json($respon, 200);
    }

    public function show(Request $request, $id) {
        $itemuser = User::findOrFail($id);
        $respon = [
            'status' => 'success',
            'msg' => 'User berhasil ditemukan',
            'content' => new UserResource($itemuser),
            'errors' => null
        ];
        return response()->json($respon, 200);
    }
}
