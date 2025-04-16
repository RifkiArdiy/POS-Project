<?php

namespace App\Http\Controllers;

use App\Models\PenjualanDetailModel;
use DB;
use Illuminate\Http\Request;
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
            'title' => 'Penjualan Detail',
            'list' => ['Home', 'Penjualan Detail']
        ];

        $page = (object) [
            'title' => 'Daftar Penjualan Detail yang terdaftar dalam sistem'
        ];

        $activeMenu = 'penjualan_detail';

        return view('penjualan_detail.index', compact('breadcrumb', 'page', 'activeMenu'));
    }

    public function list(Request $request)
    {
        $details = PenjualanDetailModel::select('detail_id', 'penjualan_id', 'barang_id', 'jumlah_barang', 'harga_barang' )
            ->with('penjualan')
            ->with('barang');

        return DataTables::of($details)
            ->addIndexColumn()
            ->addColumn('aksi', function ($penjualan_detail) {
                
                $btn = '<button onclick="modalAction(\'' . url('/penjualan_detail/' . $penjualan_detail->detail_id . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/penjualan_detail/' . $penjualan_detail->detail_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/penjualan_detail/' . $penjualan_detail->detail_id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
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
}
