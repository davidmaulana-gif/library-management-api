<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class JurusanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('jurusans')->insert([
            [
                'id' => 1,
                'jurusan' => 'TEKNIK KOMPUTER DAN JARINGAN',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'id' => 2,
                'jurusan' => 'TEKNIK OTOMOTIF',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'id' => 3,
                'jurusan' => 'TEKNIK KELISTRIKAN',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'jurusan' => 'TEKNIK SEPEDA MONTOR ',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
