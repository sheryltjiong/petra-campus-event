<?php
// app/Models/VolunteerPosition.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class VolunteerPosition extends Model
{
    protected $fillable = [
        'event_id',
        'position_name',
        'description',
        'initial_quota',
        'deadline'
    ];

    protected $casts = [
        'deadline' => 'datetime',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function volunteerApplications()
    {
        return $this->hasMany(VolunteerApplication::class, 'position_id');
    }

    // Helper methods
    public function getFormattedDeadlineAttribute()
    {
        return Carbon::parse($this->deadline)->format('d M Y');
    }

    public function getRemainingQuotaAttribute()
{
    $count = $this->volunteerApplications()->count();
    return max(0, $this->initial_quota - $count); 
}



    // Additional helper methods for better status tracking
    public function getAcceptedCountAttribute()
    {
        return $this->volunteerApplications()->where('status', 'accepted')->count();
    }

    public function getPendingCountAttribute()
    {
        return $this->volunteerApplications()->where('status', 'pending')->count();
    }

    public function getInterviewScheduledCountAttribute()
    {
        return $this->volunteerApplications()->where('status', 'interview_scheduled')->count();
    }

    public function getRejectedCountAttribute()
    {
        return $this->volunteerApplications()->where('status', 'rejected')->count();
    }

    public function isDeadlinePassed()
    {
        return Carbon::now()->isAfter($this->deadline);
    }

    public function isFull()
    {
        return $this->remaining_quota <= 0;
    }

    // Legacy support for backward compatibility
    public function getApprovedCountAttribute()
    {
        // Map to accepted for backward compatibility
        return $this->accepted_count;
    }
}