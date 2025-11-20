<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'nrp',
        'jurusan',
        'line_id',
        'whatsapp',
        'role'
    ];

    //kolom ini tidak terlihat di JSON output supaya data gak bocor
    protected $hidden = [
        'password',
        'remember_token',
    ];

    //memastikan konversi tipe data yang benar
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    //admin bisa mengelola banyak event, disimpan di tabel event_admins
    public function managedEvents()
    {
        return $this->belongsToMany(Event::class, 'event_admins')->withTimestamps();
    }


    //1 user bisa mendaftar banyak event sebagai panitia
    public function volunteerApplications()
    {
        return $this->hasMany(VolunteerApplication::class);
    }

    //1 user bisa mendaftar banyak event sebagai peserta
    public function eventRegistrations()
    {
        return $this->hasMany(EventRegistration::class);
    }

    //metode2 untuk cek role user
    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

   
    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    //fungsi buat menjumlahkan total SKKK yang didapat user dari EventRegistration dan VolunteerApplication
    public function getTotalSkkk(): float
    {
        $participantPoints = $this->eventRegistrations()
            ->whereHas('event', fn($q) => $q->where('registration_phase', 'completed'))
            ->sum('skkk');

        $volunteerPoints = $this->volunteerApplications()
            ->whereHas('event', fn($q) => $q->where('registration_phase', 'completed'))
            ->sum('skkk');

        return $participantPoints + $volunteerPoints;
    }

    public function getSkkkSummary(): array
    {
        $summary = [
            'A' => 0, // Penalaran
            'B' => 0, // Bakat Minat
            'C' => 0, // Organisasi & Kepemimpinan
            'D' => 0  // Pengabdian Masyarakat
        ];

        // EventRegistration (sebagai peserta)
        $registrations = $this->eventRegistrations()
            ->where('status', 'approved')
            ->whereHas('event', fn($q) => $q->where('registration_phase', 'completed'))
            ->with('event')
            ->get();

        foreach ($registrations as $registration) {
            $category = $registration->event->skkk_category ?? null;
            if (in_array($category, ['A', 'B', 'C', 'D'])) {
                $summary[$category] += $registration->skkk ?? 0;
            }
        }

        // VolunteerApplication (sebagai panitia)
        $applications = $this->volunteerApplications()
            ->where('status', 'accepted')
            ->whereHas('event', fn($q) => $q->where('registration_phase', 'completed'))
            ->with('event')
            ->get();

        foreach ($applications as $application) {
            $category = $application->event->skkk_category ?? null;
            if (in_array($category, ['A', 'B', 'C', 'D'])) {
                $summary[$category] += $application->skkk ?? 0;
            }
        }

        return $summary;
    }

    /**
     * Get full name attribute
     *
     * @return string
     */
    public function getFullNameAttribute(): string
    {
        return $this->name;
    }
}