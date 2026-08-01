<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DatadiriController extends Controller
{
    public function createData_diri(Request $request)
    {
        try {
            $request->validate([

                'nis' => 'required|integer',
                'nama_lengkap' => 'required|string',
                'tanggal_lahir' => 'required|date'
            ]);

            DB::table('data_diris')->insertGetId([
                'nis' => $request->nis,
                'nama_lengkap' => $request->nama_lengkap,
                'tanggal_lahir' => $request->tanggal_lahir,
                'kelas_id' => $request->kelas_id,
                'jurusan_id' => $request->jurusan_id,
                'user_id' => Auth::id(), // otomatis mengambil id user yang login
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Data diri berhasil ditambahkan.',
                'data' => [
                    'nis' => $request->nis,
                    'nama_lengkap' => $request->nama_lengkap,
                    'tanggal_lahir' => $request->tanggal_lahir,
                    'kelas_id' => $request->kelas_id,
                    'jurusan_id' => $request->jurusan_id,
                    'user_id' => Auth::id()
                ]
            ], 201);
        } catch (\Exception $dataError) {

            return response()->json([
                'status' => false,
                'pesan' => 'Gagal menambahkan data .',
                'error' => $dataError->getMessage()
            ], 500);
        }
    }

    public function readDatadiri()
    {
        try {
            $data = DB::table('data_diris')
                ->select(
                    'data_diris.nama_lengkap',
                    'data_diris.tanggal_lahir',
                    DB::raw("
                        CASE
                            WHEN kelas.deleted_at IS NULL THEN kelas.kelas
                            ELSE 0
                        END AS kelas
                    "),
                    DB::raw("
                        CASE
                            WHEN jurusans.deleted_at IS NULL THEN jurusans.jurusan
                            ELSE 'terhapus'
                        END AS jurusan
                    "),
                    'users.email'
                )
                ->join('kelas', 'data_diris.kelas_id', '=', 'kelas.id')
                ->join('jurusans', 'data_diris.jurusan_id', '=', 'jurusans.id')
                ->join('users', 'data_diris.user_id', '=', 'users.id')
                ->where('users.id', Auth::id())
                ->whereNull('data_diris.deleted_at')
                ->first();

            $data->umur = Carbon::parse($data->tanggal_lahir)->age;

            if (!$data) {
                return response()->json([
                    'message' => 'data diri tidak di temukan.'
                ], 404);
            }

            return response()->json([
                'status' => true,
                'data' => $data
            ]);
        } catch (\Exception $dataError) {

            return response()->json([
                'status' => false,
                'pesan' => 'Gagal menampilkan data diri .',
                'error' => $dataError->getMessage()
            ], 500);
        }
    }

    public function editDatadiri(Request $request,  $id)
    {
        try {
            $request->validate([
                'nama_lengkap' => 'sometimes|string',
                'tanggal_lahir' => 'sometimes|date'
            ]);

            $datadiri = DB::table('data_diris')->where('id', $id)->first();

            if (!$datadiri) {
                return response()->json([
                    'status' => false,
                    'message' => 'data diri tidak di temukan'
                ], 404);
            }

            $e = DB::table('data_diris')
                ->where('id', $id)
                ->update([
                    'nama_lengkap' => $request->nama_lengkap,
                    'tanggal_lahir' => $request->tanggal_lahir,
                    'kelas_id' => $request->kelas_id,
                    'jurusan_id' => $request->jurusan_id,
                    'user_id' => Auth::id(),
                    'updated_at' => now()
                ]);

            return response()->json([
                'status' => true,
                'message' => "data diri berhasil di ubah",
                'id' => Auth::id()
            ], 200);
        } catch (\Exception $errorDatadiri) {

            return response()->json([
                'status' => false,
                'message' => 'data diri gagal di ubah',
                'error' => $errorDatadiri->getMessage()
            ], 500);
        }
    }

    public function deleteDatadiri($id)
    {
        try {
            $data = DB::table('data_diris')->where('id', $id)->first();

            if (!$data) {
                return response()->json([
                    'status' => false,
                    'message' => 'data diri gagal di hapus.'
                ]);
            }

            DB::table('data_diris')
                ->where('id', $id)
                ->update([
                    'deleted_at' => now(),
                    'updated_at' => now()
                ]);

            return response()->json([
                'status' => true,
                'message' => 'data diri berhasil di hapus.'
            ], 200);
        } catch (\Exception $dataError) {
            return response()->json([
                'status' => false,
                'message' => 'data diri tidak di temukan.',
                'error' => $dataError->getMessage()
            ], 500);
        }
    }
}
