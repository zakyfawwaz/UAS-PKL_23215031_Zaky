@extends('layouts.app')
@section('title', 'Data Staf Administrasi')
@section('page-title', 'Data Staf Administrasi')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <p class="text-muted mb-0" style="font-size:.85rem">Kelola data staf administrasi Fraksi PKS</p>
    </div>
    <a href="{{ route('admin.staf-administrasi.create') }}" class="btn btn-success btn-sm px-3" style="border-radius:8px">
        <i class="bi bi-plus-lg me-1"></i> Tambah Staf
    </a>
</div>
<div class="card-clean card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-clean table-hover mb-0">
                <thead>
                    <tr>
                        <th style="width:40px">#</th>
                        <th>Nama Lengkap</th>
                        <th>Jabatan</th>
                        <th class="text-center">Status</th>
                        <th class="text-center" style="width:120px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($staf as $i => $s)
                        <tr>
                            <td>{{ $staf->firstItem() + $i }}</td>
                            <td class="fw-semibold">{{ $s->nama_lengkap }}</td>
                            <td>{{ $s->jabatan }}</td>
                            <td class="text-center">
                                <span class="badge {{ $s->status ? 'bg-success' : 'bg-secondary' }} bg-opacity-10 {{ $s->status ? 'text-success' : 'text-secondary' }}">
                                    {{ $s->status ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="d-flex gap-1 justify-content-center">
                                    <a href="{{ route('admin.staf-administrasi.edit', $s) }}" class="btn btn-outline-warning btn-sm" title="Edit"><i class="bi bi-pencil"></i></a>
                                    <form action="{{ route('admin.staf-administrasi.destroy', $s) }}" method="POST" class="m-0 p-0" onsubmit="return confirm('Hapus staf administrasi ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm" title="Hapus"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center py-4 text-muted">Belum ada data staf administrasi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($staf->hasPages())
        <div class="card-footer bg-transparent p-3">{{ $staf->links() }}</div>
    @endif
</div>
@endsection
