<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;

class KategoriController extends Controller
{
    public function createKategori(Request $request)
    {

        try {
            $request->validate([
                'kategori' => 'required|string|unique:kategoris,kategori'
            ]);

            $id = DB::table('kategoris')->insertGetId([
                'kategori' => $request->kategori,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return ResponseHelper::Success(
                'data kategori berhasil di tambahkan.',
                [
                    'id' => $id,
                    'kategori' => $request->kategori
                ]
            );
        } catch (\Exception $e) {

            return response()->json([
                'status' => false,
                'pesan' => 'Gagal menambahkan data kategori.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function editKategori(Request $request, $id)
    {
        try {

            $request->validate([
                'kategori' => 'required|string|unique:kategoris,kategori,' . $id,
            ]);

            $kategori = DB::table('kategoris')->where('id', $id)->first();

            if (!$kategori) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data kategori tidak ditemukan.'
                ], 404);
            }

            DB::table('kategoris')
                ->where('id', $id)
                ->update([
                    'kategori' => $request->kategori,
                    'updated_at' => now(),
                ]);

            return response()->json([
                'status' => true,
                'message' => 'Data kategori berhasil diubah.',
                'data' => [
                    'id' => $id,
                    'kategori' => $request->kategori
                ]
            ]);
        } catch (\Exception $errorKategori) {

            return response()->json([
                'status' => false,
                'message' => 'Gagal mengubah data kategori.',
                'error' => $errorKategori->getMessage()
            ], 500);
        }
    }

    public function kategoriDelete($id)
    {
        try {
            $kategori = DB::table('kategoris')->where('id', $id)->first();

            if (!$kategori) {
                return response()->json([
                    'status' => false,
                    'message' => 'data kategori gagal di hapus'
                ], 404);
            }

            DB::table('kategoris')
                ->where('id', $id)
                ->update([
                    'deleted_at' => now(),
                    'updated_at' => now()
                ]);

            return response()->json([
                'status' => true,
                'message' => 'data kategori berhasil di hapus,'
            ], 200);
        } catch (\Exception $errorKategori) {
            return response()->json([
                'status' => false,
                'message' => 'data kategori gagal di temukan.',
                'error' => $errorKategori->getMessage()
            ], 500);
        }
    }

    public function kategoriGet()
    {
        try {
            $kategoriGet = DB::table('kategoris')
                ->select(
                    'kategori'
                )
                ->whereNull('deleted_at')
                ->get();

            if ($kategoriGet) {
                return response()->json([
                    'status' => true,
                    'message' => 'data kategori tidak di temukan.'
                ], 404);
            }

            return response()->json([
                'status' => true,
                'message' => 'data berhasil di lihat.',
                'data' => $kategoriGet
            ], 200);
        } catch (\Exception $kategoriError) {
            return response()->json([
                'status' => true,
                'message' => 'data kategori tidak di temukan.',
                'error' => $kategoriError->getMessage()
            ], 404);
        }
    }

    public function kategoriSearch(Request $request)
    {
        try {
            $kategoriSearch = DB::table('kategoris')
                ->select(
                    'kategori'
                )
                ->whereNull('deleted_at')
                ->where('kategori', 'ILIKE', '%' . $request->nama_kategori . '%')
                ->get();

            if ($kategoriSearch->isEmpty()) {
                return response()->json([
                    'message' => 'data kategori tidak di temukan.'
                ], 404);
            }
            return response()->json([
                'status' => true,
                'message' => 'data kategori berhasil dilihat',
                'data' => $kategoriSearch
            ], 200);
        } catch (\Exception $kategoriError) {
            return response()->json([
                'status' => false,
                'message' => 'data kategori tidak di temukan.',
                'error' => $kategoriError->getMessage()
            ], 404);
        }
    }
}
