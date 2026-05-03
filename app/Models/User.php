<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'role_id',
        'name',
        'email',
        'password',
        'google_id',
        'avatar',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function isAdmin()
    {
        return $this->role && $this->role->nama_role === 'admin';
    }

    public function isGuru()
    {
        return $this->role && $this->role->nama_role === 'guru';
    }

    public function isSiswa()
    {
        return $this->role && $this->role->nama_role === 'siswa';
    }

    public function progressBelajar()
    {
        return $this->hasMany(ProgressBelajar::class, 'user_id');
    }
    
    public function materi()
    {
        return $this->hasMany(Materi::class, 'user_id');
    }

    public function kuisJawaban()
    {
        return $this->hasMany(KuisJawabanSiswa::class, 'user_id');
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class, 'user_id');
    }
}
