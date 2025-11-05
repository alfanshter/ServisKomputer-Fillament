<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 15px; }
        .logo { width: 80px; }
        .title { font-size: 18px; font-weight: bold; margin-top: 5px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 6px; text-align: left; vertical-align: top; }
        .footer { margin-top: 40px; font-size: 11px; text-align: center; border-top: 1px dashed #000; padding-top: 5px; }
        .terms { font-size: 10px; margin-top: 25px; }
        .sign { margin-top: 40px; text-align: right; font-size: 12px; }
    </style>
</head>
<body>

    <div class="header">
        <img src="{{ public_path('logopws.jpg') }}" class="logo">
        <div class="title">TANDA TERIMA SERVIS</div>
        <div>No: {{ str_pad($data->id, 5, '0', STR_PAD_LEFT) }}</div>
    </div>

    <table>
        <tr>
            <td><b>Nama Customer</b>: {{ $data->user->name }}</td>
            <td><b>Nomor HP</b>: {{ $data->user->phone }}</td>
        </tr>
        <tr>
            <td><b>Alamat</b>: {{ $data->user->address }}</td>
            <td><b>Tanggal Masuk</b>: {{ $data->created_at->format('d/m/Y') }}</td>
        </tr>
    </table>

    <table border="1" style="margin-top: 15px;">
        <tr>
            <th>Tipe Barang</th>
            <th>Kerusakan</th>
            <th>Kelengkapan</th>
            <th>Estimasi Biaya</th>
        </tr>
        <tr>
            <td>{{ $data->device_type }}</td>
            <td>{{ $data->damage_description }}</td>
            <td>{{ $data->kelengkapan ?? '' }}</td>
            <td>Rp {{ number_format($data->estimasi_biaya) }}</td>
        </tr>
    </table>

    <div class="terms">
        <b>Syarat & Ketentuan:</b>
        <ol>
            <li>Pelanggan wajib menyimpan tanda terima ini untuk pengambilan barang.</li>
            <li>Barang yang tidak diambil lebih dari 2 bulan dianggap ditinggalkan dan bukan tanggung jawab kami.</li>
            <li>Segala kerusakan atau kehilangan akibat bencana, pencurian, dan force majeure bukan tanggung jawab kami.</li>
            <li>Kami tidak bertanggung jawab atas kerusakan lain yang muncul selain keluhan awal.</li>
            <li>Pengecekan tidak dikenakan biaya jika tidak jadi servis.</li>
        </ol>
    </div>

    <div class="sign">
        Hormat Kami,<br><br><br><br>
        (.......................................)
    </div>

    <div class="footer">
        Barang sedang dalam proses pengecekan / perbaikan. Harap simpan tanda terima ini untuk pengambilan barang.
    </div>

</body>
</html>
