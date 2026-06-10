<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TenagaAhli;
use Illuminate\Http\Request;

class TenagaAhliController extends Controller
{
    public function index()
    {
        return view('admin.tenaga-ahli.index', [
            'tenagaAhli' => TenagaAhli::latest()->paginate(15),
        ]);
    }

    public function create()
    {
        return view('admin.tenaga-ahli.form');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_lengkap' => 'required|string|max:100',
            'jabatan'      => 'required|string|max:100',
            'status'       => 'required|boolean',
        ]);

        TenagaAhli::create($data);
        return redirect()->route('admin.tenaga-ahli.index')->with('success', 'Tenaga ahli berhasil ditambahkan.');
    }

    public function show(TenagaAhli $tenaga_ahli)
    {
        return redirect()->route('admin.tenaga-ahli.edit', $tenaga_ahli);
    }

    public function edit(TenagaAhli $tenaga_ahli)
    {
        return view('admin.tenaga-ahli.form', ['tenagaAhli' => $tenaga_ahli]);
    }

    public function update(Request $request, TenagaAhli $tenaga_ahli)
    {
        $data = $request->validate([
            'nama_lengkap' => 'required|string|max:100',
            'jabatan'      => 'required|string|max:100',
            'status'       => 'required|boolean',
        ]);

        $tenaga_ahli->update($data);
        return redirect()->route('admin.tenaga-ahli.index')->with('success', 'Data tenaga ahli berhasil diperbarui.');
    }

    public function destroy(TenagaAhli $tenaga_ahli)
    {
        $tenaga_ahli->delete();
        return redirect()->route('admin.tenaga-ahli.index')->with('success', 'Tenaga ahli berhasil dihapus.');
    }
}
