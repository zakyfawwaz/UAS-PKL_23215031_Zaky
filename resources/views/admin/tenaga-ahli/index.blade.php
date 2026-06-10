@extends('layouts.app')
@section('title', 'Data Tenaga Ahli')
@section('page-title', 'Data Tenaga Ahli')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <p class="text-muted mb-0" style="font-size:.85rem">Kelola data tenaga ahli Fraksi PKS</p>
    </div>
    <a href="{{ route('admin.tenaga-ahli.create') }}" class="btn btn-success btn-sm px-3" style="border-radius:8px">
        <i class="bi bi-plus-lg me-1"></i> Tambah Tenaga Ahli
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
                    @forelse($tenagaAhli as $i => $t)
                        <tr>
                            <td>{{ $tenagaAhli->firstItem() + $i }}</td>
                            <td class="fw-semibold">{{ $t->nama_lengkap }}</td>
                            <td>{{ $t->jabatan }}</td>
                            <td class="text-center">
                                <span class="badge {{ $t->status ? 'bg-success' : 'bg-secondary' }} bg-opacity-10 {{ $t->status ? 'text-success' : 'text-secondary' }}">
                                    {{ $t->status ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="d-flex gap-1 justify-content-center">
                                    <a href="{{ route('admin.tenaga-ahli.edit', $t) }}" class="btn btn-outline-warning btn-sm" title="Edit"><i class="bi bi-pencil"></i></a>
                                    <form action="{{ route('admin.tenaga-ahli.destroy', $t) }}" method="POST" class="m-0 p-0" onsubmit="return confirm('Hapus tenaga ahli ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm" title="Hapus"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center py-4 text-muted">Belum ada data tenaga ahli.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($tenagaAhli->hasPages())
        <div class="card-footer bg-transparent p-3">{{ $tenagaAhli->links() }}</div>
    @endif
</div>
@endsection
