<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class KelasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('kelas')->insert([
            [
                'id' => 1,
                'kelas' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'kelas' => 11,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 3,
                'kelas' => 12,
                'created_at' => now(),
                'updated_at' => now()
            ]

        ]);
    }
}
