<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\VolunteerPosition;
use App\Models\VolunteerApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VolunteerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // Correct method name: middleware, not middlware
    }

    public function showRegistrationForm($eventId)
    {
        $event = Event::findOrFail($eventId);

        if ($event->registration_phase !== 'volunteer_open') {
            return redirect()->route('events.show', $eventId)
                ->withErrors(['registration' => 'Volunteer registration is not open for this event.']);
        }

        $userId = Auth::id();

        $alreadyParticipant = \App\Models\EventRegistration::where('user_id', $userId)
            ->where('event_id', $eventId)
            ->exists();

        $alreadyVolunteer = \App\Models\VolunteerApplication::where('user_id', $userId)
            ->where('event_id', $eventId)
            ->exists();

        if ($alreadyParticipant || $alreadyVolunteer) {
            return redirect()->route('events.show', $eventId)
                ->with('error', 'You have already registered as a participant or volunteer.');
        }

        $volunteerPositions = VolunteerPosition::where('event_id', $eventId)
    ->with('volunteerApplications') // penting!
    ->get();

        if (!$volunteerPositions) {
            return back()->withErrors(['position' => 'Invalid position for this event.']);
        }

        return view('volunteer.register', compact('event', 'volunteerPositions'));
    }


    public function register(Request $request, $eventId)
    {
        $event = Event::findOrFail($eventId);


        if ($event->registration_phase !== 'volunteer_open') {
            return back()->withErrors(['registration' => 'Volunteer registration is not open.']);
        }

        $userId = Auth::id();

        // Cegah jika user sudah daftar sebagai peserta
        $alreadyParticipant = \App\Models\EventRegistration::where('user_id', $userId)
            ->where('event_id', $eventId)
            ->exists();

        if ($alreadyParticipant) {
            return redirect()->route('events.show', $eventId)
                ->with('error', 'You have already registered as a participant and cannot register as a volunteer.');
        }



        $data = $request->validate([
            'position_id' => 'required|exists:volunteer_positions,id'
        ]);

        $position = VolunteerPosition::find($data['position_id']);
        if ($position->remaining_quota <= 0) {
            return back()->withErrors(['position' => 'Kuota posisi ini sudah habis.']);
        }

        // Cek apakah user sudah apply ke posisi ini sebelumnya
        $existingApplication = VolunteerApplication::where('user_id', $userId)
            ->where('event_id', $eventId)
            ->where('position_id', $data['position_id'])
            ->first();

        if ($existingApplication) {
            return back()->withErrors(['position' => 'You have already applied for this position.']);
        }

        $application = VolunteerApplication::create([
            'user_id' => $userId,
            'event_id' => $eventId,
            'position_id' => $data['position_id'],
            'status' => 'pending'
        ]);

        return redirect()->route('dashboard')->with('success', 'Successfully applied for volunteer position!');
    }

   


}