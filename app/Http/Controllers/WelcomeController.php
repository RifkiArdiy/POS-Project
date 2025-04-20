<?php

namespace App\Http\Controllers;

use App\Models\PenjualanDetailModel;
use App\Models\PenjualanModel;
use App\Models\StokModel;
use App\Models\UserModel;
use Auth;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class WelcomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $breadcrumb = (object) [
            'title' => 'Dashboard',
            'list' => ['Home', 'Dashboard']
        ];

        $user = UserModel::select('user_id', 'username', 'nama', 'level_id')
            ->with('level')
            ->get();

        // $userByLevel = UserModel::select('m_level.level_nama', DB::raw('COUNT(*) as total'))
        //     ->join('m_level', 'm_level.level_id', '=', 'm_user.level_id')
        //     ->groupBy('m_level.level_nama')
        //     ->get();
        $barangTerjual = DB::table('t_penjualan_detail')
            ->join('m_barang', 'm_barang.barang_id', '=', 't_penjualan_detail.barang_id')
            ->select('m_barang.barang_nama', DB::raw('SUM(t_penjualan_detail.jumlah_barang) as total'))
            ->groupBy('m_barang.barang_nama')
            ->orderByDesc('total')
            ->get();


        $penjualanDetail = PenjualanDetailModel::select(
            DB::raw('MONTH(created_at) as bulan'),
            DB::raw('YEAR(created_at) as tahun'),
            DB::raw('COUNT(*) as total')
        )
            ->groupBy('tahun', 'bulan')
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->limit(6)
            ->get();

        // Penjualan terakhir (5 data)
        $penjualanTerakhir = PenjualanModel::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $user = Auth::user();

        // Total jumlah pengguna
        $totalUser = UserModel::count();

        // Total stok (jumlah semua stok_jumlah)
        $totalStok = StokModel::sum('stok_jumlah');

        $totalPenjualan = PenjualanDetailModel::sum(DB::raw('harga_barang * jumlah_barang'));
        $activeMenu = 'dashboard';

        return view('welcome', compact('breadcrumb', 'barangTerjual', 'penjualanDetail', 'penjualanTerakhir', 'totalUser', 'totalPenjualan', 'totalStok', 'activeMenu'));
    }



    public function incomeByRange(Request $request)
    {
        try {
            // contoh query logika filter range kamu
            $range = $request->input('range');
            $query = PenjualanDetailModel::selectRaw('MONTH(penjualan.tanggal_penjualan) as bulan, SUM(harga_barang * jumlah_barang) as total')
                ->join('t_penjualan as penjualan', 'penjualan.penjualan_id', '=', 't_penjualan_detail.penjualan_id');

            // filter berdasarkan range
            switch ($range) {
                case 'today':
                    $query->whereDate('penjualan.tanggal_penjualan', now());
                    break;
                case 'yesterday':
                    $query->whereDate('penjualan.tanggal_penjualan', now()->subDay());
                    break;
                case '7days':
                    $query->whereBetween('penjualan.tanggal_penjualan', [now()->subDays(6), now()]);
                    break;
                case '30days':
                    $query->whereBetween('penjualan.tanggal_penjualan', [now()->subDays(29), now()]);
                    break;
                case 'this_month':
                    $query->whereMonth('penjualan.tanggal_penjualan', now()->month);
                    break;
                default:
                    // fallback: semua data
                    break;
            }

            // $data = $query->groupBy('bulan')->orderBy('bulan')->get();
            $data = PenjualanModel::selectRaw('YEAR(tanggal_penjualan) as tahun, MONTH(tanggal_penjualan) as bulan, SUM(harga_barang * jumlah_barang) as total')
            ->join('t_penjualan_detail', 't_penjualan.penjualan_id', '=', 't_penjualan_detail.penjualan_id')
            ->whereYear('tanggal_penjualan', now()->year)
            ->groupByRaw('YEAR(tanggal_penjualan), MONTH(tanggal_penjualan)')
            ->orderByRaw('YEAR(tanggal_penjualan), MONTH(tanggal_penjualan)')
            ->get();
        

            return response()->json($data);
        } catch (\Throwable $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessage(),
            ], 500);
        }
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
