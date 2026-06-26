<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AktivitasStafAdministrasi extends Model
{
    use HasFactory;

    protected $table = 'aktivitas_staf_administrasi';

    protected $fillable = [
        'staf_administrasi_id',
        'tanggal',
        'waktu',
        'nama_kegiatan',
        'kategori',
        'lokasi',
        'deskripsi_kegiatan',
        'dokumentasi_foto',
        'status',
        'dibuat_oleh',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'waktu'   => 'string',
        'status'  => 'boolean',
    ];

    public const KATEGORI_UMUM = [
        'opd'         => 'OPD',
        'konstituen'  => 'Konstituen',
        'Fraksi'  => 'Fraksi',
    ];

    public static function semuaKategori(): array
    {
        return self::KATEGORI_UMUM;
    }

    public static function kategoriGrouped(): array
    {
        return [
            'Umum' => self::KATEGORI_UMUM,
        ];
    }

    public function stafAdministrasi(): BelongsTo
    {
        return $this->belongsTo(StafAdministrasi::class, 'staf_administrasi_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }

    public function scopeKategori($query, string $kategori)
    {
        return $query->where('kategori', $kategori);
    }

    public function scopeHariIni($query)
    {
        return $query->whereDate('tanggal', today());
    }

    public function scopeBulan($query, int $bulan, ?int $tahun = null)
    {
        $tahun = $tahun ?? now()->year;
        return $query->whereMonth('tanggal', $bulan)
                     ->whereYear('tanggal', $tahun);
    }

    public function scopeTahun($query, int $tahun)
    {
        return $query->whereYear('tanggal', $tahun);
    }

    public function scopeMilikStaf($query, int $id)
    {
        return $query->where('staf_administrasi_id', $id);
    }

    public function getLabelKategoriAttribute(): string
    {
        return self::semuaKategori()[$this->kategori] ?? ucfirst($this->kategori);
    }

    public function getDokumentasiFotoUrlAttribute(): ?string
    {
        return $this->dokumentasi_foto
            ? asset('storage/' . $this->dokumentasi_foto)
            : null;
    }
}
