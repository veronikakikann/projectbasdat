<!DOCTYPE html>
<html>
<head><title>Edit Bukti Penyelesaian</title></head>
<body>
    <h1>Edit Bukti Penyelesaian</h1>
    @if($errors->any())<div style="color: red;"><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
    <form action="{{ route('bukti_penyelesaian.update', $bukti->id_bukti) }}" method="POST">
        @csrf @method('PUT')
        <label>Lamaran:</label><br>
        <select name="id_lamaran">
            @foreach($lamaran as $l)
                <option value="{{ $l->id_lamaran }}" {{ $bukti->id_lamaran == $l->id_lamaran ? 'selected' : '' }}>Lamaran #{{ $l->id_lamaran }}</option>
            @endforeach
        </select><br><br>
        <label>Foto Bukti Kerja:</label><br>
        <input type="text" name="foto_bukti_kerja" value="{{ old('foto_bukti_kerja', $bukti->foto_bukti_kerja) }}"><br><br>
        <label>Foto Bukti Bayar:</label><br>
        <input type="text" name="foto_bukti_bayar" value="{{ old('foto_bukti_bayar', $bukti->foto_bukti_bayar) }}"><br><br>
        <label>Catatan:</label><br>
        <textarea name="catatan">{{ old('catatan', $bukti->catatan) }}</textarea><br><br>
        <label>Tanggal Upload:</label><br>
        <input type="date" name="tanggal_upload" value="{{ old('tanggal_upload', $bukti->tanggal_upload) }}"><br><br>
        <button type="submit">Update</button>
    </form>
    <br><a href="{{ route('bukti_penyelesaian.index') }}">Kembali ke daftar</a>
</body>
</html>