<?php

use App\Http\Controllers\Api\BukuController;
use App\Http\Controllers\Api\DatadiriController;
use App\Http\Controllers\Api\KembaliController;
use App\Http\Controllers\Api\PeranController;
use App\Http\Controllers\Api\KategoriController;
use App\Http\Controllers\Api\KelasController;
use App\Http\Controllers\Api\JurusanController;
use App\Http\Controllers\Api\PinjamController;
use App\Http\Controllers\Api\UserController;
// use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::post('/user/login', [UserController::class, 'login']);


Route::middleware('auth:sanctum')->group(function () {
    Route::middleware('peran:1')->group(function () {
        Route::post('/user/create', [UserController::class, 'createUser']);
        Route::patch('/user/edit', [UserController::class, 'editUser']);
        Route::post('/user/logout', [UserController::class, 'logout']);
        Route::post('/user/delete/{id}', [UserController::class, 'deleteUser']);

        Route::post('/buku/create', [BukuController::class, 'createBuku']);
        Route::get('/buku/search-buku', [BukuController::class, 'searchBuku']);
        Route::get('/buku/get', [BukuController::class, 'bukuGet']);
        Route::patch('/buku/edit/{id}', [BukuController::class, 'editBuku']);
        Route::put('/buku/delete/{id}', [BukuController::class, 'deleteBuku']);

        Route::post('/peran/create', [PeranController::class, 'createPeran']);
        Route::patch('/peran/edit/{id}', [PeranController::class, 'editPeran']);
        Route::get('/peran/get', [PeranController::class, 'peranGet']);
        Route::get('/peran/search', [PeranController::class, 'peranSearch']);

        Route::post('/kategori/create', [KategoriController::class, 'createKategori']);
        Route::patch('/kategori/edit/{id}', [KategoriController::class, 'editKategori']);
        Route::put('/kategori/delete/{id}', [KategoriController::class, 'kategoriDelete']);
        Route::get('/kategori/get', [KategoriController::class, 'kategoriGet']);
        Route::get('/kategori/search', [KategoriController::class, 'kategoriSearch']);

        Route::post('kelas/create', [KelasController::class, 'createKelas']);
        Route::patch('/kelas/edit/{id}', [KelasController::class, 'editKelas']);
        Route::put('/kelas/delete/{id}', [KelasController::class, 'deleteKelas']);
        Route::get('/kelas/get', [KelasController::class, 'kelasGet']);
        Route::get('/kelas/search', [KelasController::class, 'kelasSearch']);

        Route::post('jurusan/create', [JurusanController::class, 'createJurusan']);
        Route::patch('/jurusan/edit/{id}', [JurusanController::class, 'editJurusan']);
        Route::get('/jurusan/search', [JurusanController::class, 'jurusanSearch']);
        Route::get('/jurusan/get', [JurusanController::class, 'jurusanGet']);
        Route::put('/jurusan/delete/{id}', [JurusanController::class, 'deleteJurusan']);

        Route::post('/pinjam/create', [PinjamController::class, 'createPinjam']);
        Route::get('/pinjam/read-pinjam', [PinjamController::class, 'searchPinjam']);
        Route::get('/pinjam/get-pinjam', [PinjamController::class, 'pinjamGet']);

        Route::post('kembali/create', [KembaliController::class, 'createKembali']);
        Route::get('kembali/search-kembali', [KembaliController::class, 'searchKembali']);
    });
    Route::post('/data-diri/create', [DatadiriController::class, 'createData_diri']);
    Route::get('/data-diri/read-datadiri', [DatadiriController::class, 'readDatadiri']);
    Route::patch('/data-diri/edit/{id}', [DatadiriController::class, 'editDatadiri']);
    Route::put('/data-diri/delete/{id}', [DatadiriController::class, 'deleteDatadiri']);
});
