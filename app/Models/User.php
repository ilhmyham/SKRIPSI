<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password_2',
        'roles_role_id',
        'google_id',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password_2',
        'remember_token',
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
            'password_2' => 'hashed',
        ];
    }

    /**
     * Get the role
     */
    public function role()
    {
        return $this->belongsTo(Role::class, 'roles_role_id', 'role_id');
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role && $this->role->nama_role === 'admin';
    }

    /**
     * Check if user is guru (teacher)
     */
    public function isGuru(): bool
    {
        return $this->role && $this->role->nama_role === 'guru';
    }

    /**
     * Check if user is siswa (student)
     */
    public function isSiswa(): bool
    {
        return $this->role && $this->role->nama_role === 'siswa';
    }

    /**
     * Get created materials (for teachers)
     */
    public function materials()
    {
        return $this->hasMany(Materi::class, 'users_user_id', 'id');
    }

    /**
     * Get learning progress (for students)
     */
    public function progress()
    {
        return $this->hasMany(ProgressBelajar::class, 'users_user_id', 'id');
    }

    /**
     * Get quiz answers (for students)
     */
    public function jawabanKuis()
    {
        return $this->hasMany(JawabanSiswa::class, 'users_user_id', 'id');
    }

    /**
     * Get created assignments (for teachers)
     */
    public function tugasDibuat()
    {
        return $this->hasMany(Tugas::class, 'users_user_id', 'id');
    }

    /**
     * Get assignment submissions (for students)
     */
    public function tugasDikumpulkan()
    {
        return $this->hasMany(PengumpulanTugas::class, 'users_user_id', 'id');
    }

    /**
     * Override getAuthPassword for authentication
     */
    public function getAuthPassword()
    {
        return $this->password_2;
    }
}
