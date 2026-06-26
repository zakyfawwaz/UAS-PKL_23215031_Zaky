<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Aktivitas;
use App\Models\AnggotaDewan;
use Illuminate\Http\Request;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\AktivitasTenagaAhli;
use App\Models\AktivitasStafAdministrasi;
use App\Models\TenagaAhli;
use App\Models\StafAdministrasi;

class LaporanController extends Controller
{
    public function index()
    {
        $tenagaAhliList = TenagaAhli::aktif()->orderBy('nama_lengkap')->get()->map(function ($item) {
            $item->key = 'tenaga_ahli-' . $item->id;
            $item->label = $item->nama_lengkap . ' (Tenaga Ahli)';
            return $item;
        });
        $stafAdministrasiList = StafAdministrasi::aktif()->orderBy('nama_lengkap')->get()->map(function ($item) {
            $item->key = 'staf_administrasi-' . $item->id;
            $item->label = $item->nama_lengkap . ' (Staf Administrasi)';
            return $item;
        });

        return view('admin.laporan.index', [
            'daftarAnggota' => AnggotaDewan::aktif()->orderBy('nama_lengkap')->get(),
            'daftarStaf'    => $tenagaAhliList->concat($stafAdministrasiList),
        ]);
    }

    private function getLaporanData(Request $request)
    {
        $jenis  = $request->get('jenis_laporan', 'dewan');
        $dari   = $request->get('dari', now()->startOfMonth()->format('Y-m-d'));
        $sampai = $request->get('sampai', now()->format('Y-m-d'));
        $person = $request->get('anggota');

        $aktivitas = collect();
        $title = '';
        $namaTampil = 'Semua';

        if ($jenis === 'dewan') {
            $title = 'LAPORAN AKTIVITAS ANGGOTA DEWAN';
            $query = Aktivitas::with('anggotaDewan')->rentangTanggal($dari, $sampai)->orderBy('tanggal');
            if ($person) {
                $query->milikAnggota($person);
                $anggota = AnggotaDewan::find($person);
                $namaTampil = $anggota ? $anggota->nama_lengkap : 'Semua Anggota';
            } else {
                $namaTampil = 'Semua Anggota';
            }
            $aktivitas = $query->get();
        } else {
            $title = 'LAPORAN AKTIVITAS TA/SA FRAKSI';
            $queryTA = AktivitasTenagaAhli::with('tenagaAhli')->whereBetween('tanggal', [$dari, $sampai])->orderBy('tanggal');
            $querySA = AktivitasStafAdministrasi::with('stafAdministrasi')->whereBetween('tanggal', [$dari, $sampai])->orderBy('tanggal');

            if ($person) {
                $parts = explode('-', $person);
                if (count($parts) === 2) {
                    $type = $parts[0];
                    $id = $parts[1];
                    if ($type === 'tenaga_ahli') {
                        $queryTA->where('tenaga_ahli_id', $id);
                        $querySA->whereRaw('1 = 0');
                        $taData = TenagaAhli::find($id);
                        $namaTampil = $taData ? $taData->nama_lengkap : 'Semua TA/SA';
                    } elseif ($type === 'staf_administrasi') {
                        $querySA->where('staf_administrasi_id', $id);
                        $queryTA->whereRaw('1 = 0');
                        $saData = StafAdministrasi::find($id);
                        $namaTampil = $saData ? $saData->nama_lengkap : 'Semua TA/SA';
                    }
                }
            } else {
                $namaTampil = 'Semua TA/SA Fraksi';
            }

            $ta = $queryTA->get();
            $sa = $querySA->get();

            $aktivitas = $ta->concat($sa)->sortBy(function ($item) {
                return $item->tanggal->format('Y-m-d') . ' ' . $item->waktu;
            })->values();
        }

        return compact('aktivitas', 'dari', 'sampai', 'jenis', 'title', 'namaTampil');
    }

    public function cetak(Request $request)
    {
        $data = $this->getLaporanData($request);
        $data['pdf'] = false;
        return view('admin.laporan.cetak', $data);
    }

    public function exportPdf(Request $request)
    {
        $data = $this->getLaporanData($request);
        $data['pdf'] = true;
        
        $pdf = Pdf::loadView('admin.laporan.cetak', $data);
        
        $prefix = $data['jenis'] === 'dewan' ? 'laporan-anggota-dewan' : 'laporan-tasa-fraksi';
        $filename = $prefix . '-' . now()->format('Y-m-d') . '.pdf';
        
        return $pdf->download($filename);
    }

    public function exportExcel()
    {
        return redirect()->route('admin.laporan.index')->with('error', 'Fitur export Excel akan segera tersedia.');
    }
}
