<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class LevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['level_id' => 1, 'Level_kode' => 'ADM', 'level_nama' => 'Administator'],
            ['level_id' => 2, 'Level_kode' => 'MMG', 'level_nama' => ' Manager'],
            ['level_id' => 3, 'Level_kode' => 'STF', 'level_nama' => 'Staff/Kasir'],
        ];

        DB::table('m_level')->insert($data);
    }
}
