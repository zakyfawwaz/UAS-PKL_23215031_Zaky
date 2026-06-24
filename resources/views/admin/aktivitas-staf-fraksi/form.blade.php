@extends('layouts.app')
@section('title', isset($aktivitas) ? 'Edit Aktivitas Staf Fraksi' : 'Tambah Aktivitas Staf Fraksi')
@section('page-title', isset($aktivitas) ? 'Edit Aktivitas Staf Fraksi' : 'Tambah Aktivitas Staf Fraksi')
@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card-clean card">
            <div class="card-header"><i class="bi bi-calendar-plus me-2 text-success"></i>{{ isset($aktivitas) ? 'Edit Data' : 'Data Baru' }}</div>
            <div class="card-body p-4">
                <form method="POST" action="{{ isset($aktivitas) ? route('admin.aktivitas-staf-fraksi.update', ['type' => $aktivitas->pelaku_type, 'id' => $aktivitas->id]) : route('admin.aktivitas-staf-fraksi.store') }}" enctype="multipart/form-data">
                    @csrf
                    @if(isset($aktivitas)) @method('PUT') @endif

                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label fw-semibold" style="font-size:.85rem">Staf Fraksi <span class="text-danger">*</span></label>
                            <select name="staf_id" class="form-select @error('staf_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Staf Fraksi --</option>
                                @foreach($daftarStaf as $staf)
                                    <option value="{{ $staf->key }}" {{ old('staf_id', $aktivitas->staf_id ?? '') == $staf->key ? 'selected' : '' }}>{{ $staf->label }}</option>
                                @endforeach
                            </select>
                            @error('staf_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold" style="font-size:.85rem">Tanggal <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal" class="form-control @error('tanggal') is-invalid @enderror" value="{{ old('tanggal', isset($aktivitas) ? $aktivitas->tanggal->format('Y-m-d') : date('Y-m-d')) }}" required>
                            @error('tanggal')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold" style="font-size:.85rem">Waktu <span class="text-danger">*</span></label>
                            <input type="time" name="waktu" class="form-control @error('waktu') is-invalid @enderror" value="{{ old('waktu', $aktivitas->waktu ?? '08:00') }}" required>
                            @error('waktu')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold" style="font-size:.85rem">Kategori <span class="text-danger">*</span></label>
                            <select name="kategori" class="form-select @error('kategori') is-invalid @enderror" required>
                                <option value="">-- Pilih --</option>
                                @foreach(\App\Models\AktivitasTenagaAhli::kategoriGrouped() as $group => $items)
                                    <optgroup label="{{ $group }}">
                                        @foreach($items as $val => $label)
                                            <option value="{{ $val }}" {{ old('kategori', $aktivitas->kategori ?? '') == $val ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                            @error('kategori')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-semibold" style="font-size:.85rem">Nama Kegiatan <span class="text-danger">*</span></label>
                            <input type="text" name="nama_kegiatan" class="form-control @error('nama_kegiatan') is-invalid @enderror" value="{{ old('nama_kegiatan', $aktivitas->nama_kegiatan ?? '') }}" required>
                            @error('nama_kegiatan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-semibold" style="font-size:.85rem">Lokasi <span class="text-danger">*</span></label>
                            <input type="text" name="lokasi" class="form-control @error('lokasi') is-invalid @enderror" value="{{ old('lokasi', $aktivitas->lokasi ?? '') }}" required>
                            @error('lokasi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-semibold" style="font-size:.85rem">Deskripsi Kegiatan</label>
                            <textarea name="deskripsi_kegiatan" class="form-control" rows="3">{{ old('deskripsi_kegiatan', $aktivitas->deskripsi_kegiatan ?? '') }}</textarea>
                        </div>
                        <div class="col-md-12">
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
                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-success px-4"><i class="bi bi-check-lg me-1"></i>Simpan</button>
                        <a href="{{ route('admin.aktivitas-staf-fraksi.index') }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
