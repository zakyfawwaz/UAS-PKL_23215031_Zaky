@extends('layouts.app')
@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard')
@section('content')
{{-- Stat Cards --}}
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl">
        <div class="stat-card card p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon bg-success bg-opacity-10 text-success"><i class="bi bi-people-fill"></i></div>
                <div><div class="stat-value">{{ $totalAnggota }}</div><div class="stat-label">Anggota Dewan</div></div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl">
        <div class="stat-card card p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon bg-success bg-opacity-10 text-success"><i class="bi bi-person-badge-fill"></i></div>
                <div><div class="stat-value">{{ $totalStaf }}</div><div class="stat-label">TA/SA Fraksi</div></div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl">
        <div class="stat-card card p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon bg-primary bg-opacity-10 text-primary"><i class="bi bi-calendar-check-fill"></i></div>
                <div><div class="stat-value">{{ $aktivitasHariIni }}</div><div class="stat-label">Aktivitas Hari Ini</div></div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl">
        <div class="stat-card card p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon bg-warning bg-opacity-10 text-warning"><i class="bi bi-calendar3"></i></div>
                <div><div class="stat-value">{{ $aktivitasBulanIni }}</div><div class="stat-label">Bulan Ini</div></div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl">
        <div class="stat-card card p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon bg-info bg-opacity-10 text-info"><i class="bi bi-graph-up"></i></div>
                <div><div class="stat-value">{{ $aktivitasTahunIni }}</div><div class="stat-label">Tahun {{ date('Y') }}</div></div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    {{-- Aktivitas Terbaru --}}
    <div class="col-lg-8">
        <div class="card-clean card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="d-flex align-items-center"><i class="bi bi-clock-history text-success me-2"></i>Aktivitas Terbaru</span>
                <ul class="nav nav-pills card-header-pills" id="aktivitasTab" role="tablist" style="font-size: .8rem">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active px-2 py-1" id="dewan-tab" data-bs-toggle="tab" data-bs-target="#dewan" type="button" role="tab">Dewan</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link px-2 py-1" id="staf-tab" data-bs-toggle="tab" data-bs-target="#staf" type="button" role="tab">TA/SA</button>
                    </li>
                </ul>
            </div>
            <div class="card-body p-0">
                <div class="tab-content" id="aktivitasTabContent">
                    {{-- Tab Dewan --}}
                    <div class="tab-pane fade show active" id="dewan" role="tabpanel" aria-labelledby="dewan-tab">
                        @forelse($aktivitasTerbaruDewan as $akt)
                            <div class="d-flex align-items-center gap-3 p-3 border-bottom">
                                <div class="rounded-circle bg-success bg-opacity-10 d-flex align-items-center justify-content-center flex-shrink-0" style="width:38px;height:38px">
                                    <i class="bi bi-calendar-event text-success" style="font-size:.85rem"></i>
                                </div>
                                <div class="flex-grow-1" style="min-width:0; overflow:hidden;">
                                    <div class="fw-semibold text-truncate" style="font-size:.85rem;" title="{{ $akt->nama_kegiatan }}">{{ $akt->nama_kegiatan }}</div>
                                    <div class="text-muted text-truncate" style="font-size:.75rem">{{ $akt->anggotaDewan->nama_lengkap }} · {{ $akt->tanggal->format('d M Y') }}</div>
                                </div>
                                <span class="badge bg-success bg-opacity-10 text-success badge-kategori">{{ $akt->label_kategori }}</span>
                            </div>
                        @empty
                            <div class="p-4 text-center text-muted"><i class="bi bi-inbox" style="font-size:1.5rem"></i><p class="mt-2 mb-0">Belum ada aktivitas.</p></div>
                        @endforelse
                    </div>

                    {{-- Tab TA/SA --}}
                    <div class="tab-pane fade" id="staf" role="tabpanel" aria-labelledby="staf-tab">
                        @forelse($aktivitasTerbaruStaf as $akt)
                            <div class="d-flex align-items-center gap-3 p-3 border-bottom">
                                <div class="rounded-circle bg-success bg-opacity-10 d-flex align-items-center justify-content-center flex-shrink-0" style="width:38px;height:38px">
                                    <i class="bi bi-calendar-event text-success" style="font-size:.85rem"></i>
                                </div>
                                <div class="flex-grow-1" style="min-width:0; overflow:hidden;">
                                    <div class="fw-semibold text-truncate" style="font-size:.85rem;" title="{{ $akt->nama_kegiatan }}">{{ $akt->nama_kegiatan }}</div>
                                    <div class="text-muted text-truncate" style="font-size:.75rem">{{ $akt->pelaku->nama_lengkap }} · {{ $akt->tanggal->format('d M Y') }}</div>
                                </div>
                                <span class="badge bg-success bg-opacity-10 text-success badge-kategori">{{ $akt->label_kategori }}</span>
                            </div>
                        @empty
                            <div class="p-4 text-center text-muted"><i class="bi bi-inbox" style="font-size:1.5rem"></i><p class="mt-2 mb-0">Belum ada aktivitas.</p></div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Anggota Dewan --}}
    <div class="col-lg-4">
        <div class="card-clean card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-people text-success me-2"></i>Anggota Dewan</span>
                <a href="{{ route('admin.anggota-dewan.index') }}" class="btn btn-sm btn-outline-success" style="font-size:.78rem">Kelola</a>
            </div>
            <div class="card-body p-0">
                @forelse($anggotaDewan as $anggota)
                    <div class="d-flex align-items-center gap-3 p-3 border-bottom">
                        <div class="rounded-circle bg-success bg-opacity-10 d-flex align-items-center justify-content-center flex-shrink-0" style="width:36px;height:36px">
                            <i class="bi bi-person-fill text-success" style="font-size:.85rem"></i>
                        </div>
                        <div>
                            <div class="fw-semibold" style="font-size:.85rem">{{ $anggota->nama_lengkap }}</div>
                            <div class="text-muted" style="font-size:.73rem">{{ $anggota->jabatan }} · {{ $anggota->komisi }}</div>
                        </div>
                    </div>
                @empty
                    <div class="p-4 text-center text-muted">Belum ada anggota.</div>
                @endforelse
            </div>
        </div>

        {{-- TA/SA Fraksi --}}
        <div class="card-clean card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-person-badge text-success me-2"></i>TA/SA Fraksi</span>
                <a href="{{ route('admin.staf-fraksi.index') }}" class="btn btn-sm btn-outline-success" style="font-size:.78rem">Kelola</a>
            </div>
            <div class="card-body p-0">
                @forelse($daftarStaf as $staf)
                    <div class="d-flex align-items-center gap-3 p-3 border-bottom">
                        <div class="rounded-circle bg-success bg-opacity-10 d-flex align-items-center justify-content-center flex-shrink-0" style="width:36px;height:36px">
                            <i class="bi bi-person-fill text-success" style="font-size:.85rem"></i>
                        </div>
                        <div>
                            <div class="fw-semibold" style="font-size:.85rem">{{ $staf->nama_lengkap }}</div>
                            <div class="text-muted" style="font-size:.73rem">{{ $staf->jenis_staf }}</div>
                        </div>
                    </div>
                @empty
                    <div class="p-4 text-center text-muted">Belum ada TA/SA.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
