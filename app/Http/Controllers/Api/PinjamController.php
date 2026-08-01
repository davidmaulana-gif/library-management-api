<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class PinjamController extends Controller
{
    public function createPinjam(Request $request)
    {
        try {
            $request->validate([
                'jumlah' => 'required|integer'
            ]);


            $id = DB::table('pinjams')->insertGetId([
                'data_diri_id' => $request->data_diri_id,
                'buku_id' => $request->buku_id,
                'jumlah' => $request->jumlah,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Data pinjam berhasil ditambahkan.',
                'data' => [
                    'id' => $id,
                ]
            ], 201);
        } catch (\Throwable $pinjamError) {
            return response()->json([
                'status' => false,
                'pesan' => 'Gagal menambahkan data pinjam',
                'error' => $pinjamError->getMessage()
            ], 500);
        }
    }

    public function searchPinjam(Request $request)
    {
        try {
            $pinjam = DB::table('pinjams')
                ->select(
                    'data_diris.nama_lengkap',
                    'pinjams.jumlah',
                    'kelas.kelas',
                    'bukus.nama_buku'
                )
                ->join('data_diris', 'pinjams.data_diri_id', 'data_diris.id')
                ->join('kelas', 'kelas.id', 'data_diris.kelas_id')
                ->join('bukus', 'bukus.id', 'pinjams.buku_id')
                ->where('data_diris.nama_lengkap', $request->nama_lengkap)
                ->get();

            if ($pinjam->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'message' => 'data pinjam tidak di temukan.'
                ], 404);
            }

            return response()->json([
                'status' => true,
                'message'=> 'data pinjam berhasil di lihat',
                'data' => $pinjam
            ]);
        } catch (\Throwable $pinjamError) {
            return response()->json([
                'status' => false,
                'pesan' => 'Gagal menampilkan data pinjam.',
                'error' => $pinjamError->getMessage()
            ], 500);
        }
    }

    public function pinjamGet()
    {
        try {
            $pinjamget = DB::table('pinjams')
                ->select(
                    'pinjams.jumlah',
                    'data_diris.nama_lengkap',
                    'bukus.nama_buku',
                    'jurusans.jurusan'
                )
                ->join('data_diris', 'pinjams.data_diri_id', '=', 'data_diris.id')
                ->join('bukus', 'pinjams.buku_id', '=', 'bukus.id')
                ->join('jurusans', 'data_diris.jurusan_id', '=', 'jurusans.id')
                ->whereNull('bukus.deleted_at')
                ->get();

            if ($pinjamget->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'message' => 'data pinjam tidak ada.'
                ], 404);
            }

            return response()->json([
                'status' => true,
                'message' => 'data pinjam berhasil di lihat.',
                'data' => $pinjamget
            ], 200);
        } catch (\Exception $pinjamError) {
            return response()->json([
                'status' => false,
                'message' => 'data pinjam tidak di temukan.',
                'error' => $pinjamError->getMessage()
            ], 404);
        }
    }
}
