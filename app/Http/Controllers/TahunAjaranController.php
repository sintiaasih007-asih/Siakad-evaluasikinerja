<?php

namespace App\Http\Controllers;

use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class TahunAjaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = TahunAjaran::all();
        return view('tahun_ajaran.index', compact('data'));
    }

    public function create()
    {
        return view('tahun_ajaran.create');
    }

    public function store(Request $request)
    {
        // kalau aktif, nonaktifkan yang lain
        if ($request->is_active) {
            TahunAjaran::where('is_active', true)->update(['is_active' => false]);
        }

        TahunAjaran::create($request->all());

        return redirect()->route('tahun-ajaran.index');
    }

    public function edit($id)
    {
        $data = TahunAjaran::findOrFail($id);
        return view('tahun_ajaran.edit', compact('data'));
    }

    public function update(Request $request, string $id)
    {
        $ta = TahunAjaran::findOrFail($id);

        if ($request->is_active) {
            TahunAjaran::where('is_active', true)->update(['is_active' => false]);
        }

        $ta->update($request->all());

        return redirect()->route('tahun-ajaran.index');
    }

    public function destroy(string $id)
    {
        TahunAjaran::destroy($id);
        return redirect()->route('tahun-ajaran.index');
    }
}