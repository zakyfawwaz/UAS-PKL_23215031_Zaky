<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TenagaAhli extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang digunakan oleh model.
     *
     * @var string
     */
    protected $table = 'tenaga_ahli';

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama_lengkap',
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
     * Scope: hanya tenaga ahli yang aktif.
     */
    public function scopeAktif($query)
    {
        return $query->where('status', true);
    }

    /**
     * Scope: hanya tenaga ahli yang tidak aktif.
     */
    public function scopeTidakAktif($query)
    {
        return $query->where('status', false);
    }
}
