<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Aktivitas — DPRD PKS Tegal</title>
    <style>
        * { font-family: 'Times New Roman', serif; font-size: 12pt; }
        body { margin: 2cm; }
        .kop { text-align: center; border-bottom: 3px double #000; padding-bottom: 10px; margin-bottom: 20px; }
        .kop h2 { margin: 0; font-size: 16pt; }
        .kop p { margin: 2px 0; font-size: 10pt; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #333; padding: 6px 8px; font-size: 10pt; }
        th { background: #f0f0f0; text-align: center; }
        .ttd { margin-top: 40px; text-align: right; }
        @media print { body { margin: 1cm; } }
    </style>
</head>
<body onload="window.print()">
    <div class="kop">
        <h2>DPRD KOTA TEGAL</h2>
        <p>FRAKSI PARTAI KEADILAN SEJAHTERA (PKS)</p>
        <p style="font-size:9pt">Jl. Pemuda No. 4, Kota Tegal</p>
    </div>

    <h3 style="text-align:center; margin-bottom:5px">{{ $title }}</h3>
    <div style="text-align:center; font-size:10pt; color:#333; margin-bottom: 15px;">
        <p style="margin: 2px 0;"><strong>Jenis Laporan:</strong> {{ $jenis === 'dewan' ? 'Anggota Dewan' : 'TA/SA Fraksi' }}</p>
        <p style="margin: 2px 0;"><strong>Nama:</strong> {{ $namaTampil }}</p>
        <p style="margin: 2px 0;"><strong>Periode:</strong> {{ \Carbon\Carbon::parse($dari)->format('d M Y') }} — {{ \Carbon\Carbon::parse($sampai)->format('d M Y') }}</p>
        <p style="margin: 2px 0;"><strong>Tanggal Cetak:</strong> {{ now()->format('d M Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr><th>No</th><th>Tanggal</th><th>Waktu</th><th>Nama Kegiatan</th><th>Kategori</th><th>Lokasi</th><th>Deskripsi</th></tr>
        </thead>
        <tbody>
            @forelse($aktivitas as $i => $akt)
                <tr>
                    <td style="text-align:center">{{ $i + 1 }}</td>
                    <td>{{ $akt->tanggal->format('d/m/Y') }}</td>
                    <td>{{ $akt->waktu }}</td>
                    <td>{{ $akt->nama_kegiatan }}</td>
                    <td>{{ $akt->label_kategori }}</td>
                    <td>{{ $akt->lokasi }}</td>
                    <td>{{ $akt->deskripsi_kegiatan ?? '-' }}</td>
                </tr>
            @empty
                <tr><td colspan="7" style="text-align:center">Tidak ada data aktivitas.</td></tr>
            @endforelse
        </tbody>
    </table>

    <p style="margin-top:15px; font-size:10pt"><strong>Total Aktivitas: {{ $aktivitas->count() }}</strong></p>

    <div class="ttd">
        <p>Tegal, {{ now()->translatedFormat('d F Y') }}</p>
        <p>Ketua Fraksi PKS</p>
        <br><br><br>
        <p>________________________</p>
    </div>
</body>
</html>
