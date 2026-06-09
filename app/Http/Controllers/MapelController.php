<?php

namespace App\Http\Controllers;

use App\Models\Mapel;
use App\Models\Guru;
use Illuminate\Http\Request;

class MapelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $mapels = Mapel::with('guru')->get();
        $gurus = Guru::all();
        return view('mapel.index', compact('mapels', 'gurus'));
    }

    public function create()
    {
        $gurus = Guru::all();
        return view('mapel.create', compact('gurus'));
    }

    public function store(Request $request)
    {
        Mapel::create($request->all());
        return redirect()->route('mapel.index');
    }

    public function edit($id)
    {
        $mapel = Mapel::findOrFail($id);
        $gurus = Guru::all();

        return view('mapel.edit', compact('mapel', 'gurus'));
    }

    public function update(Request $request, $id)
    {
        $mapel = Mapel::findOrFail($id);
        $mapel->update($request->all());

        return redirect()->route('mapel.index');
    }

    public function destroy($id)
    {
        $mapel = Mapel::findOrFail($id);
        $mapel->delete();

        return redirect()->route('mapel.index');
    }
}