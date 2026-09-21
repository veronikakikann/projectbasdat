<!DOCTYPE html>
<html>
<head>
    <title>Edit Keahlian Pencari Kerja</title>
</head>
<body>
    <h1>Edit Keahlian Pencari Kerja</h1>

    @if($errors->any())
        <div style="color: red;">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif