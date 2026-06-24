<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Aktivitas;
use App\Models\AnggotaDewan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AktivitasDewanController extends Controller
{
    public function index(Request $request)
    {
        $query = Aktivitas::with('anggotaDewan')->latest('tanggal')->latest('waktu');

        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        }
        if ($request->filled('kategori')) {
            $query->kategori($request->kategori);
        }
        if ($request->filled('anggota')) {
            $query->milikAnggota($request->anggota);
        }

        return view('admin.aktivitas-dewan.index', [
            'aktivitas'     => $query->paginate(15),
            'daftarAnggota' => AnggotaDewan::aktif()->orderBy('nama_lengkap')->get(),
        ]);
    }

    public function create()
    {
        return view('admin.aktivitas-dewan.form', [
            'daftarAnggota' => AnggotaDewan::aktif()->orderBy('nama_lengkap')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'anggota_dewan_id'   => 'required|exists:anggota_dewans,id',
            'tanggal'            => 'required|date',
            'waktu'              => 'required',
            'nama_kegiatan'      => 'required|string|max:255',
            'kategori'           => 'required|in:' . implode(',', array_keys(Aktivitas::semuaKategori())),
            'lokasi'             => 'required|string|max:255',
            'deskripsi_kegiatan' => 'nullable|string',
            'dokumentasi_foto'   => 'nullable|file|mimes:jpg,jpeg,png|max:5120',
        ]);

        $data['dibuat_oleh'] = auth()->id();

        if ($request->hasFile('dokumentasi_foto')) {
            $data['dokumentasi_foto'] = $request->file('dokumentasi_foto')->store('aktivitas', 'public');
        }

        Aktivitas::create($data);
        return redirect()->route('admin.aktivitas-dewan.index')->with('success', 'Aktivitas Dewan berhasil ditambahkan.');
    }

    public function show(Aktivitas $aktivitas_dewan)
    {
        return redirect()->route('admin.aktivitas-dewan.edit', $aktivitas_dewan);
    }

    public function edit(Aktivitas $aktivitas_dewan)
    {
        return view('admin.aktivitas-dewan.form', [
            'aktivitas'     => $aktivitas_dewan,
            'daftarAnggota' => AnggotaDewan::aktif()->orderBy('nama_lengkap')->get(),
        ]);
    }

    public function update(Request $request, Aktivitas $aktivitas_dewan)
    {
        $data = $request->validate([
            'anggota_dewan_id'   => 'required|exists:anggota_dewans,id',
            'tanggal'            => 'required|date',
            'waktu'              => 'required',
            'nama_kegiatan'      => 'required|string|max:255',
            'kategori'           => 'required|in:' . implode(',', array_keys(Aktivitas::semuaKategori())),
            'lokasi'             => 'required|string|max:255',
            'deskripsi_kegiatan' => 'nullable|string',
            'dokumentasi_foto'   => 'nullable|file|mimes:jpg,jpeg,png|max:5120',
        ]);

        if ($request->hasFile('dokumentasi_foto')) {
            if ($aktivitas_dewan->dokumentasi_foto && Storage::disk('public')->exists($aktivitas_dewan->dokumentasi_foto)) {
                Storage::disk('public')->delete($aktivitas_dewan->dokumentasi_foto);
            }
            $data['dokumentasi_foto'] = $request->file('dokumentasi_foto')->store('aktivitas', 'public');
        }

        $aktivitas_dewan->update($data);
        return redirect()->route('admin.aktivitas-dewan.index')->with('success', 'Aktivitas Dewan berhasil diperbarui.');
    }

    public function destroy(Aktivitas $aktivitas_dewan)
    {
        if ($aktivitas_dewan->dokumentasi_foto && Storage::disk('public')->exists($aktivitas_dewan->dokumentasi_foto)) {
            Storage::disk('public')->delete($aktivitas_dewan->dokumentasi_foto);
        }

        $aktivitas_dewan->delete();
        return redirect()->route('admin.aktivitas-dewan.index')->with('success', 'Aktivitas Dewan berhasil dihapus.');
    }
}
