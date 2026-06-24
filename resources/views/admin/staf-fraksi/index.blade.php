@extends('layouts.app')
@section('title', 'Data Staf Fraksi')
@section('page-title', 'Data Staf Fraksi')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <p class="text-muted mb-0" style="font-size:.85rem">Kelola data Tenaga Ahli dan Staf Administrasi Fraksi PKS</p>
    </div>
    <a href="{{ route('admin.staf-fraksi.create') }}" class="btn btn-success btn-sm px-3" style="border-radius:8px">
        <i class="bi bi-plus-lg me-1"></i> Tambah Staf Fraksi
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
                        <th>Jenis Staf</th>
                        <th class="text-center">Status</th>
                        <th class="text-center" style="width:120px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stafFraksi as $i => $s)
                        <tr>
                            <td>{{ $stafFraksi->firstItem() + $i }}</td>
                            <td class="fw-semibold">{{ $s->nama_lengkap }}</td>
                            <td>{{ $s->jabatan }}</td>
                            <td>
                                @if($s->jenis_staf === 'tenaga_ahli')
                                    <span class="badge bg-primary bg-opacity-10 text-primary px-2 py-1" style="font-size:.75rem">
                                        Tenaga Ahli
                                    </span>
                                @else
                                    <span class="badge bg-info bg-opacity-10 text-info px-2 py-1" style="font-size:.75rem">
                                        Staf Administrasi
                                    </span>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="badge {{ $s->status ? 'bg-success' : 'bg-secondary' }} bg-opacity-10 {{ $s->status ? 'text-success' : 'text-secondary' }}">
                                    {{ $s->status ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="d-flex gap-1 justify-content-center">
                                    <a href="{{ route('admin.staf-fraksi.edit', ['type' => $s->jenis_staf, 'id' => $s->id]) }}" class="btn btn-outline-warning btn-sm" title="Edit"><i class="bi bi-pencil"></i></a>
                                    <form action="{{ route('admin.staf-fraksi.destroy', ['type' => $s->jenis_staf, 'id' => $s->id]) }}" method="POST" class="m-0 p-0" onsubmit="return confirm('Hapus staf fraksi ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm" title="Hapus"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center py-4 text-muted">Belum ada data staf fraksi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($stafFraksi->hasPages())
        <div class="card-footer bg-transparent p-3">{{ $stafFraksi->links() }}</div>
    @endif
</div>
@endsection
