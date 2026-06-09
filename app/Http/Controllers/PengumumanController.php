<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengumuman;

class PengumumanController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required',
            'isi' => 'required',
            'tanggal' => 'required|date'
        ]);

        Pengumuman::create([
            'judul' => $request->judul,
            'isi' => $request->isi,
            'tanggal' => $request->tanggal
        ]);

        return redirect()->back()->with('success', 'Pengumuman berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $data = Pengumuman::findOrFail($id);

        $data->update([
            'judul' => $request->judul,
            'tanggal' => $request->tanggal,
            'isi' => $request->isi,
        ]);

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        Pengumuman::findOrFail($id)->delete();

        return response()->json(['success' => true]);
    }
}