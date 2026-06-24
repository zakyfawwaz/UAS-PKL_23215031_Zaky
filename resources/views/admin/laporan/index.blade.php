@extends('layouts.app')
@section('title', 'Laporan')
@section('page-title', 'Laporan')
@section('content')
<div class="card-clean card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-printer me-2 text-success"></i>Generate Laporan</span>
    </div>
    <div class="card-body p-4">
        <form method="GET" action="{{ route('admin.laporan.cetak') }}" target="_blank">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label fw-semibold" style="font-size:.85rem">Jenis Laporan <span class="text-danger">*</span></label>
                    <select name="jenis_laporan" id="jenis_laporan" class="form-select" required>
                        <option value="dewan" {{ request('jenis_laporan') == 'dewan' ? 'selected' : '' }}>Anggota Dewan</option>
                        <option value="staf" {{ request('jenis_laporan') == 'staf' ? 'selected' : '' }}>TA/SA Fraksi</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold" style="font-size:.85rem">Dari Tanggal</label>
                    <input type="date" name="dari" class="form-control" value="{{ request('dari', now()->startOfMonth()->format('Y-m-d')) }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold" style="font-size:.85rem">Sampai Tanggal</label>
                    <input type="date" name="sampai" class="form-control" value="{{ request('sampai', now()->format('Y-m-d')) }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold" style="font-size:.85rem">Nama <span id="label-nama">Anggota</span></label>
                    <select name="anggota" id="anggota" class="form-select">
                        <option value="">Semua Anggota</option>
                        @foreach($daftarAnggota as $a)
                            <option value="{{ $a->id }}">{{ $a->nama_lengkap }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-success"><i class="bi bi-printer me-1"></i>Cetak Laporan</button>
                <button type="submit" formaction="{{ route('admin.laporan.export-pdf') }}" class="btn btn-outline-danger"><i class="bi bi-file-earmark-pdf me-1"></i>Download Laporan</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const daftarAnggota = @json($daftarAnggota->map(fn($a) => ['value' => $a->id, 'text' => $a->nama_lengkap]));
        const daftarStaf = @json($daftarStaf->map(fn($s) => ['value' => $s->key, 'text' => $s->label]));

        const jenisLaporan = document.getElementById('jenis_laporan');
        const selectAnggota = document.getElementById('anggota');
        const labelNama = document.getElementById('label-nama');

        jenisLaporan.addEventListener('change', function() {
            const type = this.value;
            selectAnggota.innerHTML = '';
            
            const defaultOption = document.createElement('option');
            defaultOption.value = '';
            
            let list = [];
            if (type === 'dewan') {
                labelNama.innerText = 'Anggota';
                defaultOption.text = 'Semua Anggota';
                list = daftarAnggota;
            } else {
                labelNama.innerText = 'TA/SA';
                defaultOption.text = 'Semua TA/SA';
                list = daftarStaf;
            }
            
            selectAnggota.appendChild(defaultOption);

            list.forEach(item => {
                const opt = document.createElement('option');
                opt.value = item.value;
                opt.text = item.text;
                selectAnggota.appendChild(opt);
            });
        });
        
        // Trigger on load in case of old input
        jenisLaporan.dispatchEvent(new Event('change'));
    });
</script>
@endsection
