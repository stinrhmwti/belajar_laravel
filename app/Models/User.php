<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * @property int $id
 * @property string $name
 * @property string $username
 * @property string|null $email
 * @property string $password
 * @property string $role
 * @property string|null $kelas
 * @property string|null $nis
 */
class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'username',
        'email',
        'no_telepon',
        'nomor_sim',
        'jenis_sim',
        'masa_berlaku_sim',
        'password',
        'role',
        'kelas',
        'nis',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'masa_berlaku_sim' => 'date',
    ];

    public function getAuthPassword()
    {
        return $this->password;
    }

    public function isSuperAdmin()
    {
        return $this->role === 'superadmin';
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isTeknisi()
    {
        return $this->role === 'teknisi';
    }

    public function isDriver()
    {
        return $this->role === 'user';
    }

    public function isPimpinan()
    {
        return $this->role === 'pimpinan';
    }

    /**
     * Relasi ke armada yang ditugaskan ke pengemudi ini
     */
    public function assignedVehicles()
    {
        return $this->hasMany(Vehicle::class, 'driver_id');
    }

    /**
     * Warning status masa berlaku SIM pengemudi:
     * - 'merah' : sudah lewat masa berlaku
     * - 'kuning': mendekati kedaluwarsa (<= 30 hari)
     * - 'hijau' : aman
     * - 'none'  : belum mengisi tanggal SIM
     */
    public function getStatusSimAttribute(): string
    {
        if (!$this->masa_berlaku_sim) {
            return 'none';
        }

        $today = \Carbon\Carbon::now()->startOfDay();
        $dueDate = \Carbon\Carbon::parse($this->masa_berlaku_sim)->startOfDay();

        if ($today->greaterThan($dueDate)) {
            return 'merah';
        }

        if ($today->diffInDays($dueDate) <= 30) {
            return 'kuning';
        }

        return 'hijau';
    }
}
