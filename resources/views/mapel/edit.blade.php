<form method="POST" action="{{ route('mapel.update', $mapel->id) }}">
    @csrf
    @method('PUT')

    <input type="text" name="kode_mapel" value="{{ $mapel->kode_mapel }}">
    <input type="text" name="nama_mapel" value="{{ $mapel->nama_mapel }}">

    <select name="guru_id">
        @foreach($gurus as $g)
            <option value="{{ $g->id }}" {{ $mapel->guru_id == $g->id ? 'selected' : '' }}>
                {{ $g->nama }}
            </option>
        @endforeach
    </select>

    <button type="submit">Update</button>
</form>