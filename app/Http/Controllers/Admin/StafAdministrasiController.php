<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StafAdministrasi;
use Illuminate\Http\Request;

class StafAdministrasiController extends Controller
{
    public function index()
    {
        return view('admin.staf-administrasi.index', [
            'staf' => StafAdministrasi::latest()->paginate(15),
        ]);
    }

    public function create()
    {
        return view('admin.staf-administrasi.form');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_lengkap' => 'required|string|max:100',
            'jabatan'      => 'required|string|max:100',
            'status'       => 'required|boolean',
        ]);

        StafAdministrasi::create($data);
        return redirect()->route('admin.staf-administrasi.index')->with('success', 'Staf administrasi berhasil ditambahkan.');
    }

    public function show(StafAdministrasi $staf_administrasi)
    {
        return redirect()->route('admin.staf-administrasi.edit', $staf_administrasi);
    }

    public function edit(StafAdministrasi $staf_administrasi)
    {
        return view('admin.staf-administrasi.form', ['staf' => $staf_administrasi]);
    }

    public function update(Request $request, StafAdministrasi $staf_administrasi)
    {
        $data = $request->validate([
            'nama_lengkap' => 'required|string|max:100',
            'jabatan'      => 'required|string|max:100',
            'status'       => 'required|boolean',
        ]);

        $staf_administrasi->update($data);
        return redirect()->route('admin.staf-administrasi.index')->with('success', 'Data staf administrasi berhasil diperbarui.');
    }

    public function destroy(StafAdministrasi $staf_administrasi)
    {
        $staf_administrasi->delete();
        return redirect()->route('admin.staf-administrasi.index')->with('success', 'Staf administrasi berhasil dihapus.');
    }
}
