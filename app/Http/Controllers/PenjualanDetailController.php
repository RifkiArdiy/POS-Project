<?php

namespace App\Http\Controllers;

use App\Models\PenjualanDetailModel;
use DB;
use Illuminate\Http\Request;

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
        $data = PenjualanDetailModel::all();
        return view('penjualan_detail.index', ['data' => $data]);
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
