<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AktivitasTenagaAhli;
use App\Models\AktivitasStafAdministrasi;
use App\Models\TenagaAhli;
use App\Models\StafAdministrasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\LengthAwarePaginator;

class AktivitasStafFraksiController extends Controller
{
    public function index(Request $request)
    {
        $queryTA = AktivitasTenagaAhli::with('tenagaAhli')->latest('tanggal')->latest('waktu');
        $querySA = AktivitasStafAdministrasi::with('stafAdministrasi')->latest('tanggal')->latest('waktu');

        if ($request->filled('tanggal')) {
            $queryTA->whereDate('tanggal', $request->tanggal);
            $querySA->whereDate('tanggal', $request->tanggal);
        }
        if ($request->filled('kategori')) {
            $queryTA->kategori($request->kategori);
            $querySA->kategori($request->kategori);
        }
        if ($request->filled('staf')) {
            $parts = explode('-', $request->staf);
            if (count($parts) === 2) {
                $type = $parts[0];
                $id = $parts[1];
                if ($type === 'tenaga_ahli') {
                    $queryTA->where('tenaga_ahli_id', $id);
                    $querySA->whereRaw('1 = 0');
                } elseif ($type === 'staf_administrasi') {
                    $querySA->where('staf_administrasi_id', $id);
                    $queryTA->whereRaw('1 = 0');
                }
            }
        }

        $ta = $queryTA->get()->map(function ($item) {
            $item->pelaku_type = 'tenaga_ahli';
            $item->pelaku = $item->tenagaAhli;
            return $item;
        });

        $sa = $querySA->get()->map(function ($item) {
            $item->pelaku_type = 'staf_administrasi';
            $item->pelaku = $item->stafAdministrasi;
            return $item;
        });

        $merged = $ta->concat($sa)->sortByDesc(function ($item) {
            return $item->tanggal->format('Y-m-d') . ' ' . $item->waktu;
        });

        $perPage = 15;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentItems = $merged->slice(($currentPage - 1) * $perPage, $perPage)->values();

        $paginated = new LengthAwarePaginator(
            $currentItems,
            $merged->count(),
            $perPage,
            $currentPage,
            ['path' => LengthAwarePaginator::resolveCurrentPath()]
        );

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
        $daftarStaf = $tenagaAhliList->concat($stafAdministrasiList);

        return view('admin.aktivitas-staf-fraksi.index', [
            'aktivitas' => $paginated,
            'daftarStaf' => $daftarStaf,
        ]);
    }

    public function create()
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
        $daftarStaf = $tenagaAhliList->concat($stafAdministrasiList);

        return view('admin.aktivitas-staf-fraksi.form', [
            'daftarStaf' => $daftarStaf,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'staf_id'            => 'required|string',
            'tanggal'            => 'required|date',
            'waktu'              => 'required',
            'nama_kegiatan'      => 'required|string|max:255',
            'kategori'           => 'required|in:' . implode(',', array_keys(AktivitasTenagaAhli::semuaKategori())),
            'lokasi'             => 'required|string|max:255',
            'deskripsi_kegiatan' => 'nullable|string',
            'dokumentasi_foto'   => 'nullable|file|mimes:jpg,jpeg,png|max:5120',
        ]);

        $parts = explode('-', $data['staf_id']);
        if (count($parts) !== 2) {
            return back()->withErrors(['staf_id' => 'Staf tidak valid.'])->withInput();
        }

        $type = $parts[0];
        $id = $parts[1];

        $insertData = [
            'tanggal'            => $data['tanggal'],
            'waktu'              => $data['waktu'],
            'nama_kegiatan'      => $data['nama_kegiatan'],
            'kategori'           => $data['kategori'],
            'lokasi'             => $data['lokasi'],
            'deskripsi_kegiatan' => $data['deskripsi_kegiatan'],
            'status'             => true,
            'dibuat_oleh'        => auth()->id(),
        ];

        if ($request->hasFile('dokumentasi_foto')) {
            $folder = $type === 'tenaga_ahli' ? 'aktivitas_ta' : 'aktivitas_staf';
            $insertData['dokumentasi_foto'] = $request->file('dokumentasi_foto')->store($folder, 'public');
        }

        if ($type === 'tenaga_ahli') {
            $insertData['tenaga_ahli_id'] = $id;
            AktivitasTenagaAhli::create($insertData);
        } else {
            $insertData['staf_administrasi_id'] = $id;
            AktivitasStafAdministrasi::create($insertData);
        }

