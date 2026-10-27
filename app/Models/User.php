<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Laporan;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Atribut yang boleh diisi secara massal.
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relasi: satu user memiliki banyak laporan.
     */
    public function laporans()
    {
        return $this->hasMany(Laporan::class);
    }

    /**
     * Periksa apakah pengguna memiliki peran admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function claims()
{
    return $this->hasMany(Claim::class);
}

public function reportedConversations()
{
    return $this->hasMany(Conversation::class, 'reporter_id');
}

public function participatedConversations()
{
    return $this->hasMany(Conversation::class, 'participant_id');
}

public function sentMessages()
{
    return $this->hasMany(Message::class, 'sender_id');
}
}
