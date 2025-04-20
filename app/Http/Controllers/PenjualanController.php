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
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
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
            ->with('user')
            ->orderBy('tanggal_penjualan', 'DESC'); // Relasi ke model user

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
                // $btn .= '<a href="' . url('/penjualan/' . $penjualans->penjualan_id . '/show') . '" class="btn btn-sm btn-warning mr-1">show</a>';
                // $btn .= '<button onclick="modalAction(\'' . url('/penjualan/' . $penjualans->penjualan_id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
    
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

    // PenjualanController.php
    public function show_ajax($id)
    {
        $penjualan = PenjualanModel::with(['user', 'penjualanDetails.barang'])->find($id);

        $penjualanDetails = $penjualan->penjualanDetails;

        return view('penjualan.show_ajax', ['penjualanDetails' => $penjualanDetails]);
    }

    public function confirm_ajax($id)
    {
        $penjualan = PenjualanModel::with(['penjualanDetails.barang', 'user'])->find($id);
        return view('penjualan.confirm_ajax', ['penjualan' => $penjualan]);
    }

    // public function delete_ajax(Request $request, $id)
    // {
    //     if ($request->ajax() || $request->wantsJson()) {
    //         $penjualan = PenjualanModel::find($id);
    //         if ($penjualan) {
    //             $penjualan->delete();
    //             return response()->json([
    //                 'status' => true,
    //                 'message' => 'Data penjualan beserta detailnya berhasil dihapus'
    //             ]);
    //         } else {
    //             return response()->json([
    //                 'status' => false,
    //                 'message' => 'Data penjualan tidak ditemukan'
    //             ]);
    //         }
    //     }
    // }

    public function delete_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $penjualan = PenjualanModel::with('penjualanDetails')->find($id);

            if ($penjualan) {
                // Hapus semua detail terlebih dahulu
                foreach ($penjualan->penjualanDetails as $detail) {
                    $detail->delete();
                }

                // Lalu hapus header penjualannya
                $penjualan->delete();

                return response()->json([
                    'status' => true,
                    'message' => 'Data penjualan beserta detailnya berhasil dihapus'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data penjualan tidak ditemukan'
                ]);
            }
        }
    }


    public function import()
    {
        return view('penjualan.import');
    }

    public function import_ajax(Request $request)
    {
        if (!$request->ajax() && !$request->wantsJson()) {
            return redirect()->back();
        }

        // 1) validasi file
        $validator = Validator::make($request->all(), [
            'file_penjualan' => ['required', 'mimes:xlsx', 'max:2048'],
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal',
                'msgField' => $validator->errors()
            ]);
        }

        // 2) load spreadsheet
        $path = $request->file('file_penjualan')->getPathname();
        $reader = IOFactory::createReader('Xlsx');
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($path);

        // Sheet pertama = header penjualan, sheet kedua = detail
        $sheetH = $spreadsheet->getSheet(0)->toArray(null, true, true, true);
        $sheetD = $spreadsheet->getSheet(1)->toArray(null, true, true, true);

        DB::beginTransaction();
        try {
            // Mengimport penjualan
            $mapKode = []; // [ penjualan_kode => penjualan_id ]
            foreach ($sheetH as $rowNum => $row) {
                if ($rowNum === 1) {
                    // anggap baris 1 adalah header kolom: skip
                    continue;
                }

                // baca kolom A:D sesuai template:
                $userId = intval($row['A'] ?? 0);
                $pembeli = trim($row['B'] ?? '');
                $kode = trim($row['C'] ?? '');
                $tgl = trim($row['D'] ?? '');

                // jika salah satu field wajib kosong, skip baris ini
                if (!$userId || $kode === '' || !$tgl) {
                    continue;
                }

                // insert penjualan baru
                $p = PenjualanModel::create([
                    'user_id' => $userId,
                    'pembeli' => $pembeli,
                    'penjualan_kode' => $kode,
                    'tanggal_penjualan' => date('Y-m-d H:i:s', strtotime($tgl)),
                ]);

                // simpan mapping untuk detail
                $mapKode[$kode] = $p->penjualan_id;
            }

            // Mengimport detail penjualan serta update stok di barang
            foreach ($sheetD as $rowNum => $row) {
                if ($rowNum === 1) {
                    // skip header kolom
                    continue;
                }

                $kode = trim($row['A'] ?? '');
                $barangId = intval($row['B'] ?? 0);
                $jumlah = intval($row['C'] ?? 0);
                $harga = floatval($row['D'] ?? 0);

                // pastikan header dengan kode ini sudah di‐import
                if (!isset($mapKode[$kode])) {
                    throw new \Exception("Header penjualan kode “{$kode}” tidak ditemukan (baris {$rowNum}).");
                }
                $penjualanId = $mapKode[$kode];

                // cek & kurangi stok di BarangModel
                $barang = BarangModel::find($barangId);
                if (!$barang) {
                    throw new \Exception("Barang dengan ID {$barangId} tidak ditemukan (baris {$rowNum}).");
                }
                if ($barang->barang_stok < $jumlah) {
                    throw new \Exception("Stok tidak mencukupi untuk barang “{$barang->barang_nama}” (baris {$rowNum}).");
                }
                // kurangi stok
                $barang->decrement('barang_stok', $jumlah);

                // simpan detail
                PenjualanDetailModel::create([
                    'penjualan_id' => $penjualanId,
                    'barang_id' => $barangId,
                    'jumlah' => $jumlah,
                    'harga' => $harga,
                ]);
            }

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Import berhasil: data penjualan & detail tersimpan, stok terupdate.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Import gagal: ' . $e->getMessage()
            ]);
        }
    }

    public function export_excel()
    {
        // Ambil data penjualan dengan relasi user dan detail barang
        $penjualans = PenjualanModel::with(['user', 'penjualanDetails.barang'])
            ->orderBy('penjualan_id')
            ->get();

        // Load library spreadsheet
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header kolom
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Kode Penjualan');
        $sheet->setCellValue('C1', 'Tanggal Penjualan');
        $sheet->setCellValue('D1', 'Nama Kasir');
        $sheet->setCellValue('E1', 'Pembeli');
        $sheet->setCellValue('F1', 'Jumlah Item');
        $sheet->setCellValue('G1', 'Total Harga');

        // Buat header bold
        $sheet->getStyle('A1:G1')->getFont()->setBold(true);

        // Loop data dan isi ke sheet
        $baris = 2;
        $no = 1;

        foreach ($penjualans as $penjualan) {
            $jumlah_item = $penjualan->penjualanDetails->sum('jumlah_barang');
            $total_harga = $penjualan->penjualanDetails->sum(function ($detail) {
                return $detail->harga_barang * $detail->jumlah_barang;
            });

            $sheet->setCellValue('A' . $baris, $no++);
            $sheet->setCellValue('B' . $baris, $penjualan->penjualan_kode);
            $sheet->setCellValue('C' . $baris, \Carbon\Carbon::parse($penjualan->tanggal_penjualan)->format('d-m-Y'));
            $sheet->setCellValue('D' . $baris, $penjualan->user->username ?? '-');
            $sheet->setCellValue('E' . $baris, $penjualan->pembeli);
            $sheet->setCellValue('F' . $baris, $jumlah_item);
            $sheet->setCellValue('G' . $baris, $total_harga);

            $baris++;
        }

        // Set auto width untuk semua kolom
        foreach (range('A', 'G') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $sheet->setTitle('Data Penjualan');

        // Tulis file Excel ke output
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data_Penjualan_' . date('Y-m-d_H-i-s') . '.xlsx';

        // Header untuk download file
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');

        $writer->save('php://output');
        exit;
    }

    public function export_pdf()
    {
        $penjualan = PenjualanModel::with(['user', 'penjualanDetails'])
            ->orderBy('penjualan_id')
            ->orderBy('penjualan_kode')
            ->get();


        // use Barryvdh\DomPDF\Facade\Pdf;
        $pdf = PDF::loadView('penjualan.export_pdf', ['penjualan' => $penjualan]);
        $pdf->setPaper('A4', 'portrait'); // set ukuran kertas dan orientasi
        $pdf->setOption("isRemoteEnabled", true); // set true jika ada gambar dari url
        $pdf->render(); // render pdf

        return $pdf->stream('Data Supplier ' . date('Y-m-d H-i-s') . '.pdf');
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
