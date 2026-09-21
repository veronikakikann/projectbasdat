<!DOCTYPE html>
<html>
<head><title>Edit Lamaran</title></head>
<body>
    <h1>Edit Lamaran</h1>
    @if($errors->any())<div style="color: red;"><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
    <form action="{{ route('lamaran.update', $lamaran->id_lamaran) }}" method="POST">
        @csrf @method('PUT')
        <label>Pekerjaan:</label><br>
        <select name="id_pekerjaan">
            @foreach($pekerjaan as $p)
                <option value="{{ $p->id_pekerjaan }}" {{ $lamaran->id_pekerjaan == $p->id_pekerjaan ? 'selected' : '' }}>{{ $p->deskripsi }}</option>
            @endforeach
        </select><br><br>
        <label>Pencari Kerja:</label><br>
        <select name="id_pencari">
            @foreach($pencariKerja as $pk)
                <option value="{{ $pk->id_pencari }}" {{ $lamaran->id_pencari == $pk->id_pencari ? 'selected' : '' }}>{{ $pk->nama }}</option>
            @endforeach
        </select><br><br>
        <label>Status Lamaran:</label><br>
        <select name="status_lamaran">
            <option value="menunggu" {{ $lamaran->status_lamaran == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
            <option value="diterima" {{ $lamaran->status_lamaran == 'diterima' ? 'selected' : '' }}>Diterima</option>
            <option value="ditolak" {{ $lamaran->status_lamaran == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
        </select><br><br>
        <label>Tanggal Submit:</label><br>
        <input type="date" name="tanggal_submit" value="{{ old('tanggal_submit', $lamaran->tanggal_submit) }}"><br><br>
        <button type="submit">Update</button>
    </form>
    <br><a href="{{ route('lamaran.index') }}">Kembali ke daftar</a>
</body>
</html>