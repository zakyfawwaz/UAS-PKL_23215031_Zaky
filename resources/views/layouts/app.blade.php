<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — Monitoring DPRD PKS Tegal</title>
    <meta name="description" content="Sistem Monitoring Aktivitas Anggota Dewan Fraksi PKS DPRD Kota Tegal">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --pks-primary: #fe5000;
            --pks-secondary: #e64800;
            --pks-accent: #ff6b2b;
            --pks-gold: #F9A825;
            --pks-dark: #4d1800;
            --bs-success: #fe5000;
            --bs-success-rgb: 254, 80, 0;
            --sidebar-width: 260px;
            --sidebar-collapsed: 0px;
        }
        .badge.bg-success { color: #000 !important; }
        .btn-success { background-color: var(--pks-primary) !important; border-color: var(--pks-primary) !important; color: #fff !important; }
        .btn-success:hover { background-color: var(--pks-secondary) !important; border-color: var(--pks-secondary) !important; }
        .btn-outline-success { color: var(--pks-primary) !important; border-color: var(--pks-primary) !important; }
        .btn-outline-success:hover { background-color: var(--pks-primary) !important; border-color: var(--pks-primary) !important; color: #fff !important; }
        * { font-family: 'Inter', sans-serif; }
        body { background: #f0f2f5; min-height: 100vh; }

        /* ── Sidebar ── */
        .sidebar {
            position: fixed; top: 0; left: 0; bottom: 0;
            width: var(--sidebar-width);
            background: linear-gradient(180deg, var(--pks-dark) 0%, var(--pks-primary) 100%);
            z-index: 1050; transition: transform .3s ease;
            overflow-y: auto; overflow-x: hidden;
        }
        .sidebar-brand {
            padding: 1.5rem 1.25rem; text-align: center;
            border-bottom: 1px solid rgba(255,255,255,.1);
        }
        .sidebar-brand h5 { color: #fff; font-weight: 700; margin: 0; font-size: .95rem; }
        .sidebar-brand small { color: var(--pks-accent); font-size: .7rem; letter-spacing: 1px; text-transform: uppercase; }
        .sidebar-menu { padding: .75rem 0; }
        .sidebar-heading {
            color: rgba(255,255,255,.4); font-size: .65rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: 1.5px;
            padding: 1rem 1.25rem .5rem;
        }
        .sidebar-link {
            display: flex; align-items: center; gap: .75rem;
            padding: .65rem 1.25rem; color: rgba(255,255,255,.7);
            text-decoration: none; font-size: .85rem; font-weight: 500;
            transition: all .2s; border-left: 3px solid transparent;
        }
        .sidebar-link:hover, .sidebar-link.active {
            color: #fff; background: rgba(255,255,255,.08);
            border-left-color: var(--pks-accent);
        }
        .sidebar-link.active { background: rgba(76,175,80,.15); }
        .sidebar-link i { font-size: 1.1rem; width: 20px; text-align: center; }

        /* ── Topbar ── */
        .main-content { margin-left: var(--sidebar-width); transition: margin .3s; }
        .topbar {
            background: #fff; border-bottom: 1px solid #e3e6ea;
            padding: .75rem 1.5rem; display: flex; align-items: center;
            justify-content: space-between; position: sticky; top: 0; z-index: 1040;
            box-shadow: 0 1px 3px rgba(0,0,0,.04);
        }
        .topbar .btn-toggle { display: none; }
        .content-wrapper { padding: 1.5rem; }

        /* ── Cards ── */
        .stat-card {
            border: none; border-radius: 12px;
            background: #fff; box-shadow: 0 1px 4px rgba(0,0,0,.06);
            transition: transform .2s, box-shadow .2s;
        }
        .stat-card:hover { transform: translateY(-2px); box-shadow: 0 4px 15px rgba(0,0,0,.1); }
        .stat-card .stat-icon {
            width: 48px; height: 48px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center; font-size: 1.3rem;
        }
        .stat-card .stat-value { font-size: 1.75rem; font-weight: 800; color: #1a1a2e; }
        .stat-card .stat-label { font-size: .78rem; color: #6c757d; font-weight: 500; }

        .card-clean {
            border: none; border-radius: 12px; background: #fff;
            box-shadow: 0 1px 4px rgba(0,0,0,.06);
        }
        .card-clean .card-header {
            background: transparent; border-bottom: 1px solid #f0f0f0;
            font-weight: 600; padding: 1rem 1.25rem;
        }

        /* ── Table ── */
        .table-clean th {
            background: #f8f9fb; font-size: .75rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: .5px; color: #6c757d;
            border-bottom-width: 1px;
        }
        .table-clean td { font-size: .85rem; vertical-align: middle; }

        /* ── Badge ── */
        .badge-kategori { font-size: .7rem; font-weight: 600; padding: .35em .65em; border-radius: 6px; }

        /* ── Responsive ── */
        .sidebar-overlay {
            display: none; position: fixed; inset: 0;
            background: rgba(0,0,0,.5); z-index: 1045;
        }
        @media (max-width: 991.98px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.show { transform: translateX(0); }
            .sidebar-overlay.show { display: block; }
            .main-content { margin-left: 0; }
            .topbar .btn-toggle { display: inline-flex; }
        }

        /* ── Animations ── */
        .fade-in { animation: fadeIn .4s ease; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }
    </style>
    @stack('styles')
</head>
<body>
    {{-- Sidebar Overlay (Mobile) --}}
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    {{-- Sidebar --}}
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <div class="mx-auto mb-3 d-flex justify-content-center align-items-center" style="width: 72px; height: 72px; background-color: #ffffff; border-radius: 50%;">
                <img src="{{ asset('img/logo-pks.svg') }}" alt="Logo PKS" style="height: 44px; width: auto;">
            </div>
            <h5>DPRD Kota Tegal</h5>
            <small>Fraksi PKS — Monitoring</small>
        </div>
        <nav class="sidebar-menu">
            @auth
                @if(auth()->user()->isAdmin())
                    {{-- ADMIN MENU --}}
                    <div class="sidebar-heading">Utama</div>
                    <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-grid-1x2-fill"></i> Dashboard
                    </a>

                    <div class="sidebar-heading">Data Master</div>
                    <a href="{{ route('admin.anggota-dewan.index') }}" class="sidebar-link {{ request()->routeIs('admin.anggota-dewan.*') ? 'active' : '' }}">
                        <i class="bi bi-people-fill"></i> Anggota Dewan
                    </a>
                    <a href="{{ route('admin.staf-fraksi.index') }}" class="sidebar-link {{ request()->routeIs('admin.staf-fraksi.*') ? 'active' : '' }}">
                        <i class="bi bi-person-badge-fill"></i> TA/SA Fraksi
                    </a>
                    <a href="{{ route('admin.aktivitas-dewan.index') }}" class="sidebar-link {{ request()->routeIs('admin.aktivitas-dewan.*') ? 'active' : '' }}">
                        <i class="bi bi-calendar-event-fill"></i> Aktivitas Dewan
                    </a>
                    <a href="{{ route('admin.aktivitas-staf-fraksi.index') }}" class="sidebar-link {{ request()->routeIs('admin.aktivitas-staf-fraksi.*') ? 'active' : '' }}">
                        <i class="bi bi-calendar-event-fill"></i> Aktivitas TA/SA Fraksi
                    </a>

                    <div class="sidebar-heading">Rekapitulasi</div>
                    <a href="{{ route('admin.rekap.bulanan') }}" class="sidebar-link {{ request()->routeIs('admin.rekap.bulanan') ? 'active' : '' }}">
                        <i class="bi bi-calendar3"></i> Bulanan
                    </a>
                    <a href="{{ route('admin.rekap.triwulan') }}" class="sidebar-link {{ request()->routeIs('admin.rekap.triwulan') ? 'active' : '' }}">
                        <i class="bi bi-calendar3-range"></i> Triwulan
                    </a>
                    <a href="{{ route('admin.rekap.semester') }}" class="sidebar-link {{ request()->routeIs('admin.rekap.semester') ? 'active' : '' }}">
                        <i class="bi bi-calendar3-week"></i> Semester
                    </a>
                    <a href="{{ route('admin.rekap.tahunan') }}" class="sidebar-link {{ request()->routeIs('admin.rekap.tahunan') ? 'active' : '' }}">
                        <i class="bi bi-calendar-date"></i> Tahunan
                    </a>

                    <div class="sidebar-heading">Lainnya</div>
                    <a href="{{ route('admin.laporan.index') }}" class="sidebar-link {{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}">
                        <i class="bi bi-printer-fill"></i> Laporan
                    </a>
                    <a href="{{ route('admin.pengguna.index') }}" class="sidebar-link {{ request()->routeIs('admin.pengguna.*') ? 'active' : '' }}">
                        <i class="bi bi-person-gear"></i> Pengguna
                    </a>
                @else
                    {{-- ANGGOTA DEWAN MENU --}}
                    <div class="sidebar-heading">Utama</div>
                    <a href="{{ route('anggota.dashboard') }}" class="sidebar-link {{ request()->routeIs('anggota.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-grid-1x2-fill"></i> Dashboard
                    </a>
                    <a href="{{ route('anggota.aktivitas.index') }}" class="sidebar-link {{ request()->routeIs('anggota.aktivitas.*') ? 'active' : '' }}">
                        <i class="bi bi-calendar-event-fill"></i> Aktivitas Saya
                    </a>

                    <div class="sidebar-heading">Rekapitulasi</div>
                    <a href="{{ route('anggota.rekap.bulanan') }}" class="sidebar-link {{ request()->routeIs('anggota.rekap.*') ? 'active' : '' }}">
                        <i class="bi bi-bar-chart-fill"></i> Rekap Saya
                    </a>
                @endif

                <div class="sidebar-heading">Akun</div>
                <a href="{{ route('profil.index') }}" class="sidebar-link {{ request()->routeIs('profil.*') ? 'active' : '' }}">
                    <i class="bi bi-person-circle"></i> Profil
                </a>
            @endauth
        </nav>
    </aside>

    {{-- Main Content --}}
    <div class="main-content">
        <header class="topbar">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-sm btn-outline-secondary btn-toggle" onclick="toggleSidebar()">
                    <i class="bi bi-list"></i>
                </button>
                <h6 class="mb-0 fw-600" style="font-size:.9rem; color:#333;">@yield('page-title', 'Dashboard')</h6>
            </div>
            <div class="d-flex align-items-center gap-3">
                @auth
                    <span class="badge bg-success bg-opacity-10 text-success px-3 py-2" style="font-size:.75rem">
                        <i class="bi bi-shield-check me-1"></i>{{ ucfirst(auth()->user()->role) }}
                    </span>
                    <div class="dropdown">
                        <a href="#" class="d-flex align-items-center gap-2 text-decoration-none text-dark dropdown-toggle" data-bs-toggle="dropdown">
                            <div class="rounded-circle bg-success bg-opacity-10 d-flex align-items-center justify-content-center" style="width:34px;height:34px">
                                <i class="bi bi-person-fill text-success"></i>
                            </div>
                            <span style="font-size:.85rem; font-weight:500">{{ auth()->user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                            <li><a class="dropdown-item" href="{{ route('profil.index') }}"><i class="bi bi-person me-2"></i>Profil</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i>Logout</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @endauth
            </div>
        </header>

        <div class="content-wrapper fade-in">
            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2" role="alert" style="border-radius:10px; border:none; background:rgba(76,175,80,.1); color:#2e7d32;">
                    <i class="bi bi-check-circle-fill"></i>
                    <div>{{ session('success') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2" role="alert" style="border-radius:10px; border:none;">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <div>{{ session('error') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('show');
            document.getElementById('sidebarOverlay').classList.toggle('show');
        }
    </script>
    @stack('scripts')
</body>
</html>
