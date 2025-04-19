<?php

namespace App\Http\Controllers;

use App\Models\PenjualanDetailModel;
use App\Models\PenjualanModel;
use App\Models\StokModel;
use App\Models\UserModel;
use Auth;
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

        $userByLevel = UserModel::select('m_level.level_nama', DB::raw('COUNT(*) as total'))
            ->join('m_level', 'm_level.level_id', '=', 'm_user.level_id')
            ->groupBy('m_level.level_nama')
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
        
        return view('welcome', compact('breadcrumb', 'userByLevel', 'penjualanDetail', 'penjualanTerakhir', 'totalUser', 'totalPenjualan', 'totalStok', 'activeMenu'));
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
