<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Aktivitas;
use App\Models\AnggotaDewan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AktivitasController extends Controller
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

        return view('admin.aktivitas.index', [
            'aktivitas'     => $query->get(),
            'daftarAnggota' => AnggotaDewan::aktif()->orderBy('nama_lengkap')->get(),
        ]);
    }

    public function create()
    {
        return view('admin.aktivitas.form', [
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
        return redirect()->route('admin.aktivitas.index')->with('success', 'Aktivitas berhasil ditambahkan.');
    }

    public function show(Aktivitas $aktivita)
    {
        return redirect()->route('admin.aktivitas.edit', $aktivita);
    }

    public function edit(Aktivitas $aktivita)
    {
        return view('admin.aktivitas.form', [
            'aktivitas'     => $aktivita,
            'daftarAnggota' => AnggotaDewan::aktif()->orderBy('nama_lengkap')->get(),
        ]);
    }

    public function update(Request $request, Aktivitas $aktivita)
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
            // Delete old photo file if it exists
            if ($aktivita->dokumentasi_foto && Storage::disk('public')->exists($aktivita->dokumentasi_foto)) {
                Storage::disk('public')->delete($aktivita->dokumentasi_foto);
            }
            $data['dokumentasi_foto'] = $request->file('dokumentasi_foto')->store('aktivitas', 'public');
        }

        $aktivita->update($data);
        return redirect()->route('admin.aktivitas.index')->with('success', 'Aktivitas berhasil diperbarui.');
    }

    public function destroy(Aktivitas $aktivita)
    {
        // Delete associated photo file from storage
        if ($aktivita->dokumentasi_foto && Storage::disk('public')->exists($aktivita->dokumentasi_foto)) {
            Storage::disk('public')->delete($aktivita->dokumentasi_foto);
        }

        $aktivita->delete();
        return redirect()->route('admin.aktivitas.index')->with('success', 'Aktivitas berhasil dihapus.');
    }
}
