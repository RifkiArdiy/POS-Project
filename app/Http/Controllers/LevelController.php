<?php

namespace App\Http\Controllers;

use App\Models\LevelModel;
use DB;
use Illuminate\Http\Request;

class LevelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // DB FACADE
        // $data = DB::select('select * from m_level'); // Mengambil semua data dari tabel m_level
        // return view('level.index', ['data' => $data]);

        //QUERY BUILDER
        // $data = DB::table('m_level')->get(); // Mengambil semua data dari tabel m_level
        // return view('level.index', ['data' => $data]);

        //ELOQUENT ORM
        $data = LevelModel::all();
        return view('level.index', ['data' => $data]);
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
