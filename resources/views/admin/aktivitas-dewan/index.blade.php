@extends('layouts.app')
@section('title', 'Aktivitas Dewan')
@section('page-title', 'Aktivitas Dewan')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <p class="text-muted mb-0" style="font-size:.85rem">Kelola aktivitas harian seluruh anggota dewan</p>
    <a href="{{ route('admin.aktivitas-dewan.create') }}" class="btn btn-success btn-sm px-3" style="border-radius:8px"><i class="bi bi-plus-lg me-1"></i> Tambah Aktivitas</a>
</div>

{{-- Filter --}}
<div class="card-clean card mb-3">
    <div class="card-body py-2 px-3">
        <form method="GET" class="row g-2 align-items-center">
            <div class="col-auto">
                <input type="date" name="tanggal" class="form-control form-control-sm" value="{{ request('tanggal') }}">
            </div>
            <div class="col-auto">
                <select name="kategori" class="form-select form-select-sm">
                    <option value="">Semua Kategori</option>
                    @foreach(\App\Models\Aktivitas::semuaKategori() as $v => $l)
                        <option value="{{ $v }}" {{ request('kategori') == $v ? 'selected' : '' }}>{{ $l }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-auto">
                <select name="anggota" class="form-select form-select-sm">
                    <option value="">Semua Anggota</option>
                    @foreach($daftarAnggota as $a)
                        <option value="{{ $a->id }}" {{ request('anggota') == $a->id ? 'selected' : '' }}>{{ $a->nama_lengkap }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-auto"><button class="btn btn-success btn-sm"><i class="bi bi-search"></i></button></div>
            <div class="col-auto"><a href="{{ route('admin.aktivitas-dewan.index') }}" class="btn btn-outline-secondary btn-sm">Reset</a></div>
        </form>
    </div>
</div>

<div class="card-clean card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-clean table-hover mb-0">
                <thead>
                    <tr><th style="width:40px">#</th><th>Tanggal</th><th>Kegiatan</th><th>Anggota</th><th>Kategori</th><th>Lokasi</th><th class="text-center" style="width:100px">Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse($aktivitas as $i => $akt)
                        <tr>
                            <td>{{ $aktivitas->firstItem() + $i }}</td>
                            <td style="white-space:nowrap">{{ $akt->tanggal->format('d/m/Y') }}<br><small class="text-muted">{{ $akt->waktu }}</small></td>
                            <td class="fw-medium">{{ Str::limit($akt->nama_kegiatan, 40) }}</td>
                            <td>{{ $akt->anggotaDewan->nama_lengkap }}</td>
                            <td><span class="badge bg-success bg-opacity-10 text-success badge-kategori">{{ $akt->label_kategori }}</span></td>
                            <td>{{ Str::limit($akt->lokasi, 25) }}</td>
                            <td class="text-center">
                                <div class="d-flex gap-1 justify-content-center">
                                    <a href="{{ route('admin.aktivitas-dewan.edit', $akt) }}" class="btn btn-outline-warning btn-sm"><i class="bi bi-pencil"></i></a>
                                    <form action="{{ route('admin.aktivitas-dewan.destroy', $akt) }}" method="POST" class="m-0 p-0" onsubmit="return confirm('Hapus aktivitas ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center py-4 text-muted">Belum ada aktivitas.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($aktivitas->hasPages())
        <div class="card-footer bg-transparent p-3">{{ $aktivitas->links() }}</div>
    @endif
</div>
@endsection
