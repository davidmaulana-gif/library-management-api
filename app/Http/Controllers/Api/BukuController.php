<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BukuController extends Controller
{
    public function createBuku(Request $request)
    {

        try {

            $request->validate([
                'nama_buku' => 'required|string|unique:bukus,nama_buku',
                'jumlah' => 'required|integer'
            ]);
            DB::table('bukus')->insertGetId([
                'nama_buku' => $request->nama_buku,
                'kategori_id' => $request->kategori_id,
                'jumlah' => $request->jumlah,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Data buku berhasil di tambahkan ',
                'data' => [
                    'nama_buku' => $request->nama_buku,
                    'kategori_id' => $request->kategori_id,
                    'jumlah' => $request->jumlah,
                ]
            ], 201);
        } catch (\Exception $bukuError) {

            return response()->json([
                'status' => false,
                'pesan' => 'Gagal menambahkan data',
                'error' => $bukuError->getMessage()
            ], 500);
        }
    }

    public function bukuGet()
    {
        try {
            $bukuGet = DB::table('bukus')
                ->select(
                    'bukus.nama_buku',
                    DB::raw("
                        CASE
                            WHEN kategoris.deleted_at IS NULL THEN kategoris.kategori
                            ELSE 'terhapus'
                        END AS kategori
                    "),
                    'bukus.jumlah'
                )
                ->join('kategoris', 'bukus.kategori_id', '=', 'kategoris.id')
                ->whereNull('bukus.deleted_at')
                ->get();

            if ($bukuGet->isEmpty()) {
                return response()->json([
                    'status' => true,
                    'message' => 'data buku tidak di temukan.'
                ], 404);
            }

            return response()->json([
                'status' => true,
                'message' => 'data buku berhasil di lihat.',
                'data' => $bukuGet
            ], 200);
        } catch (\Exception $bukuError) {
            return response()->json([
                'status' => true,
                'message' => 'data bukus tidak di temukan.',
                'error' => $bukuError->getMessage()
            ], 404);
        }
    }

    public function searchBuku(Request $request)
    {
        try {
            $buku = DB::table('bukus')
                ->select(
                    'bukus.nama_buku',
                    'bukus.jumlah',
                    'kategoris.kategori'
                )
                ->join('kategoris', 'bukus.kategori_id', 'kategoris.id')
                ->where('bukus.nama_buku', $request->nama_buku)
                ->get();

            if ($buku->isEmpty())
                return response()->json([
                    'message' => 'data buku tidak temukan'
                ]);

            return response()->json([
                'status' => true,
                'data' => $buku
            ]);
        } catch (\Exception $bukuError) {
            return response()->json([
                'status' => false,
                'pesan' => 'Gagal menampilkan data buku .',
                'error' => $bukuError->getMessage()
            ], 500);
        }
    }

    public function editBuku(Request $request, $id)
    {
        try {
            $request->validate([
                'nama_buku' => 'required|string|unique:bukus,nama_buku,' . $id,
            ]);

            $bukus = DB::table('bukus')->where('id', $id)->first();

            if (!$bukus) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data buku tidak di temukan.'
                ], 404);
            }

            DB::table('bukus')
                ->where('id', $id)
                ->update([
                    'nama_buku' => $request->nama_buku,
                    'bukus_id' => $request->bukus_id,
                    'jumlah' => $request->jumlah,
                    'updated_at' => now()
                ]);

            return response()->json([
                'status' => true,
                'message' => 'Data buku berhasil di ubah'
            ], 200);
        } catch (\Exception $errorBuku) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal mengubah data buku.',
                'error' => $errorBuku->getMessage()

            ], 500);
        }
    }

    public function deleteBuku($id)
    {
        try {
            $buku = DB::table('bukus')->where('id', $id)->first();

            if (!$buku) {
                return response()->json([
                    'status' => false,
                    'mesagge' => 'data buku gagal di hapus'
                ], 404);
            }

            DB::table('bukus')
                ->where('id', $id)
                ->update([
                    'deleted_at' => now(),
                    'updated_at' => now()
                ]);

            return response()->json([
                'status' => true,
                'message' => 'data buku berhasil di hapus.'
            ], 200);
        } catch (\Exception $bukuError) {
            return response()->json([
                'status' => false,
                'message' => 'data buku tidak di temukan.',
                'error' => $bukuError->getMessage()
            ], 500);
        }
    }
}
