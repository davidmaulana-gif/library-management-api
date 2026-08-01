<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('kategoris')->insert([
            [
                'id' => 1,
                'kategori' => 'novel',
                'created_at' => now(),
                'updated_at' => now(),

            ],
            [
                'id' => 2,
                'kategori' => 'cerpen',
                'created_at' => now(),
                'updated_at' => now(),

            ],
            [
                'id' => 3,
                'kategori' => 'sejarah',
                'created_at' => now(),
                'updated_at' => now(),

            ]
        ]);
    }
}
