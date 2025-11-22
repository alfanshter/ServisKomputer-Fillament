<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Invoice #{{ str_pad($data->id, 5, '0', STR_PAD_LEFT) }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Arial', sans-serif;
            font-size: 11px;
            padding: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #2563eb;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .logo { width: 80px; margin-bottom: 10px; }
        .company-name {
            font-size: 20px;
            font-weight: bold;
            color: #2563eb;
            margin: 5px 0;
        }
        .company-info {
            font-size: 10px;
            color: #666;
            line-height: 1.5;
        }
        .invoice-title {
            font-size: 24px;
            font-weight: bold;
            margin: 15px 0 5px 0;
            color: #1e40af;
        }
        .invoice-number {
            font-size: 12px;
            color: #666;
        }

        .info-section {
            margin: 20px 0;
            display: table;
            width: 100%;
        }
        .info-left, .info-right {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }
        .info-box {
            background: #f8fafc;
            padding: 12px;
            border-radius: 5px;
            border: 1px solid #e2e8f0;
            min-height: 100px;
        }
        .info-box h3 {
            font-size: 12px;
            color: #2563eb;
            margin-bottom: 8px;
            font-weight: bold;
        }
        .info-box p {
            margin: 4px 0;
            line-height: 1.6;
        }
        .info-label {
            font-weight: bold;
            color: #475569;
            display: inline-block;
            width: 100px;
        }

        .detail-section {
            margin: 20px 0;
            background: #f8fafc;
            padding: 12px;
            border-radius: 5px;
            border: 1px solid #e2e8f0;
        }
        .detail-section h3 {
            font-size: 12px;
            color: #2563eb;
            margin-bottom: 8px;
            font-weight: bold;
        }
        .detail-section p {
            margin: 5px 0;
            line-height: 1.6;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        th {
            background: #2563eb;
            color: white;
            padding: 10px;
            text-align: left;
            font-weight: bold;
            font-size: 11px;
        }
        td {
            padding: 10px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 11px;
        }
        tr:hover td {
            background: #f8fafc;
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }

        .summary-table {
            width: 100%;
            margin-top: 15px;
        }
        .summary-table td {
            border: none;
            padding: 5px 10px;
        }
        .summary-row {
            font-size: 11px;
        }
        .summary-row.subtotal td {
            border-top: 1px solid #e2e8f0;
            padding-top: 10px;
        }
        .summary-row.total {
            background: #2563eb;
            color: white;
            font-weight: bold;
            font-size: 14px;
        }
        .summary-row.total td {
            padding: 12px 10px;
        }

        .notes {
            margin-top: 20px;
            padding: 15px;
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
            border-radius: 5px;
        }
        .notes h4 {
            font-size: 12px;
            color: #92400e;
            margin-bottom: 8px;
        }
        .notes p {
            font-size: 10px;
            color: #78350f;
            line-height: 1.6;
        }

        .footer {
            margin-top: 30px;
        }
        .signature-section {
            display: table;
            width: 100%;
            margin-top: 40px;
        }
        .signature-box {
            display: table-cell;
            width: 50%;
            text-align: center;
        }
        .signature-box strong {
            display: block;
            margin-bottom: 60px;
            font-size: 12px;
        }
        .signature-line {
            border-top: 1px solid #333;
            margin: 0 auto;
            width: 200px;
            padding-top: 5px;
            font-size: 11px;
        }
    </style>
</head>
<body>

    <div class="header">
        <img src="{{ public_path('logopws.jpg') }}" class="logo" alt="Logo">
        <div class="company-name">PWS COMPUTER SERVICE CENTER</div>
        <div class="company-info">
            Jalan Raya Wonosari, Gondangwetan, Kab. Pasuruan<br>
            Telp: 0822-3246-9415 | Email: pwscomputer@gmail.com
        </div>
        <div class="invoice-title">INVOICE</div>
        <div class="invoice-number">No: INV-{{ str_pad($data->id, 5, '0', STR_PAD_LEFT) }}</div>
    </div>

    <div class="info-section">
        <div class="info-left">
            <div class="info-box">
                <h3>Informasi Customer</h3>
                <p><span class="info-label">Nama</span>: {{ $data->user->name }}</p>
                <p><span class="info-label">No. HP</span>: {{ $data->user->phone }}</p>
                <p><span class="info-label">Alamat</span>: {{ $data->user->address ?? '-' }}</p>
            </div>
        </div>
        <div class="info-right" style="padding-left: 10px;">
            <div class="info-box">
                <h3>Detail Invoice</h3>
                <p><span class="info-label">Tanggal</span>: {{ now()->format('d F Y') }}</p>
                <p><span class="info-label">Perangkat</span>: {{ $data->device_type }}</p>
            </div>
        </div>
    </div>

    <h3 style="margin-top: 25px; margin-bottom: 10px; color: #2563eb;">Rincian Biaya</h3>
    <table>
        <thead>
            <tr>
                <th width="5%" class="text-center">No</th>
                <th width="50%">Deskripsi</th>
                <th width="10%" class="text-center">Qty</th>
                <th width="17%" class="text-right">Harga Satuan</th>
                <th width="18%" class="text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @php
                $no = 1;
                $totalBiaya = 0;
            @endphp

            {{-- Jasa Service dari Master Data --}}
            @foreach($data->services as $service)
            <tr>
                <td class="text-center">{{ $no++ }}</td>
                <td><strong>{{ $service->name }}</strong><br><small style="color: #666;">Jasa Service</small></td>
                <td class="text-center">{{ $service->pivot->quantity }}</td>
                <td class="text-right">Rp {{ number_format($service->pivot->price, 0, ',', '.') }}</td>
                <td class="text-right"><strong>Rp {{ number_format($service->pivot->subtotal, 0, ',', '.') }}</strong></td>
            </tr>
            @php $totalBiaya += $service->pivot->subtotal; @endphp
            @endforeach

            {{-- Sparepart --}}
            @foreach($data->spareparts as $sparepart)
            <tr>
                <td class="text-center">{{ $no++ }}</td>
                <td><strong>{{ $sparepart->name }}</strong><br><small style="color: #666;">Sparepart</small></td>
                <td class="text-center">{{ $sparepart->pivot->quantity }}</td>
                <td class="text-right">Rp {{ number_format($sparepart->pivot->price, 0, ',', '.') }}</td>
                <td class="text-right"><strong>Rp {{ number_format($sparepart->pivot->subtotal, 0, ',', '.') }}</strong></td>
            </tr>
            @php $totalBiaya += $sparepart->pivot->subtotal; @endphp
            @endforeach

            {{-- Baris kosong jika kurang dari 3 item --}}
            @php
                $itemCount = $data->services->count() + $data->spareparts->count();
                $emptyRows = max(0, 3 - $itemCount);
            @endphp
            @for ($i = 0; $i < $emptyRows; $i++)
            <tr>
                <td colspan="5" style="height: 30px;">&nbsp;</td>
            </tr>
            @endfor
        </tbody>
    </table>

    <table class="summary-table">
        <tr class="summary-row subtotal">
            <td width="70%" class="text-right"><strong>Subtotal:</strong></td>
            <td width="30%" class="text-right">Rp {{ number_format($totalBiaya, 0, ',', '.') }}</td>
        </tr>
        @if($data->discount > 0)
        <tr class="summary-row">
            <td class="text-right">Diskon:</td>
            <td class="text-right">- Rp {{ number_format($data->discount, 0, ',', '.') }}</td>
        </tr>
        @endif
        <tr class="summary-row total">
            <td class="text-right">TOTAL PEMBAYARAN:</td>
            <td class="text-right">Rp {{ number_format($data->total_cost ?? ($totalBiaya - ($data->discount ?? 0)), 0, ',', '.') }}</td>
        </tr>
    </table>

    <div class="notes">
        <h4>Catatan Penting</h4>
        <p>
            • Invoice ini merupakan bukti resmi transaksi servis komputer<br>
            • Harap menyimpan invoice ini sebagai bukti garansi servis<br>
            • Garansi servis berlaku 30 hari (tidak termasuk sparepart)<br>
            • Untuk komplain atau pertanyaan, hubungi nomor yang tertera di atas
        </p>
    </div>

    <div class="footer">
        <div class="signature-section">
            <div class="signature-box">
                <strong>Teknisi</strong>
                <div class="signature-line">
                    (...................................)
                </div>
            </div>
            <div class="signature-box">
                <strong>Customer</strong>
                <div class="signature-line">
                    {{ $data->user->name }}
                </div>
            </div>
        </div>

        <p style="text-align: center; margin-top: 30px; font-size: 10px; color: #666;">
            Terima kasih atas kepercayaan Anda menggunakan layanan kami.<br>
            <strong>PWS Computer Service Center</strong> - Solusi Terpercaya untuk Perangkat Anda
        </p>
    </div>

</body>
</html>
