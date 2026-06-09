@extends('layouts.app')
@section('title', isset($aktivitas) ? 'Edit Aktivitas' : 'Tambah Aktivitas')
@section('page-title', isset($aktivitas) ? 'Edit Aktivitas' : 'Tambah Aktivitas')
@section('content')
<div class="row justify-content-center"><div class="col-lg-8">
    <div class="card-clean card">
        <div class="card-body p-4">
            <form method="POST" action="{{ isset($aktivitas) ? route('anggota.aktivitas.update', $aktivitas) : route('anggota.aktivitas.store') }}" enctype="multipart/form-data">
                @csrf
                @if(isset($aktivitas)) @method('PUT') @endif
                <div class="row g-3">
                    <div class="col-md-4"><label class="form-label fw-semibold" style="font-size:.85rem">Tanggal</label><input type="date" name="tanggal" class="form-control @error('tanggal') is-invalid @enderror" value="{{ old('tanggal', isset($aktivitas) ? $aktivitas->tanggal->format('Y-m-d') : date('Y-m-d')) }}" required>@error('tanggal')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                    <div class="col-md-4"><label class="form-label fw-semibold" style="font-size:.85rem">Waktu</label><input type="time" name="waktu" class="form-control" value="{{ old('waktu', $aktivitas->waktu ?? '08:00') }}" required></div>
                    <div class="col-md-4"><label class="form-label fw-semibold" style="font-size:.85rem">Kategori</label>
                        <select name="kategori" class="form-select @error('kategori') is-invalid @enderror" required>
                            <option value="">-- Pilih --</option>
                            @foreach(\App\Models\Aktivitas::kategoriGrouped() as $g => $items)
                                <optgroup label="{{ $g }}">@foreach($items as $v => $l)<option value="{{ $v }}" {{ old('kategori', $aktivitas->kategori ?? '') == $v ? 'selected' : '' }}>{{ $l }}</option>@endforeach</optgroup>
                            @endforeach
                        </select>@error('kategori')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12"><label class="form-label fw-semibold" style="font-size:.85rem">Nama Kegiatan</label><input type="text" name="nama_kegiatan" class="form-control @error('nama_kegiatan') is-invalid @enderror" value="{{ old('nama_kegiatan', $aktivitas->nama_kegiatan ?? '') }}" required>@error('nama_kegiatan')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                    <div class="col-12"><label class="form-label fw-semibold" style="font-size:.85rem">Lokasi</label><input type="text" name="lokasi" class="form-control @error('lokasi') is-invalid @enderror" value="{{ old('lokasi', $aktivitas->lokasi ?? '') }}" required>@error('lokasi')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                    <div class="col-12"><label class="form-label fw-semibold" style="font-size:.85rem">Deskripsi</label><textarea name="deskripsi_kegiatan" class="form-control" rows="3">{{ old('deskripsi_kegiatan', $aktivitas->deskripsi_kegiatan ?? '') }}</textarea></div>
                    <div class="col-12">
                        <label class="form-label fw-semibold" style="font-size:.85rem">Dokumentasi Foto</label>
                        <input type="file" name="dokumentasi_foto" class="form-control @error('dokumentasi_foto') is-invalid @enderror" accept=".jpg,.jpeg,.png">
                        @error('dokumentasi_foto')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <div class="form-text text-muted" style="font-size:.78rem">Ukuran file maksimal: 5 MB. Format yang didukung: JPG, JPEG, PNG.</div>
                        @if(isset($aktivitas) && $aktivitas->dokumentasi_foto)
                            <div class="mt-2">
                                <small class="text-muted d-block mb-1" style="font-size:.78rem"><i class="bi bi-image me-1"></i>Foto saat ini:</small>
                                <img src="{{ $aktivitas->dokumentasi_foto_url }}" alt="Dokumentasi Foto" class="rounded border" style="max-height:180px; max-width:100%; object-fit:cover;">
                            </div>
                        @endif
                    </div>
                </div>
                <div class="d-flex gap-2 mt-4"><button class="btn btn-success px-4"><i class="bi bi-check-lg me-1"></i>Simpan</button><a href="{{ route('anggota.aktivitas.index') }}" class="btn btn-outline-secondary">Batal</a></div>
            </form>
        </div>
    </div>
</div></div>
@endsection
