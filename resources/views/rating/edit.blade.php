<!DOCTYPE html>
<html>
<head><title>Edit Rating</title></head>
<body>
    <h1>Edit Rating</h1>
    @if($errors->any())<div style="color: red;"><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
    <form action="{{ route('rating.update', $rating->id_rating) }}" method="POST">
        @csrf @method('PUT')
        <label>Lamaran:</label><br>
        <select name="id_lamaran">
            @foreach($lamaran as $l)
                <option value="{{ $l->id_lamaran }}" {{ $rating->id_lamaran == $l->id_lamaran ? 'selected' : '' }}>Lamaran #{{ $l->id_lamaran }}</option>
            @endforeach
        </select><br><br>
        <label>Arah Rating:</label><br>
        <select name="arah_rating">
            <option value="pekerja_ke_pemberi" {{ $rating->arah_rating == 'pekerja_ke_pemberi' ? 'selected' : '' }}>Pekerja ke Pemberi</option>
            <option value="pemberi_ke_pekerja" {{ $rating->arah_rating == 'pemberi_ke_pekerja' ? 'selected' : '' }}>Pemberi ke Pekerja</option>
        </select><br><br>
        <label>ID Pemberi Rating:</label><br>
        <input type="number" name="pemberi_rating" value="{{ old('pemberi_rating', $rating->pemberi_rating) }}"><br><br>
        <label>ID Penerima Rating:</label><br>
        <input type="number" name="penerima_rating" value="{{ old('penerima_rating', $rating->penerima_rating) }}"><br><br>
        <label>Skor (1-5):</label><br>
        <input type="number" name="skor" min="1" max="5" value="{{ old('skor', $rating->skor) }}"><br><br>
        <label>Kategori Komentar:</label><br>
        <input type="text" name="kategori_komentar" value="{{ old('kategori_komentar', $rating->kategori_komentar) }}"><br><br>
        <label>Tanggal Rating:</label><br>
        <input type="date" name="tanggal_rating" value="{{ old('tanggal_rating', $rating->tanggal_rating) }}"><br><br>
        <button type="submit">Update</button>
    </form>
    <br><a href="{{ route('rating.index') }}">Kembali ke daftar</a>
</body>
</html>