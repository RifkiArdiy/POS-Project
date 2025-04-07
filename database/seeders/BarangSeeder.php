<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'kategori_id'   => 1,
                'barang_kode'   => 'ELT-001',
                'barang_nama'   => 'Samsung Galaxy A36 5G',
                'harga_jual'    => 5000000,
                'harga_beli'    => 4500000,
            ],
            [
                'kategori_id'   => 1,
                'barang_kode'   => 'ELT-002',
                'barang_nama'   => 'Lenovo LOQ 15IRH8 ',
                'harga_jual'    => 17000000,
                'harga_beli'    => 15000000,
            ],
            [
                'kategori_id'   => 2,
                'barang_kode'   => 'PKA-001',
                'barang_nama'   => 'T-Shirt X1',
                'harga_jual'    => 100000,
                'harga_beli'    => 70000,
            ],
            [
                'kategori_id'   => 2,
                'barang_kode'   => 'PKA-002',
                'barang_nama'   => 'EIGER X-BOUSTER 1.0',
                'harga_jual'    => 300000,
                'harga_beli'    => 250000,
            ],
            [
                'kategori_id'   => 3,
                'barang_kode'   => 'CML-001',
                'barang_nama'   => 'Ciki PIE',
                'harga_jual'    => 20000,
                'harga_beli'    => 15000,
            ],
            [
                'kategori_id'   => 3,
                'barang_kode'   => 'CML-002',
                'barang_nama'   => 'NAHATI Coklat',
                'harga_jual'    => 15000,
                'harga_beli'    => 10000,
            ],
            [
                'kategori_id'   => 4,
                'barang_kode'   => 'BKU-001',
                'barang_nama'   => 'Atomic Habits',
                'harga_jual'    => 100000,
                'harga_beli'    => 80000,
            ],
            [
                'kategori_id'   => 4,
                'barang_kode'   => 'BKU-002',
                'barang_nama'   => 'Self Discipline',
                'harga_jual'    => 50000,
                'harga_beli'    => 40000,
            ],
            [
                'kategori_id'   => 5,
                'barang_kode'   => 'SPT-001',
                'barang_nama'   => 'Nike Air Max',
                'harga_jual'    => 350000,
                'harga_beli'    => 300000,
            ],
            [
                'kategori_id'   => 5,
                'barang_kode'   => 'SPT-002',
                'barang_nama'   => 'Adidas Ultraboost',
                'harga_jual'    => 150000,
                'harga_beli'    => 120000,
            ],
        ];

        DB::table('m_barang')->insert($data);
    }
}
