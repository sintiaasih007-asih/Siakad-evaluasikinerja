<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Agenda;

class AgendaController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required',
            'deskripsi' => 'required',
            'tanggal' => 'required|date',
            'lokasi' => 'required'
        ]);

        Agenda::create([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'tanggal' => $request->tanggal,
            'lokasi' => $request->lokasi
        ]);

        return redirect()->back()->with('success', 'Agenda berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $agenda = Agenda::findOrFail($id);

        $agenda->update([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'tanggal' => $request->tanggal,
            'lokasi' => $request->lokasi
        ]);

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        Agenda::findOrFail($id)->delete();

        return response()->json(['success' => true]);
    }
}