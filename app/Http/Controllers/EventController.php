<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\VolunteerPosition;
use App\Models\EventRegistration;
use App\Models\VolunteerApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    public function index(Request $request)
    {
        $filter = $request->query('filter');

        $eventsQuery = Event::query();

        if ($filter === 'panitia') {
            $eventsQuery->where('registration_phase', 'volunteer_open');
        } elseif ($filter === 'peserta') {
            $eventsQuery->where('registration_phase', 'participant_open');
        }

        $events = $eventsQuery->orderBy('date', 'desc')->get();

        // Jika request AJAX, kirim HTML fragment saja (bukan satu view)
        if ($request->ajax()) {
            $html = '';

            foreach ($events as $event) {
                $html .= '
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition duration-300">
                    <div class="p-6">
                        <h3 class="font-bold text-lg text-gray-800 mb-2">' . e($event->name) . '</h3>
                        <p class="text-sm text-gray-600 mb-2">' . \Carbon\Carbon::parse($event->date)->format('d M Y') . '</p>
                        <p class="text-sm text-gray-600 mb-4">';
                if ($event->registration_phase === 'volunteer_open') {
                    $html .= '<span class="text-blue-600 font-medium">Dicari: Panitia</span>';
                } elseif ($event->registration_phase === 'participant_open') {
                    $html .= '<span class="text-green-600 font-medium">Dicari: Peserta</span>';
                } else {
                    $html .= '<span class="text-gray-500">Pendaftaran ditutup</span>';
                }
                $html .= '</p>
                        <a href="' . route('events.show', $event->id) . '" 
                            class="inline-block w-full text-center bg-blue-950 text-white px-4 py-2 rounded-lg hover:bg-blue-800 transition duration-300">
                            More Info
                        </a>
                    </div>
                </div>';
            }

            if ($events->isEmpty()) {
                $html = '<div class="col-span-full text-center py-16 text-gray-500">Tidak ada event ditemukan.</div>';
            }

            return response()->json(['html' => $html]);
        }

        // Bukan AJAX, tampilkan halaman seperti biasa
        return view('events.index', compact('events'));
    }




    public function show($id)
    {
        $event = Event::findOrFail($id);
        $registration_phase = $event->registration_phase;
        $volunteerPositions = VolunteerPosition::where('event_id', $id)->get();

        $hasRegisteredAsParticipant = false;
        $hasRegisteredAsVolunteer = false;

        if (Auth::check()) {
            $userId = Auth::id();

            $hasRegisteredAsParticipant = EventRegistration::where('user_id', $userId)
                ->where('event_id', $id)
                ->exists();

            $hasRegisteredAsVolunteer = VolunteerApplication::where('user_id', $userId)
                ->where('event_id', $id)
                ->exists();
        }

        return view('events.show', compact(
            'event',
            'registration_phase',
            'volunteerPositions',
            'hasRegisteredAsParticipant',
            'hasRegisteredAsVolunteer'
        ));
    }


    public function register($id, Request $request)
    {
        $event = Event::findOrFail($id);
        $role = $request->input('role');
        $userId = Auth::id();

        //cek apakah user sudah daftar sebagai salah satu (volunteer atau participant)
        $alreadyParticipant = EventRegistration::where('user_id', $userId)
            ->where('event_id', $id)
            ->exists();

        $alreadyVolunteer = VolunteerApplication::where('user_id', $userId)
            ->where('event_id', $id)
            ->exists();

        if ($alreadyParticipant || $alreadyVolunteer) {
            return redirect()->route('events.show', $id)
                ->with('error', 'You have already registered for this event.');
        }

        //arahkan ke form volunteer jika volunteer, simpan langsung jika participant
        if ($role === 'volunteer' && $event->registration_phase === 'volunteer_open') {
            return redirect()->route('volunteer.register', $id);
        } elseif ($role === 'participant' && $event->registration_phase === 'participant_open') {
            $registration = new EventRegistration();
            $registration->user_id = $userId;
            $registration->event_id = $id;
            $registration->status = 'pending';
            $registration->save();

            return redirect()->route('dashboard')->with('success', 'Successfully registered as volunteer!');
        }

        return back()->withErrors(['role' => 'Registration not available for this role at this time.']);
    }


    public function registerParticipant($id, Request $request)
    {
        $event = Event::findOrFail($id);

        if ($event->registration_phase !== 'participant_open') {
            return back()->withErrors(['registration' => 'Participant registration is not open for this event.']);
        }

        $userId = Auth::id();

        //cek apakah user sudah mendaftar sebagai peserta atau volunteer
        $alreadyParticipant = EventRegistration::where('user_id', $userId)
            ->where('event_id', $id)
            ->exists();

        $alreadyVolunteer = VolunteerApplication::where('user_id', $userId)
            ->where('event_id', $id)
            ->exists();

        if ($alreadyParticipant || $alreadyVolunteer) {
            return redirect()->route('events.show', $id)
                ->with('error', 'You have already registered for this event.');
        }

        $data = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'email' => 'required|email',
            'nrp' => 'required|string',
            'jurusan' => 'required|string',
            'additional_info' => 'nullable|string',
            'terms' => 'required|accepted',
        ]);

        EventRegistration::create([
            'user_id' => $userId,
            'event_id' => $id,
            'status' => 'pending',
            'skkk' => $event->skkk_points ?? 0,
            'additional_data' => json_encode($data)
        ]);

        return redirect()->route('dashboard')->with('success', 'Successfully registered as participant!');
    }


    public function showParticipantForm($id)
    {
        $event = Event::findOrFail($id);

        if ($event->registration_phase !== 'participant_open') {
            return redirect()->route('events.show', $id)->withErrors(['registration' => 'Participant registration is not open.']);
        }

        $userId = Auth::id();
        // Cek apakah user sudah mendaftar sebagai peserta atau panitia
        $alreadyParticipant = EventRegistration::where('user_id', $userId)
            ->where('event_id', $id)
            ->exists();

        $alreadyVolunteer = VolunteerApplication::where('user_id', $userId)
            ->where('event_id', $id)
            ->exists();

        if ($alreadyParticipant) {
            return redirect()->route('events.show', $id)
                ->with('error', 'You have already registered as a participant for this event.');
        }

        if ($alreadyVolunteer) {
            return redirect()->route('events.show', $id)
                ->with('error', 'You have already registered as a volunteer for this event.');
        }

        $user = Auth::user();
        return view('events.participant-form', compact('event', 'user'));
    }
}