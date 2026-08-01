<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


// use function Laravel\Prompts\password;

class UserController extends Controller
{
    public function createUser(Request $request)
    {
        try {
            $request->validate([
                'username' => 'required|string|unique:users,username',
                'email' => 'required|string|unique:users,email',
                'password' => 'required|string'
            ]);

            $nn = DB::table('users')->insertGetId([
                'username' => $request->username,
                'peran_id' => 2,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            return response()->json([
                'status' => true,
                'message' => 'Data user berhasil ditambahkan.',
            ], 201);
        } catch (\Exception $userError) {

            return response()->json([
                'status' => false,
                'pesan' => 'Gagal menambahkan data user.',
                'error' => $userError->getMessage()
            ], 500);
        }
    }

    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required|string'
            ]);

            $user = User::where('email', $request->email)->first();

            if (!$user) {
                return response()->json([
                    'message' => 'Email tidak ditemukan'
                ], 404);
            }

            if (!Hash::check($request->password, $user->password)) {
                return response()->json([
                    'message' => 'Password salah'
                ], 401);
            }
            // Hapus token lama (opsional)
            $user->tokens()->delete();

            // Buat token baru
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'message' => 'Login berhasil',
                'token' => $token,
                'user' => [
                    'id' => $user->id,
                    'email' => $user->email
                ]
            ], 200);
        } catch (\Exception $userError) {
            return response()->json([
                'status' => false,
                'pesan' => 'Gagal login',
                'error' => $userError->getMessage()
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'status' => true,
                'message' => 'Logout berhasil.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Logout gagal.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function editUser(Request $request)
    {
        try {

            $request->validate([
                'password' => 'string|unique:users,password',
                'email' => 'string|unique:users,email'
            ]);

            $user = DB::table('users')->where('id', Auth::id())->first();

            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data user tidak ditemukan.'
                ], 404);
            };

            $ae = DB::table('users')
                ->where('id', Auth::id())
                ->update([
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'updated_at' => now(),
                ]);

            return response()->json([
                'status' => true,
                'message' => 'Data users berhasil diubah.',
                'data' => [
                    'user' => $ae
                ]
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'status' => false,
                'message' => 'Gagal mengubah data users.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function deleteUser($id)

    {
        try {

            $user = DB::table('users')->where('id')->first();

            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'data user tidak di temukan'
                ], 404);
            }

            DB::table('users')
                ->where('id', $id)
                ->update([
                    'deleted_at' => now(),
                    'updated_at' => now()
                ]);

            return response()->json([
                'status' => true,
                'message' => 'data user berhasil di hapus.',
            ], 200);
        } catch (\Exception $userError) {
            return response()->json([
                'status' => false,
                'message' => 'data user tidak di temukan.'
            ], 404);
        }
    }
}
