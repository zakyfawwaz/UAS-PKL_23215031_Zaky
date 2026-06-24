@extends('layouts.app')
@section('title', isset($staf) ? 'Edit Staf Fraksi' : 'Tambah Staf Fraksi')
@section('page-title', isset($staf) ? 'Edit Staf Fraksi' : 'Tambah Staf Fraksi')
@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card-clean card">
            <div class="card-header"><i class="bi bi-person-plus me-2 text-success"></i>{{ isset($staf) ? 'Edit Data' : 'Data Baru' }}</div>
            <div class="card-body p-4">
                <form method="POST" action="{{ isset($staf) ? route('admin.staf-fraksi.update', ['type' => $staf->jenis_staf, 'id' => $staf->id]) : route('admin.staf-fraksi.store') }}">
                    @csrf
                    @if(isset($staf)) @method('PUT') @endif

                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label fw-semibold" style="font-size:.85rem">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="nama_lengkap" class="form-control @error('nama_lengkap') is-invalid @enderror" value="{{ old('nama_lengkap', $staf->nama_lengkap ?? '') }}" required>
                            @error('nama_lengkap')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="font-size:.85rem">Jabatan <span class="text-danger">*</span></label>
                            <input type="text" name="jabatan" class="form-control @error('jabatan') is-invalid @enderror" value="{{ old('jabatan', $staf->jabatan ?? '') }}" required>
                            @error('jabatan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="font-size:.85rem">Jenis Staf <span class="text-danger">*</span></label>
                            <select name="jenis_staf" class="form-select @error('jenis_staf') is-invalid @enderror" required>
                                <option value="" disabled {{ !isset($staf) ? 'selected' : '' }}>Pilih Jenis Staf</option>
                                <option value="tenaga_ahli" {{ old('jenis_staf', $staf->jenis_staf ?? '') == 'tenaga_ahli' ? 'selected' : '' }}>Tenaga Ahli</option>
                                <option value="staf_administrasi" {{ old('jenis_staf', $staf->jenis_staf ?? '') == 'staf_administrasi' ? 'selected' : '' }}>Staf Administrasi</option>
                            </select>
                            @error('jenis_staf')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="font-size:.85rem">Status</label>
                            <select name="status" class="form-select">
                                <option value="1" {{ old('status', $staf->status ?? 1) == 1 ? 'selected' : '' }}>Aktif</option>
                                <option value="0" {{ old('status', $staf->status ?? 1) == 0 ? 'selected' : '' }}>Nonaktif</option>
                            </select>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-success px-4"><i class="bi bi-check-lg me-1"></i>Simpan</button>
                        <a href="{{ route('admin.staf-fraksi.index') }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
