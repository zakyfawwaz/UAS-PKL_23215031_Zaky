@extends('layouts.app')
@section('title', isset($tenagaAhli) ? 'Edit Tenaga Ahli' : 'Tambah Tenaga Ahli')
@section('page-title', isset($tenagaAhli) ? 'Edit Tenaga Ahli' : 'Tambah Tenaga Ahli')
@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card-clean card">
            <div class="card-header"><i class="bi bi-person-plus me-2 text-success"></i>{{ isset($tenagaAhli) ? 'Edit Data' : 'Data Baru' }}</div>
            <div class="card-body p-4">
                <form method="POST" action="{{ isset($tenagaAhli) ? route('admin.tenaga-ahli.update', $tenagaAhli) : route('admin.tenaga-ahli.store') }}">
                    @csrf
                    @if(isset($tenagaAhli)) @method('PUT') @endif

                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label fw-semibold" style="font-size:.85rem">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="nama_lengkap" class="form-control @error('nama_lengkap') is-invalid @enderror" value="{{ old('nama_lengkap', $tenagaAhli->nama_lengkap ?? '') }}" required>
                            @error('nama_lengkap')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="font-size:.85rem">Jabatan <span class="text-danger">*</span></label>
                            <input type="text" name="jabatan" class="form-control @error('jabatan') is-invalid @enderror" value="{{ old('jabatan', $tenagaAhli->jabatan ?? '') }}" required>
                            @error('jabatan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="font-size:.85rem">Status</label>
                            <select name="status" class="form-select">
                                <option value="1" {{ old('status', $tenagaAhli->status ?? 1) == 1 ? 'selected' : '' }}>Aktif</option>
                                <option value="0" {{ old('status', $tenagaAhli->status ?? 1) == 0 ? 'selected' : '' }}>Nonaktif</option>
                            </select>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-success px-4"><i class="bi bi-check-lg me-1"></i>Simpan</button>
                        <a href="{{ route('admin.tenaga-ahli.index') }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
