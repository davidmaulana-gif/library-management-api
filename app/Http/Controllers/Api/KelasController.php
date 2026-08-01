<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function createKelas(Request $request)
    {
        try {
            $request->validate([
                'kelas' => 'required|integer|unique:kelas,kelas'
            ]);


            $id = DB::table('kelas')->insertGetId([
                'kelas' => $request->kelas,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Data kelas berhasil ditambahkan.',
                'data' => [
                    'id' => $id,
                    'kelas' => $request->kelas
                ]
            ], 201);
        } catch (\Exception $e) {

            return response()->json([
                'status' => false,
                'pesan' => 'Gagal menambahkan data kelas.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function editKelas(Request $request, $id)
    {
        try {
            $request->validate([
                'kelas' => 'required|integer|unique:kelas,kelas,' . $id,
            ]);

            $kelas = DB::table('kelas')->where('id', $id)->first();

            if (!$kelas) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data kelas tidak ditemukan.'
                ], 404);
            }

            DB::table('kelas')
                ->where('id', $id)
                ->update([
                    'kelas' => $request->kelas,
                    'updated_at' => now(),
                ]);

            return response()->json([
                'status' => true,
                'message' => 'Data kelas berhasil diubah.',
                'data' => [
                    'id' => $id,
                    'kelas' => $request->kelas
                ]
            ]);
        } catch (\Exception $errorKelas) {

            return response()->json([
                'status' => false,
                'message' => 'Gagal mengubah data kelas.',
                'error' => $errorKelas->getMessage()
            ], 500);
        }
    }

    public function deleteKelas($id)
    {
        try {

            $kelas = DB::table('kelas')->where('id', $id)->first();

            if (!$kelas) {
                return response()->json([
                    'status' => false,
                    'message' => 'data kelas tidak di temukan'
                ], 404);
            }

            DB::table('kelas')
                ->where('id', $id)
                ->update([
                    'deleted_at' => now(),
                    'updated_at' => now()
                ]);


            return response()->json([
                'status' => true,
                'message' => 'data kelas berhasil di hapus.',
                'id' => $id
            ], 200);
        } catch (\Exception $kelasError) {

            return response()->json([
                'status' => false,
                'message' => 'data kelas gagal di hapus',
                'id' => $id,
                'error' => $kelasError->getMessage()
            ], 404);
        }
    }

    public function kelasGet()
    {
        try {
            $kelasGet = DB::table('kelas')
                ->select(
                    'kelas'
                )
                ->whereNull('deleted_at')
                ->get();

            if ($kelasGet->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'message' => 'data diri tidak di temukan.'
                ], 404);
            }

            return response()->json([
                'status' => true,
                'message' => 'data kelas berhasil di lihat.',
                'data' => $kelasGet
            ], 200);
        } catch (\Exception $kelasError) {
            return response()->json([
                'status' => true,
                'message' => 'data kelas tidak di temukan.',
                'error' => $kelasError->getMessage()
            ], 404);
        }
    }

    public function kelasSearch(Request $request)
    {
        try {

            $kelasSearch = DB::table('kelas')
                ->select(
                    'kelas'
                )
                ->whereNull('deleted_at')
                ->where('kelas', 'ILIKE', '%', $request->nama_kelas . '%')
                ->get();

            if ($kelasSearch->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'message' => 'data diri tidak di temukan.'
                ], 404);
            }

            return response()->json([
                'status' => true,
                'message' => 'data kelas berhasil dilihat',
                'data' => $kelasSearch
            ], 200);
        } catch (\Exception $kelasError) {
            return response()->json([
                'status' => false,
                'message' => 'data kelas tidak di temukan.',
                'error' => $kelasError->getMessage()
            ], 404);
        }
    }
}
