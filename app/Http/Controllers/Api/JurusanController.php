<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class JurusanController extends Controller

{
    public function createJurusan(Request $request)
    {
        try {
            $request->validate([
                'jurusan' => 'required|string|unique:jurusans,jurusan'
            ]);

            $id = DB::table('jurusans')->insertGetId([
                'jurusan' => $request->jurusan,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Data jurusan berhasil ditambahkan.',
                'data' => [
                    'id' => $id,
                    'jurusan' => $request->jurusan
                ]
            ], 201);
        } catch (\Exception $e) {

            return response()->json([
                'status' => false,
                'pesan' => 'Gagal menambahkan data jurusan.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function editJurusan(Request $request, $id)
    {
        try {
            $request->validate([
                'jurusan' => 'required|string|unique:jurusans,jurusan,' . $id,
            ]);

            $jurusan = DB::table('jurusans')->where('id', $id)->first();

            if (!$jurusan) {
                return response()->json([
                    'status' => false,
                    'message' => 'data jurusan tidak di temukan.'
                ], 404);
            }

            DB::table('jurusans')
                ->where('id', $id)
                ->update([
                    'jurusan' => $request->jurusan,
                    'updated_at' => now()
                ]);

            return response()->json([
                'status' => true,
                'message' => 'data jurusan berhasil di edit.',
                'data' => [
                    'id' => $id,
                    'jurusan' => $request->jurusan
                ]

            ]);
        } catch (\Exception $errorJurusan) {
            return response()->json([
                'status' => true,
                'message' => 'data jurusan berhasil di edit'
            ], 500);
        }
    }

    public function jurusanSearch(Request $request)

    {
        try {
            $data = DB::table('jurusans')
                ->select(
                    'jurusan'
                )
                ->where('jurusan', 'ILIKE', '%' . $request->jurusan . '%')
                ->whereNull('deleted_at')
                ->get();

            if ($data->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'message' => 'data jurusan tidak di temukan.'
                ], 404);
            }

            return response()->json([
                'status' => true,
                'message' => 'data jurusan berhasil dilihat',
                'data' => $data

            ], 200);
        } catch (\Exception $jurusanError) {
            return response()->json([
                'status' => false,
                'message' => 'data jurusan tidak di temukan.',
                'error' => $jurusanError->getMessage()
            ], 404);
        }
    }

    public function jurusanGet()
    {
        try {
            $jurusan = DB::table('jurusans')
                ->select('jurusan')
                ->whereNull('deleted_at')
                ->get();

            return response()->json([
                'message' => 'data jurusan di berhasil di lihat',
                'data' => $jurusan
            ], 200);
        } catch (\Throwable $jurusanError) {
            return response()->json([
                'status' => false,
                'message' => 'data jurusan tidak di temukan.',
                'error' => $jurusanError->getMessage()
            ], 404);
        }
    }

    public function deleteJurusan($id)
    {
        try {

            $jurusan = DB::table('jurusans')->where('id', $id)->first();

            if (!$jurusan) {
                return response()->json([
                    'status' => false,
                    'message' => 'data jurusan gagal di hapus'
                ], 404);
            }

            DB::table('jurusans')
                ->where('id', $id)
                ->update([
                    'deleted_at' => now(),
                    'updated_at' => now()
                ]);

            return response()->json([
                'status' => true,
                'message' => 'data jurusan berhasil di hapus'
            ], 200);
        } catch (\Exception $errorJurusan) {
            return response()->json([
                'status' => false,
                'message' => 'data jurusan tidak di temukan',
                'error' => $errorJurusan->getMessage()
            ]);
        }
    }
}
