<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Event;
use App\Models\VolunteerApplication;
use App\Models\EventRegistration;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();

        // ambil event yang posisinya sebagai peserta lewat model
        $participantEvents = EventRegistration::where('user_id', $user->id)
            ->where('status', 'approved')
            ->with('event')
            ->get()
            ->map(function ($registration) {
                return (object) [
                    'id' => $registration->event->id ?? null,
                    'name' => $registration->event->name ?? 'Unknown Event',
                    'date' => $registration->event->date ?? now(),
                    'location' => $registration->event->location ?? 'TBA'
                ];
            });

         // ambil event yang posisinya sebagai peserta lewat model
        $volunteerEvents = VolunteerApplication::where('user_id', $user->id)
            ->where('status', 'accepted')
            ->with(['event', 'position'])
            ->get()
            ->map(function ($application) {
                return (object) [
                    'id' => $application->event->id ?? null,
                    'name' => $application->event->name ?? 'Unknown Event',
                    'date' => $application->event->date ?? now(),
                    'location' => $application->event->location ?? 'TBA',
                    'position' => $application->position->position_name ?? 'Volunteer'
                ];
            });

        // ambil semua pendaftaran volunteer buat status table
        $volunteerApplications = VolunteerApplication::where('user_id', $user->id)
            ->with(['event', 'position'])
            ->orderBy('created_at', 'desc')
            ->get();

        // ambil semua pendaftaran peserta buat status table
        $participantRegistrations = EventRegistration::where('user_id', $user->id)
            ->with('event')
            ->orderBy('created_at', 'desc')
            ->get();

        // ambil SKKK summary via model user 
        $skkkSummary = $user->getSkkkSummary();


        return view('dashboard.index', compact(
            'user',
            'participantEvents',
            'volunteerEvents',
            'volunteerApplications',
            'participantRegistrations',
            'skkkSummary',
        ));
    }
}