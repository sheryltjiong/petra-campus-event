<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class VolunteerApplication extends Model
{
    protected $fillable = [
        'user_id', 
        'event_id', 
        'position_id', 
        'status', 
        'reviewed_by',
        'reviewed_at',
        'notes',
        'interview_date',
        'interview_time',
        'interview_location',
        'interview_notes',
        'skkk',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
        'interview_date' => 'datetime',
        'interview_time' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function position()
    {
        return $this->belongsTo(VolunteerPosition::class, 'position_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    // Status check methods
      public function isAccepted()
    {
        return $this->status === 'accepted';
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }
     public function isInterviewScheduled()
    {
        return $this->status === 'interview_scheduled';
    }

    public function isRejected()
    {
        return $this->status === 'rejected';
    }
     public function isApproved()
    {
        return $this->status === 'accepted';
    }

    // Scope methods
    public function scopeAccepted($query)
    {
        return $query->where('status', 'accepted');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
     public function scopeInterviewScheduled($query)
    {
        return $query->where('status', 'interview_scheduled');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

     // Legacy scope for backward compatibility
    public function scopeApproved($query)
    {
        return $query->where('status', 'accepted');
    }

     // New helper methods for interview
    public function hasInterview()
    {
        return !is_null($this->interview_date);
    }

    public function getFormattedInterviewDateAttribute()
    {
        return $this->interview_date ? Carbon::parse($this->interview_date)->format('d M Y') : null;
    }

    public function getFormattedInterviewTimeAttribute()
    {
        return $this->interview_time ? Carbon::parse($this->interview_time)->format('H:i') : null;
    }

    public function getInterviewStatusDisplayAttribute()
    {
        switch ($this->status) {
            case 'pending':
                return 'Menunggu Review';
            case 'interview_scheduled':
                return 'Interview Dijadwalkan';
            case 'accepted':
                return 'Diterima';
            case 'rejected':
                return 'Ditolak';
            default:
                return 'Status Tidak Diketahui';
        }
    }

    public function canScheduleInterview()
    {
        return $this->status === 'pending';
    }

    public function canMakeFinalDecision()
    {
        return $this->status === 'interview_scheduled';
    }
}