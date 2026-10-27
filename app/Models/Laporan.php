<?php

namespace App\Models;

use Database\Factories\LaporanFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    /** @use HasFactory<LaporanFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'judul',
        'tipe',
        'kategori',
        'deskripsi',
        'lokasi',
        'tanggal_kejadian',
        'foto',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function claims()
{
    return $this->hasMany(Claim::class, 'laporan_id');
}

public function conversations()
{
    return $this->hasMany(Conversation::class, 'laporan_id');
}
}