        return redirect()->route('admin.aktivitas-staf-fraksi.index')->with('success', 'Aktivitas Staf Fraksi berhasil ditambahkan.');
    }

    public function show($type, $id)
    {
        return redirect()->route('admin.aktivitas-staf-fraksi.edit', ['type' => $type, 'id' => $id]);
    }

    public function edit($type, $id)
    {
        if ($type === 'tenaga_ahli') {
            $aktivitas = AktivitasTenagaAhli::findOrFail($id);
            $aktivitas->staf_id = 'tenaga_ahli-' . $aktivitas->tenaga_ahli_id;
        } elseif ($type === 'staf_administrasi') {
            $aktivitas = AktivitasStafAdministrasi::findOrFail($id);
            $aktivitas->staf_id = 'staf_administrasi-' . $aktivitas->staf_administrasi_id;
        } else {
            abort(404);
        }

        $aktivitas->pelaku_type = $type;

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
        $daftarStaf = $tenagaAhliList->concat($stafAdministrasiList);

        return view('admin.aktivitas-staf-fraksi.form', [
            'aktivitas'  => $aktivitas,
            'daftarStaf' => $daftarStaf,
        ]);
    }

    public function update(Request $request, $type, $id)
    {
        if ($type === 'tenaga_ahli') {
            $aktivitas = AktivitasTenagaAhli::findOrFail($id);
        } elseif ($type === 'staf_administrasi') {
            $aktivitas = AktivitasStafAdministrasi::findOrFail($id);
        } else {
            abort(404);
        }

        $data = $request->validate([
            'staf_id'            => 'required|string',
            'tanggal'            => 'required|date',
            'waktu'              => 'required',
            'nama_kegiatan'      => 'required|string|max:255',
            'kategori'           => 'required|in:' . implode(',', array_keys(AktivitasTenagaAhli::semuaKategori())),
            'lokasi'             => 'required|string|max:255',
            'deskripsi_kegiatan' => 'nullable|string',
            'dokumentasi_foto'   => 'nullable|file|mimes:jpg,jpeg,png|max:5120',
        ]);

        $parts = explode('-', $data['staf_id']);
        if (count($parts) !== 2) {
            return back()->withErrors(['staf_id' => 'Staf tidak valid.'])->withInput();
        }

        $newType = $parts[0];
        $newStafId = $parts[1];

        $photoPath = $aktivitas->dokumentasi_foto;
        if ($request->hasFile('dokumentasi_foto')) {
            if ($photoPath && Storage::disk('public')->exists($photoPath)) {
                Storage::disk('public')->delete($photoPath);
            }
            $folder = $newType === 'tenaga_ahli' ? 'aktivitas_ta' : 'aktivitas_staf';
            $photoPath = $request->file('dokumentasi_foto')->store($folder, 'public');
        }

        if ($newType !== $type) {
            // Delete old record, insert new record
            $insertData = [
                'tanggal'            => $data['tanggal'],
                'waktu'              => $data['waktu'],
                'nama_kegiatan'      => $data['nama_kegiatan'],
                'kategori'           => $data['kategori'],
                'lokasi'             => $data['lokasi'],
                'deskripsi_kegiatan' => $data['deskripsi_kegiatan'],
                'dokumentasi_foto'   => $photoPath,
                'status'             => $aktivitas->status,
                'dibuat_oleh'        => $aktivitas->dibuat_oleh,
            ];

            if ($newType === 'tenaga_ahli') {
                $insertData['tenaga_ahli_id'] = $newStafId;
                AktivitasTenagaAhli::create($insertData);
            } else {
                $insertData['staf_administrasi_id'] = $newStafId;
                AktivitasStafAdministrasi::create($insertData);
            }

            $aktivitas->delete();
        } else {
            // Update same table
            $updateData = [
                'tanggal'            => $data['tanggal'],
                'waktu'              => $data['waktu'],
                'nama_kegiatan'      => $data['nama_kegiatan'],
                'kategori'           => $data['kategori'],
                'lokasi'             => $data['lokasi'],
                'deskripsi_kegiatan' => $data['deskripsi_kegiatan'],
                'dokumentasi_foto'   => $photoPath,
            ];

            if ($type === 'tenaga_ahli') {
                $updateData['tenaga_ahli_id'] = $newStafId;
            } else {
                $updateData['staf_administrasi_id'] = $newStafId;
            }

            $aktivitas->update($updateData);
        }

        return redirect()->route('admin.aktivitas-staf-fraksi.index')->with('success', 'Aktivitas Staf Fraksi berhasil diperbarui.');
    }

    public function destroy($type, $id)
    {
        if ($type === 'tenaga_ahli') {
            $aktivitas = AktivitasTenagaAhli::findOrFail($id);
        } elseif ($type === 'staf_administrasi') {
            $aktivitas = AktivitasStafAdministrasi::findOrFail($id);
        } else {
            abort(404);
        }

        if ($aktivitas->dokumentasi_foto && Storage::disk('public')->exists($aktivitas->dokumentasi_foto)) {
            Storage::disk('public')->delete($aktivitas->dokumentasi_foto);
        }

        $aktivitas->delete();

        return redirect()->route('admin.aktivitas-staf-fraksi.index')->with('success', 'Aktivitas Staf Fraksi berhasil dihapus.');
    }
}
