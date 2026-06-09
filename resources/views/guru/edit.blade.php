<h2>Edit Guru</h2>

<form action="{{ route('guru.update', $guru->id) }}" method="POST">
    @csrf
    @method('PUT')

    Nama: <input type="text" name="nama" value="{{ $guru->nama }}"><br>
    NIP: <input type="text" name="nip" value="{{ $guru->nip }}"><br>
    Email: <input type="email" name="email" value="{{ $guru->email }}"><br>

    <button type="submit">Update</button>
</form>