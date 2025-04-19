<?php

namespace App\Http\Controllers;

use App\Models\BarangModel;
use App\Models\LevelModel;
use App\Models\PenjualanDetailModel;
use App\Models\PenjualanModel;
use App\Models\StokModel;
use App\Models\UserModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class PenjualanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // DB FACADE
        // $data = DB::select('select * from t_penjualan'); // Mengambil semua data dari tabel t_penjualan
        // return view('penjualan.index', ['data' => $data]);

        //QUERY BUILDER
        // $data = DB::table('t_penjualan')->get(); // Mengambil semua data dari tabel t_penjualan
        // return view('penjualan.index', ['data' => $data]);

        //ELOQUENT ORM
        // $data = PenjualanModel::all();
        // return view('penjualan.index', ['data' => $data]);

        $breadcrumb = (object) [
            'title' => 'Transaksi Penjualan',
            'list' => ['Home', 'Penjualan']
        ];

        $page = (object) [
            'title' => 'Transaksi Lama'
        ];

        $users = UserModel::all();
        $activeMenu = 'penjualan';

        return view('penjualan.index', compact('breadcrumb', 'page', 'users', 'activeMenu'));
    }

    public function list(Request $request)
    {
        $penjualans = PenjualanModel::select(
            'penjualan_id',
            'user_id',
            'pembeli',
            'penjualan_kode',
            'tanggal_penjualan'
        )
            ->with('user'); // Relasi ke model user

        // Filter data berdasarkan user_id
        $user_id = $request->input('user_id');
        if (!empty($user_id)) {
            $penjualans->where('user_id', $user_id);
        }

        return DataTables::of($penjualans)
            ->addIndexColumn() // kolom DT_RowIndex
            ->addColumn('aksi', function ($penjualans) {
                // Tombol Detail, Edit, dan Hapus
                $btn = '<button onclick="modalAction(\'' . url('/penjualan/' . $penjualans->penjualan_id . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<a href="' . url('/penjualan/' . $penjualans->penjualan_id . '/print_receipt') . '" class="btn btn-sm btn-warning mr-1">Cetak Struk</a>';
                $btn .= '<a href="' . url('/penjualan/' . $penjualans->penjualan_id . '/show') . '" class="btn btn-sm btn-warning mr-1">show</a>';
                $btn .= '<button onclick="modalAction(\'' . url('/penjualan/' . $penjualans->penjualan_id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';

                return $btn;
            })
            ->editColumn('tanggal_penjualan', function ($data) {
                return \Carbon\Carbon::parse($data->tanggal_penjualan)->format('d-m-Y');
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $breadcrumb = (object) [
            'title' => 'Transaksi Penjualan',
            'list' => ['Home', 'Penjualan', 'Transaksi']
        ];

        $page = (object) [
            'title' => 'Transaksi Baru'
        ];

        // $stokBarang = DB::table('m_barang')
        //     ->join('t_stok', 'm_barang.barang_id', '=', 't_stok.barang_id')
        //     ->select('m_barang.*', DB::raw('SUM(t_stok.stok_jumlah) as total_stok'))
        //     ->groupBy('m_barang.barang_id')
        //     ->havingRaw('SUM(t_stok.stok_jumlah) > 0')
        //     ->get();

        $stokBarang = StokModel::join('m_barang', 'm_barang.barang_id', '=', 't_stok.barang_id')
            ->select('m_barang.barang_id', 'm_barang.barang_nama', 'm_barang.harga_jual', 'm_barang.barang_kode', 't_stok.stok_jumlah')
            ->get();

        // Menghitung stok_tersedia untuk setiap barang
        foreach ($stokBarang as $item) {
            // Hitung stok masuk
            $stokMasuk = StokModel::where('barang_id', $item->barang_id)->sum('stok_jumlah');

            // Hitung stok keluar
            $stokKeluar = PenjualanDetailModel::where('barang_id', $item->barang_id)->sum('jumlah_barang');

            // Menghitung stok yang tersedia
            $item->stok_tersedia = $stokMasuk - $stokKeluar;
        }

        // $stokBarang = DB::table('t_stok')
        //     ->join('m_barang', 'm_barang.barang_id', '=', 't_stok.barang_id')
        //     ->select(
        //         'm_barang.barang_id',
        //         'm_barang.barang_kode',
        //         'm_barang.barang_nama',
        //         'm_barang.harga_jual',
        //         DB::raw('SUM(t_stok.stok_jumlah) as stok_tersedia')
        //     )
        //     ->groupBy('m_barang.barang_id', 'm_barang.barang_kode', 'm_barang.barang_nama', 'm_barang.harga_jual')
        //     ->get();

        $activeMenu = 'penjualan';

        return view('penjualan.create', compact('breadcrumb', 'page', 'stokBarang', 'activeMenu'));
    }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'pembeli_input' => 'required|string|max:50',
    //         'data' => 'required|json',
    //     ]);

    //     $keranjang = json_decode($request->data, true);
    //     if (empty($keranjang)) {
    //         return back()->with('error', 'Keranjang tidak boleh kosong!');
    //     }

    //     DB::beginTransaction();
    //     try {

    //         foreach ($keranjang as $item) {
    //             $barang_id = $item['barang_id'];
    //             $qty = $item['qty'];

    //             // Hitung stok yang tersedia
    //             $stokTersedia = $this->getStokTersedia($barang_id);

    //             // Cek apakah stok cukup
    //             if ($qty > $stokTersedia) {
    //                 throw new \Exception("Stok untuk barang ID {$barang_id} tidak cukup!");
    //             }
    //         }

    //         // Simpan t_penjualan
    //         $kode = 'PNJ-' . now()->format('YmdHis') . '-' . rand(100, 999);
    //         $penjualan = PenjualanModel::create([
    //             'user_id' => auth()->user()->user_id,
    //             'pembeli' => $request->pembeli_input,
    //             'penjualan_kode' => $kode,
    //             'tanggal_penjualan' => now(),
    //         ]);

    //         foreach ($keranjang as $item) {
    //             PenjualanDetailModel::create([
    //                 'penjualan_id' => $penjualan->penjualan_id,
    //                 'barang_id' => $item['barang_id'],
    //                 'jumlah_barang' => $item['qty'],
    //                 'harga_barang' => $item['harga'],
    //             ]);

    //             // Update stok setelah penjualan
    //             $stok = StokModel::where('barang_id', $item['barang_id'])->first();
    //             if ($stok) {
    //                 $stok->decrement('stok_jumlah', $item['qty']);
    //             }
    //         }            

    //         DB::commit();
    //         return redirect()->route('penjualan.index')->with('success', 'Transaksi berhasil dan stok ter-update!');
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return back()->with('error', 'Gagal menyimpan transaksi: ' . $e->getMessage());
    //     }
    // }

    public function getStokTersedia($barang_id)
    {
        // Menghitung stok masuk (stok yang diterima)
        $stokMasuk = StokModel::where('barang_id', $barang_id)->sum('stok_jumlah');

        // Menghitung stok keluar (stok yang sudah terjual)
        $stokKeluar = PenjualanDetailModel::where('barang_id', $barang_id)->sum('jumlah_barang');

        // Menghitung stok yang tersedia
        $stokTersedia = $stokMasuk - $stokKeluar;

        return $stokTersedia;
    }

    // Fungsi untuk menyimpan transaksi penjualan
    public function store2(Request $request)
    {
        $request->validate([
            'pembeli_input' => 'required|string|max:50',
            'data' => 'required|json',
        ]);

        $keranjang = json_decode($request->data, true);
        if (empty($keranjang)) {
            return back()->with('error', 'Keranjang tidak boleh kosong!');
        }

        DB::beginTransaction();
        try {
            // Validasi stok untuk setiap barang yang dijual
            // foreach ($keranjang as $item) {
            //     $barang_id = $item['barang_id'];
            //     $qty = $item['qty'];

            //     // Menghitung stok yang tersedia
            //     $stokTersedia = $this->getStokTersedia($barang_id);

            //     // Cek apakah stok mencukupi
            //     if ($qty > $stokTersedia) {
            //         throw new \Exception("Stok untuk barang ID {$barang_id} tidak cukup!");
            //     }
            // }

            foreach ($keranjang as $item) {
                $barang_id = $item['barang_id'];
                $qty = $item['qty'];

                // Hitung stok yang tersedia
                $stokTersedia = $this->getStokTersedia($barang_id);

                // Cek apakah stok cukup
                if ($qty > $stokTersedia) {
                    throw new \Exception("Stok untuk barang ID {$barang_id} tidak cukup!");
                }
            }

            // Simpan t_penjualan
            $kode = 'PNJ-' . now()->format('YmdHis') . '-' . rand(100, 999);
            $penjualan = PenjualanModel::create([
                'user_id' => auth()->user()->user_id,
                'pembeli' => $request->pembeli_input,
                'penjualan_kode' => $kode,
                'tanggal_penjualan' => now(),
            ]);

            // Simpan detail penjualan dan update stok
            // foreach ($keranjang as $item) {
            //     $detailPenjualan = PenjualanDetailModel::create([
            //         'penjualan_id' => $penjualan->penjualan_id,
            //         'barang_id' => $item['barang_id'],
            //         'jumlah_barang' => $item['qty'],
            //         'harga_barang' => $item['harga'],
            //     ]);

            //     // Update stok (mengurangi stok setelah penjualan)
            //     $stok = StokModel::where('barang_id', $item['barang_id'])->first();
            //     if ($stok) {
            //         // Jika ada stok yang tersedia, kurangi jumlahnya
            //         $stok->decrement('stok_jumlah', $item['qty']);
            //     }
            // }

            foreach ($keranjang as $item) {
                PenjualanDetailModel::create([
                    'penjualan_id' => $penjualan->penjualan_id,
                    'barang_id' => $item['barang_id'],
                    'jumlah_barang' => $item['qty'],
                    'harga_barang' => $item['harga'],
                ]);

                // Update stok setelah penjualan
                $stok = StokModel::where('barang_id', $item['barang_id'])->first();
                if ($stok) {
                    $stok->decrement('stok_jumlah', $item['qty']);
                }
            }

            DB::commit();
            return redirect()->route('penjualan.index')->with('success', 'Transaksi berhasil dan stok berkurang!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyimpan transaksi: ' . $e->getMessage());
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $penjualan = PenjualanModel::with('user')->find($id);

        $breadcrumb = (object) [
            'title' => 'Detail Penjualan',
            'list' => ['Home', 'Penjualan', 'Detail']
        ];

        $page = (object) [
            'title' => 'Detail penjualan'
        ];

        $activeMenu = 'penjualan';

        return view('penjualan.show', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'penjualan' => $penjualan,
            'activeMenu' => $activeMenu
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    // public function show_ajax($id)
    // {
    //     // $penjualan = PenjualanModel::with(['user', 'penjualanDetail.barang'])->find($id);

    //     // $penjualanDetail = $penjualan->penjualanDetail;
    //     $penjualanDetail = PenjualanModel::find($id);

    //     return view('penjualan.show_ajax', ['penjualanDetail' => $penjualanDetail]);
    // }

    // PenjualanController.php
    public function show_ajax(string $id)
    {
        $penjualan = PenjualanModel::with(['user', 'penjualanDetails.barang'])->find($id);

        $penjualanDetails = $penjualan->penjualanDetails;

        return view('penjualan.show_ajax', ['penjualanDetails' => $penjualanDetails]);
    }

    public function print_receipt($id)
    {
        $penjualan = PenjualanModel::with(['user', 'penjualanDetails.barang'])
            ->find($id);

        $pdf = Pdf::loadView('penjualan.receipt', compact('penjualan'));
        $pdf->setPaper('A6', 'portrait');
        $pdf->setOption('isRemoteEnabled', true);

        $filename = 'Struk_' . $penjualan->penjualan_kode . '.pdf';
        return $pdf->stream($filename);
    }

}
