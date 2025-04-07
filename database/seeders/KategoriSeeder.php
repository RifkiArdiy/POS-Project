<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'kategori_kode' => 'ETN',
                'kategori_nama' => 'Elektronik',
                'deskripsi'   => 'Kategori barang elektronik',
            ],
            [
                'kategori_kode' => 'PKA',
                'kategori_nama' => 'Pakaian',
                'deskripsi'   => 'Kategori pakaian dan aksesoris',
            ],
            [
                'kategori_kode' => 'CML',
                'kategori_nama' => 'Cemilan',
                'deskripsi'   => 'Kategori makanan ringan',
            ],
            [
                'kategori_kode' => 'BKU',
                'kategori_nama' => 'Buku',
                'deskripsi'   => 'Kategori buku dan majalah',
            ],
            [
                'kategori_kode' => 'SPT',
                'kategori_nama' => 'Sepatu',
                'deskripsi'   => 'Kategori sepatu',
            ],
        ];

        DB::table('m_kategori')->insert($data);
    }
}
