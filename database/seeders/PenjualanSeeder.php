<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class PenjualanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'user_id'           => 1,
                'pembeli'           => 'Budi Santoso',
                'penjualan_kode'    => 'PNJ01',
                'tanggal_penjualan' => '2023-02-01 10:00:00',
            ],
            [
                'user_id'           => 3,
                'pembeli'           => 'Siti Aminah',
                'penjualan_kode'    => 'PNJ02',
                'tanggal_penjualan' => '2023-02-02 10:00:00',
            ],
            [
                'user_id'           => 3,
                'pembeli'           => 'Agus Salim',
                'penjualan_kode'    => 'PNJ03',
                'tanggal_penjualan' => '2023-02-03 10:00:00',
            ],
            [
                'user_id'           => 1,
                'pembeli'           => 'Dewi Lestari',
                'penjualan_kode'    => 'PNJ04',
                'tanggal_penjualan' => '2023-02-04 10:00:00',
            ],
            [
                'user_id'           => 3,
                'pembeli'           => 'Ahmad Fauzi',
                'penjualan_kode'    => 'PNJ05',
                'tanggal_penjualan' => '2023-02-05 10:00:00',
            ],
            [
                'user_id'           => 1,
                'pembeli'           => 'Rina Suryani',
                'penjualan_kode'    => 'PNJ06',
                'tanggal_penjualan' => '2023-02-06 10:00:00',
            ],
            [
                'user_id'           => 3,
                'pembeli'           => 'Joko Susilo',
                'penjualan_kode'    => 'PNJ07',
                'tanggal_penjualan' => '2023-02-07 10:00:00',
            ],
            [
                'user_id'           => 2,
                'pembeli'           => 'Lilis Kurniawati',
                'penjualan_kode'    => 'PNJ08',
                'tanggal_penjualan' => '2023-02-08 10:00:00',
            ],
            [
                'user_id'           => 3,
                'pembeli'           => 'Hendra Wijaya',
                'penjualan_kode'    => 'PNJ09',
                'tanggal_penjualan' => '2023-02-09 10:00:00',
            ],
            [
                'user_id'           => 2,
                'pembeli'           => 'Tuti Pertiwi',
                'penjualan_kode'    => 'PNJ10',
                'tanggal_penjualan' => '2023-02-10 10:00:00',
            ],
        ];

        DB::table('t_penjualan')->insert($data);
    }
}
