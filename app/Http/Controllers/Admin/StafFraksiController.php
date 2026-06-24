<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TenagaAhli;
use App\Models\StafAdministrasi;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class StafFraksiController extends Controller
{
    public function index(Request $request)
    {
        $tenagaAhli = TenagaAhli::all()->map(function ($item) {
            $item->jenis_staf = 'tenaga_ahli';
            return $item;
        });

        $stafAdministrasi = StafAdministrasi::all()->map(function ($item) {
            $item->jenis_staf = 'staf_administrasi';
            return $item;
        });

        $merged = $tenagaAhli->concat($stafAdministrasi)->sortByDesc('created_at');

        $perPage = 15;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentItems = $merged->slice(($currentPage - 1) * $perPage, $perPage)->values();

        $paginatedStaf = new LengthAwarePaginator(
            $currentItems,
            $merged->count(),
            $perPage,
            $currentPage,
            ['path' => LengthAwarePaginator::resolveCurrentPath()]
        );

        return view('admin.staf-fraksi.index', [
            'stafFraksi' => $paginatedStaf,
        ]);
    }

    public function create()
    {
        return view('admin.staf-fraksi.form');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_lengkap' => 'required|string|max:100',
            'status'       => 'required|boolean',
            'jenis_staf'   => 'required|in:tenaga_ahli,staf_administrasi',
        ]);

        if ($data['jenis_staf'] === 'tenaga_ahli') {
            TenagaAhli::create([
                'nama_lengkap' => $data['nama_lengkap'],
                'status'       => $data['status'],
            ]);
        } else {
            StafAdministrasi::create([
                'nama_lengkap' => $data['nama_lengkap'],
                'status'       => $data['status'],
            ]);
        }

        return redirect()->route('admin.staf-fraksi.index')->with('success', 'TA/SA Fraksi berhasil ditambahkan.');
    }

    public function show($type, $id)
    {
        return redirect()->route('admin.staf-fraksi.edit', ['type' => $type, 'id' => $id]);
    }

    public function edit($type, $id)
    {
        if ($type === 'tenaga_ahli') {
            $staf = TenagaAhli::findOrFail($id);
        } elseif ($type === 'staf_administrasi') {
            $staf = StafAdministrasi::findOrFail($id);
        } else {
            abort(404);
        }

        $staf->jenis_staf = $type;

        return view('admin.staf-fraksi.form', [
            'staf' => $staf,
        ]);
    }

    public function update(Request $request, $type, $id)
    {
        if ($type === 'tenaga_ahli') {
            $staf = TenagaAhli::findOrFail($id);
        } elseif ($type === 'staf_administrasi') {
            $staf = StafAdministrasi::findOrFail($id);
        } else {
            abort(404);
        }

        $data = $request->validate([
            'nama_lengkap' => 'required|string|max:100',
            'status'       => 'required|boolean',
            'jenis_staf'   => 'required|in:tenaga_ahli,staf_administrasi',
        ]);

        if ($data['jenis_staf'] !== $type) {
            // Move type/table
            if ($data['jenis_staf'] === 'tenaga_ahli') {
                TenagaAhli::create([
                    'nama_lengkap' => $data['nama_lengkap'],
                    'status'       => $data['status'],
                ]);
            } else {
                StafAdministrasi::create([
                    'nama_lengkap' => $data['nama_lengkap'],
                    'status'       => $data['status'],
                ]);
            }
            $staf->delete();
        } else {
            // Update in-place
            $staf->update([
                'nama_lengkap' => $data['nama_lengkap'],
                'status'       => $data['status'],
            ]);
        }

        return redirect()->route('admin.staf-fraksi.index')->with('success', 'Data TA/SA Fraksi berhasil diperbarui.');
    }

    public function destroy($type, $id)
    {
        if ($type === 'tenaga_ahli') {
            $staf = TenagaAhli::findOrFail($id);
        } elseif ($type === 'staf_administrasi') {
            $staf = StafAdministrasi::findOrFail($id);
        } else {
            abort(404);
        }

        $staf->delete();

        return redirect()->route('admin.staf-fraksi.index')->with('success', 'TA/SA Fraksi berhasil dihapus.');
    }
}
