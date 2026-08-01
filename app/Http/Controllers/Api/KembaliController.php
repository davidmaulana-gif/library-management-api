<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;



class KembaliController extends Controller
{
    public function createKembali(Request $request)
    {

        try {
            $request->validate([
                'jumlah' => 'required|integer'
            ]);

            $pinjam = DB::table('pinjams')
                ->where('id', $request->pinjam_id)
                ->first();


            // Hitung selisih hari
            $selisihHari = Carbon::parse($pinjam->created_at)->diffInDays(now());

            // Jika lebih dari 3 hari maka punishment = true
            $punishment = $selisihHari > 3;

            $id = DB::table('kembalis')->insertGetId([
                'pinjam_id' => $request->pinjam_id,
                'jumlah' => $request->jumlah,
                'punishment' => $punishment,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            return response()->json([
                'status' => true,
                'message' => 'Data kembali berhasil ditambahkan.',
                'data' => [
                    'id' => $id,
                    'punishment' => $punishment
                ]
            ], 201);
        } catch (\Throwable $kembaliError) {
            return response()->json([
                'status' => false,
                'pesan' => 'Gagal menambahkan data kembali',
                'error' => $kembaliError->getMessage()
            ], 500);
        }
    }
    public function searchKembali(Request $request)
    {
        try {

            $kembali = DB::table('kembalis')
                ->join('pinjams', 'kembalis.pinjam_id', '=', 'pinjams.id')
                ->join('data_diris', 'pinjams.data_diri_id', '=', 'data_diris.id')
                ->join('bukus', 'pinjams.buku_id', '=', 'bukus.id')
                ->select(
                    'data_diris.nama_lengkap',
                    'bukus.nama_buku',
                    'pinjams.jumlah',
                    'kembalis.jumlah',
                    'pinjams.created_at',
                    'kembalis.created_at',
                    'kembalis.punishment'
                )
                ->where('data_diris.nama_lengkap', $request->nama_lengkap)
                ->get();

            if ($kembali->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'message' => 'data kembali tidak di temukan.'
                ], 404);
            }

            return response()->json([
                'status' => true,
                'data' => $kembali
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'status' => false,
                'message' => 'Gagal mengambil data',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
