<h2>Edit Tahun Ajaran</h2>

<form action="{{ route('tahun-ajaran.update', $data->id) }}" method="POST">
    @csrf
    @method('PUT')

    Tahun: <input type="text" name="tahun" value="{{ $data->tahun }}"><br>

    Semester:
    <select name="semester">
        <option value="ganjil" {{ $data->semester == 'ganjil' ? 'selected' : '' }}>Ganjil</option>
        <option value="genap" {{ $data->semester == 'genap' ? 'selected' : '' }}>Genap</option>
    </select><br>

    Aktif:
    <input type="checkbox" name="is_active" value="1" {{ $data->is_active ? 'checked' : '' }}><br>

    <button>Update</button>
</form>