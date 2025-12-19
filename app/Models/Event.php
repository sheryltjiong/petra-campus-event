<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Event extends Model
{
    protected $fillable = [
        'name',
        'date',
        'time',
        'location',
        'image',
        'description',
        'slot_peserta',
        'skkk_points',
        'skkk_points_volunteer',
        'skkk_category',
        'registration_phase',
        'created_by',
        'organizer_type'
    ];

    protected $casts = [
        'date' => 'date',
        'slot_peserta' => 'integer',
    ];

    protected function skkkCategoryName(): Attribute
    {
        return new Attribute(
            get: fn (mixed $value, array $attributes) => match ($attributes['skkk_category']) {
                'A' => 'Penalaran',
                'B' => 'Bakat Minat',
                'C' => 'Organisasi',
                'D' => 'Pengmas',
                default => null,
            },
        );
    }

    
    public function users()
    {
        return $this->belongsToMany(User::class, 'event_admins')
            ->withTimestamps();
    }


    public function volunteerPositions()
    {
        return $this->hasMany(VolunteerPosition::class);
    }

    public function eventRegistrations()
    {
        return $this->hasMany(EventRegistration::class);
    }

    public function volunteerApplications()
    {
        return $this->hasMany(VolunteerApplication::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Helper methods
    public function getFormattedDateAttribute()
    {
        return Carbon::parse($this->date)->format('d M Y');
    }

    public function getFormattedTimeAttribute()
    {
        return Carbon::parse($this->time)->format('H:i');
    }

    public function isVolunteerRegistrationOpen()
    {
        return $this->registration_phase === 'volunteer_open';
    }

    public function isParticipantRegistrationOpen()
    {
        return $this->registration_phase === 'participant_open';
    }

    public function isRegistrationClosed()
    {
        return $this->registration_phase === 'closed';
    }
}