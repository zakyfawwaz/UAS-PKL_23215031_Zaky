<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StafAdministrasi extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang digunakan oleh model.
     *
     * @var string
     */
    protected $table = 'staf_administrasi';

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama_lengkap',
        'jabatan',
        'status',
    ];

    /**
     * Atribut yang harus di-cast ke tipe native.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'status' => 'boolean',
    ];

    // =========================================================================
    // SCOPES
    // =========================================================================

    /**
     * Scope: hanya staf yang aktif.
     */
    public function scopeAktif($query)
    {
        return $query->where('status', true);
    }

    /**
     * Scope: hanya staf yang tidak aktif.
     */
    public function scopeTidakAktif($query)
    {
        return $query->where('status', false);
    }
}
