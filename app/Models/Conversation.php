<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $fillable = [
        'laporan_id',
        'reporter_id',
        'participant_id',
    ];

    public function laporan()
    {
        return $this->belongsTo(Laporan::class, 'laporan_id');
    }

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    public function participant()
    {
        return $this->belongsTo(User::class, 'participant_id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    /**
     * Pesan terakhir (untuk ringkasan di inbox).
     */
    public function latestMessage()
    {
        return $this->hasOne(Message::class)->latestOfMany();
    }

    /**
     * Apakah user ini peserta percakapan (pemilik laporan atau penanya)?
     * Admin TIDAK otomatis dianggap peserta.
     */
    public function hasParticipant(User $user): bool
    {
        return (int) $user->id === (int) $this->reporter_id
            || (int) $user->id === (int) $this->participant_id;
    }

    /**
     * Lawan bicara dari sudut pandang $user.
     */
    public function otherUser(User $user): ?User
    {
        return (int) $user->id === (int) $this->reporter_id
            ? $this->participant
            : $this->reporter;
    }
}
