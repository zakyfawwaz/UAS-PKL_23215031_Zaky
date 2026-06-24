<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Aktivitas;
use App\Models\AnggotaDewan;

use App\Models\AktivitasTenagaAhli;
use App\Models\AktivitasStafAdministrasi;
use App\Models\TenagaAhli;
use App\Models\StafAdministrasi;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Data Anggota Dewan
        $totalAnggota = AnggotaDewan::aktif()->count();
        $anggotaDewan = AnggotaDewan::aktif()->get();

        // 2. Data TA/SA Fraksi
        $tenagaAhliList = TenagaAhli::aktif()->orderBy('nama_lengkap')->get()->map(function ($item) {
            $item->jenis_staf = 'Tenaga Ahli';
            return $item;
        });
        $stafAdministrasiList = StafAdministrasi::aktif()->orderBy('nama_lengkap')->get()->map(function ($item) {
            $item->jenis_staf = 'Staf Administrasi';
            return $item;
        });
        $daftarStaf = $tenagaAhliList->concat($stafAdministrasiList);
        $totalStaf = $daftarStaf->count();

        // 3. Statistik Aktivitas Gabungan (opsional, tapi pakai yang lama saja untuk aktivitas dewan)
        $aktivitasHariIni = Aktivitas::hariIni()->count();
        $aktivitasBulanIni = Aktivitas::bulan(date('n'))->count();
        $aktivitasTahunIni = Aktivitas::tahun(date('Y'))->count();

        // 4. Aktivitas Terbaru Dewan
        $aktivitasTerbaruDewan = Aktivitas::with('anggotaDewan')
            ->latest('tanggal')->latest('waktu')->take(7)->get();

        // 5. Aktivitas Terbaru TA/SA
        $ta = AktivitasTenagaAhli::with('tenagaAhli')->latest('tanggal')->latest('waktu')->take(7)->get()->map(function ($item) {
            $item->pelaku = $item->tenagaAhli;
            return $item;
        });
        $sa = AktivitasStafAdministrasi::with('stafAdministrasi')->latest('tanggal')->latest('waktu')->take(7)->get()->map(function ($item) {
            $item->pelaku = $item->stafAdministrasi;
            return $item;
        });
        $aktivitasTerbaruStaf = $ta->concat($sa)->sortByDesc(function ($item) {
            return $item->tanggal->format('Y-m-d') . ' ' . $item->waktu;
        })->take(7);

        return view('admin.dashboard', [
            'totalAnggota'          => $totalAnggota,
            'anggotaDewan'          => $anggotaDewan,
            'totalStaf'             => $totalStaf,
            'daftarStaf'            => $daftarStaf,
            'aktivitasHariIni'      => $aktivitasHariIni,
            'aktivitasBulanIni'     => $aktivitasBulanIni,
            'aktivitasTahunIni'     => $aktivitasTahunIni,
            'aktivitasTerbaruDewan' => $aktivitasTerbaruDewan,
            'aktivitasTerbaruStaf'  => $aktivitasTerbaruStaf,
        ]);
    }
}
