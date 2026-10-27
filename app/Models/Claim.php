<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Claim extends Model
{
    use HasFactory;

    protected $fillable = [
        'laporan_id',
        'user_id',
        'description',
        'status',
    ];

    public function laporan()
    {
        return $this->belongsTo(Laporan::class, 'laporan_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
