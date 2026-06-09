<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use Illuminate\Http\Request;

class GuruController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $gurus = Guru::all();
        return view('guru.index', compact('gurus'));

    }

    public function create()
    {
        return view('guru.create');
    }

    public function store(Request $request)
    {
        $request->validate([

            'nama' => 'required|string|max:255',

            'nip' => 'nullable|string|max:255',

            'email' => 'nullable|email|max:255',

            'face_descriptor' => 'required',

        ], [

            'face_descriptor.required' =>
            'Silakan rekam wajah guru terlebih dahulu.'

        ]);
        
        Guru::create([

            'nama' => $request->nama,

            'nip' => $request->nip,

            'email' => $request->email,

            'face_descriptor' =>
                $request->face_descriptor,

        ]);

        return redirect()
            ->route('guru.index')
            ->with(
                'success',
                'Data guru berhasil ditambahkan.'
            );
    }

    public function edit($id)
    {
        $guru = Guru::findOrFail($id);
        return view('guru.edit', compact('guru'));
    }

    public function update(Request $request, $id)
    {
        $guru = Guru::findOrFail($id);
        // $guru->update($request->all());

    

        $guru->update([
            'nama' => $request->nama,
            'nip' => $request->nip,
            'email' => $request->email,
        ]);

        return redirect()->route('guru.index')
            ->with('success', 'Data berhasil diupdate');
    }

    public function destroy($id)
    {
        $guru = Guru::findOrFail($id);
        $guru->delete();

        return redirect()->route('guru.index');
    }
}
