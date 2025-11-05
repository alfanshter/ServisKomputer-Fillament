<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; font-size: 11px; }
        .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 5px; margin-bottom: 10px; }
        .logo { width: 70px; }
        .title { font-size: 16px; font-weight: bold; margin-top: 3px; }
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        th, td { padding: 6px; border: 1px solid #000; text-align: left; }
        .no-border td { border: none; padding: 3px 0; }
        .sign-table td { border: none; text-align: center; padding-top: 40px; }
    </style>
</head>
<body>

    <div class="header">
        <img src="{{ public_path('logopws.jpg') }}" class="logo"><br>
        <b>PWS COMPUTER</b><br>
        Jalan Raya Wonosari, Gondangwetan, Kab. Pasuruan<br>
        Telp: 0822-3246-9415
        <div class="title">INVOICE SERVIS</div>
        <div>No: {{ str_pad($data->id, 5, '0', STR_PAD_LEFT) }}</div>
    </div>

    <table class="no-border">
        <tr>
            <td><b>Nama Customer</b>: {{ $data->user->name }}</td>
            <td><b>Nomor HP</b>: {{ $data->user->phone }}</td>
        </tr>
        <tr>
            <td><b>Alamat</b>: {{ $data->user->address }}</td>
            <td><b>Tanggal Invoice</b>: {{ now()->format('d/m/Y') }}</td>
        </tr>
    </table>

    <table>
        <tr>
            <th>No</th>
            <th>Deskripsi</th>
            <th>Qty</th>
            <th>Harga</th>
            <th>Total</th>
        </tr>

        @php $no = 1; @endphp
        @foreach($data->items->take(5) as $item)
        <tr>
            <td>{{ $no++ }}</td>
            <td>{{ $item->name }}</td>
            <td>{{ $item->qty }}</td>
            <td>Rp {{ number_format($item->price) }}</td>
            <td>Rp {{ number_format($item->qty * $item->price) }}</td>
        </tr>
        @endforeach

        {{-- Tambah baris kosong jika kurang dari 5 item --}}
        @for ($i = $data->items->count(); $i < 5; $i++)
        <tr>
            <td>&nbsp;</td><td></td><td></td><td></td><td></td>
        </tr>
        @endfor

        <tr>
            <td colspan="4" style="text-align: right;"><b>Grand Total</b></td>
            <td><b>Rp {{ number_format($data->total_biaya) }}</b></td>
        </tr>
        <tr>
            <td colspan="4" style="text-align: right;">Status Pembayaran</td>
            <td>
                <b>{{ $data->status_pembayaran == 'lunas' ? 'LUNAS' : 'BELUM LUNAS' }}</b>
            </td>
        </tr>
    </table>

    <table class="sign-table" width="100%" style="margin-top: 25px;">
        <tr>
            <td>Admin,<br><br><br><br>(_______________________)</td>
            <td>Customer,<br><br><br><br>(_______________________)</td>
        </tr>
    </table>

</body>
</html>
