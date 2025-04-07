<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class StokSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'barang_id'          => 1,
                'user_id'            => 1,
                'supplier_id'        => 1, 
                'stok_tanggal_masuk' => '2023-01-01 09:00:00',
                'stok_jumlah'        => 20,
            ],
            [
                'barang_id'          => 2,
                'user_id'            => 1,
                'supplier_id'        => 1,
                'stok_tanggal_masuk' => '2023-01-02 09:00:00',
                'stok_jumlah'        => 15,
            ],
            [
                'barang_id'          => 3,
                'user_id'            => 1,
                'supplier_id'        => 1,
                'stok_tanggal_masuk' => '2023-01-03 09:00:00',
                'stok_jumlah'        => 30,
            ],
            [
                'barang_id'          => 4,
                'user_id'            => 1,
                'supplier_id'        => 2,
                'stok_tanggal_masuk' => '2023-01-04 09:00:00',
                'stok_jumlah'        => 25,
            ],
            [
                'barang_id'          => 5,
                'user_id'            => 1,
                'supplier_id'        => 2,
                'stok_tanggal_masuk' => '2023-01-05 09:00:00',
                'stok_jumlah'        => 40,
            ],
            [
                'barang_id'          => 6,
                'user_id'            => 1,
                'supplier_id'        => 2,
                'stok_tanggal_masuk' => '2023-01-06 09:00:00',
                'stok_jumlah'        => 35,
            ],
            [
                'barang_id'          => 7,
                'user_id'            => 1,
                'supplier_id'        => 3,
                'stok_tanggal_masuk' => '2023-01-07 09:00:00',
                'stok_jumlah'        => 10,
            ],
            [
                'barang_id'          => 8,
                'user_id'            => 1,
                'supplier_id'        => 3,
                'stok_tanggal_masuk' => '2023-01-08 09:00:00',
                'stok_jumlah'        => 50,
            ],
            [
                'barang_id'          => 9,
                'user_id'            => 1,
                'supplier_id'        => 3,
                'stok_tanggal_masuk' => '2023-01-09 09:00:00',
                'stok_jumlah'        => 45,
            ],
            [
                'barang_id'          => 10,
                'user_id'            => 1,
                'supplier_id'        => 1,
                'stok_tanggal_masuk' => '2023-01-10 09:00:00',
                'stok_jumlah'        => 60,
            ],
        ];

        DB::table('t_stok')->insert($data);
    }
}
