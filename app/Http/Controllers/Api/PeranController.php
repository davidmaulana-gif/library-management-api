<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class PeranController extends Controller
{
    public function createPeran(Request $request)
    {
        try {

            $request->validate([
                'peran' => 'required|string|unique:perans,peran',

            ]);

            $id = DB::table('perans')->insertGetId([
                'peran' => $request->peran,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Data peran berhasil ditambahkan.',
                'data' => [
                    'id' => $id,
                    'peran' => $request->peran
                ]
            ], 201);
        } catch (\Exception $e) {

            return response()->json([
                'status' => false,
                'pesan' => 'Gagal menambahkan data peran.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function editPeran(Request $request, $id)
    {
        try {

            $request->validate([
                'peran' => 'required|string|unique:perans,peran,' . $id,
            ]);

            $peran = DB::table('perans')->where('id', $id)->first();

            if (!$peran) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data peran tidak ditemukan.'
                ], 404);
            }

            DB::table('perans')
                ->where('id', $id)
                ->update([
                    'peran' => $request->peran,
                    'updated_at' => now(),
                ]);

            return response()->json([
                'status' => true,
                'message' => 'Data peran berhasil diubah.',
                'data' => [
                    'id' => $id,
                    'peran' => $request->peran
                ]
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'status' => false,
                'message' => 'Gagal mengubah data peran.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function peranGet()
    {
        try {
            $peran = DB::table('perans')
                ->select(
                    'peran'
                )
                ->whereNull('deleted_at')
                ->get();

            if ($peran->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'message' => 'data peran tidak di temukan.'
                ], 404);
            }

            return response()->json([
                'status' => true,
                'message' => 'data peran berhasil di lihat.',
                'data' => $peran
            ], 200);
        } catch (\Exception $peranError) {
            return response()->json([
                'status' => true,
                'message' => 'data peran tidak di temukan.',
                'error' => $peranError->getMessage()
            ], 404);
        }
    }

    public function peranSearch(Request $request)
    {
        try {
            $peranSearch = DB::table('perans')
                ->select(
                    'peran'
                )
                ->whereNull('deleted_at')
                ->where('peran', 'ILIKE', '%' . $request->nama_peran . '%')
                ->get();

            if ($peranSearch->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'message' => 'data diri tidak di temukan.'
                ], 404);
            }

            return response()->json([
                'status' => true,
                'message' => 'data berhasil dilihat',
                'data' => $peranSearch
            ], 200);
        } catch (\Exception $peranError) {
            return response()->json([
                'status' => false,
                'message' => 'data peran tidak di temukan.',
                'error' => $peranError->getMessage()
            ], 404);
        }
    }
}
