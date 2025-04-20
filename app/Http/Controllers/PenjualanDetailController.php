<?php

namespace App\Http\Controllers;

use App\Models\BarangModel;
use App\Models\PenjualanDetailModel;
use App\Models\PenjualanModel;
use Barryvdh\DomPDF\Facade\Pdf;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Yajra\DataTables\Facades\DataTables;

class PenjualanDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // DB FACADE
        // $data = DB::select('select * from t_penjualan_detail'); // Mengambil semua data dari tabel t_penjualan_detail
        // return view('penjualan_detail.index', ['data' => $data]);

        //QUERY BUILDER
        // $data = DB::table('t_penjualan_detail')->get(); // Mengambil semua data dari tabel t_penjualan_detail
        // return view('penjualan_detail.index', ['data' => $data]);

        //ELOQUENT ORM
        // $data = PenjualanDetailModel::all();
        // return view('penjualan_detail.index', ['data' => $data]);

        $breadcrumb = (object) [
            'title' => 'Stok Keluar',
            'list' => ['Home', 'Stok Keluar']
        ];

        $page = (object) [
            'title' => 'Daftar Stok Keluar'
        ];

        $activeMenu = 'penjualan_detail';

        return view('penjualan_detail.index', compact('breadcrumb', 'page', 'activeMenu'));
    }

    public function list(Request $request)
    {
        $details = PenjualanDetailModel::select('detail_id', 'penjualan_id', 'barang_id', 'jumlah_barang', 'harga_barang')
            ->with('penjualan')
            ->with('barang')
            ->orderBy('penjualan_id', 'DESC');

        return DataTables::of($details)
            ->addIndexColumn()
            ->addColumn('aksi', function ($penjualan_detail) {

                // $btn = '<button onclick="modalAction(\'' . url('/penjualan_detail/' . $penjualan_detail->detail_id . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                // $btn .= '<button onclick="modalAction(\'' . url('/penjualan_detail/' . $penjualan_detail->detail_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn = '<button onclick="modalAction(\'' . url('/penjualan_detail/' . $penjualan_detail->detail_id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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

    public function import()
    {
        return view('penjualan.import');
    }

    public function import_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                // validasi file harus xls atau xlsx, max 1MB 
                'file_barang' => ['required', 'mimes:xlsx', 'max:1024']
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            $file = $request->file('file_detail');  // ambil file dari request 

            $reader = IOFactory::createReader('Xlsx');  // load reader file excel 
            $reader->setReadDataOnly(true);             // hanya membaca data 
            $spreadsheet = $reader->load($file->getRealPath()); // load file excel 
            $sheet = $spreadsheet->getActiveSheet();    // ambil sheet yang aktif 

            $data = $sheet->toArray(null, false, true, true);   // ambil data excel 

            $insert = [];
            if (count($data) > 1) { // jika data lebih dari 1 baris 
                foreach ($data as $baris => $value) {
                    if ($baris > 1) { // baris ke 1 adalah header, maka lewati 
                        $insert[] = [
                            'detail_id' => $value['A'],
                            'penjualan_id' => $value['B'],
                            'barang_id' => $value['C'],
                            'harga_barang' => $value['D'],
                            'jumlah_barang' => $value['E'],
                            'created_at' => now(),
                        ];
                    }
                }

                if (count($insert) > 0) {
                    // insert data ke database, jika data sudah ada, maka diabaikan 
                    BarangModel::insertOrIgnore($insert);
                }

                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil diimport'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Tidak ada data yang diimport'
                ]);
            }
        }
        return redirect('/');
    }

    public function export_excel()
    {
        // Ambil data penjualan detail dengan relasi ke penjualan, user dan barang
        $details = PenjualanDetailModel::with(['penjualan.user', 'barang'])
            ->orderBy('penjualan_id')
            ->orderBy('detail_id')
            ->get();

        // Load library spreadsheet
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header kolom
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Kode Penjualan');
        $sheet->setCellValue('C1', 'Tanggal Penjualan');
        $sheet->setCellValue('D1', 'Kasir');
        $sheet->setCellValue('E1', 'Nama Barang');
        $sheet->setCellValue('F1', 'Harga Barang');
        $sheet->setCellValue('G1', 'Jumlah Barang');
        $sheet->setCellValue('H1', 'Subtotal');

        // Buat header bold
        $sheet->getStyle('A1:H1')->getFont()->setBold(true);

        // Loop data dan isi ke sheet
        $baris = 2;
        $no = 1;

        foreach ($details as $detail) {
            $penjualan = $detail->penjualan;
            $user = $penjualan->user ?? null;
            $barang = $detail->barang;
            $subtotal = $detail->harga_barang * $detail->jumlah_barang;

            $sheet->setCellValue('A' . $baris, $no++);
            $sheet->setCellValue('B' . $baris, $penjualan->penjualan_kode ?? '-');
            $sheet->setCellValue('C' . $baris, \Carbon\Carbon::parse($penjualan->tanggal_penjualan)->format('d-m-Y'));
            $sheet->setCellValue('D' . $baris, $user->username ?? '-');
            $sheet->setCellValue('E' . $baris, $barang->barang_nama ?? '-');
            $sheet->setCellValue('F' . $baris, $detail->harga_barang);
            $sheet->setCellValue('G' . $baris, $detail->jumlah_barang);
            $sheet->setCellValue('H' . $baris, $subtotal);

            $baris++;
        }

        // Set auto width untuk semua kolom
        foreach (range('A', 'H') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $sheet->setTitle('Detail Penjualan');

        // Tulis file Excel ke output
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data_Penjualan_Detail_' . date('Y-m-d_H-i-s') . '.xlsx';

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
        $details = PenjualanDetailModel::with(['penjualan.user', 'barang'])
            ->orderBy('penjualan_id')
            ->orderBy('detail_id')
            ->get();

        $pdf = Pdf::loadView('penjualan_detail.export_pdf', ['details' => $details]);
        $pdf->setPaper('A4', 'portrait');
        $pdf->setOption("isRemoteEnabled", true);
        $pdf->render();

        return $pdf->stream('Data_Penjualan_Detail_' . date('Y-m-d_H-i-s') . '.pdf');
    }


}
