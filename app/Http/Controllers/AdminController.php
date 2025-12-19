<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\VolunteerPosition;
use App\Models\VolunteerApplication;
use App\Models\EventRegistration;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // Custom middleware logic to restrict access
        $this->middleware(function ($request, $next) {
            $user = Auth::user();
            if (!$user->isSuperAdmin() && !$user->isAdmin()) {
                return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
            }
            return $next($request);
        });
    }

    public function index()
    {
        $events = Event::with(['volunteerPositions', 'eventRegistrations'])
            ->orderBy('created_at', 'desc')
            ->get();

        $totalEvents = Event::count();
        $totalVolunteers = VolunteerApplication::where('status', 'accepted')->count();

        return view('admin.dashboard', compact('events', 'totalEvents', 'totalVolunteers'));
    }

    public function showCreateEventForm()
    {
        if (!Auth::user()->isSuperAdmin()) {
            return redirect()->route('admin.dashboard')->with('error', 'Only Super Admin can create events.');
        }

        $admins = User::where('role', 'admin')->get(); // Fetch semua admin
        return view('admin.create-event', compact('admins'));
    }


    public function createEvent(Request $request)
    {
        if (!Auth::user()->isSuperAdmin()) {
            return redirect()->route('admin.dashboard')->with('error', 'Only Super Admin can create events.');
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'time' => 'required|string',
            'location' => 'required|string|max:255',
            'image' => 'required|mimes:png,jpg,jpeg|max:10240', // Max 10MB
            'description' => 'required|string',
            'slot_peserta' => 'required|integer|min:1',
            'skkk_points' => 'required|numeric|min:0',
            'skkk_category' => 'nullable|string|max:255',
            'skkk_points_volunteer' => 'numeric|min:0',
            'admin_ids' => 'required|array',
            'admin_ids.*' => 'exists:users,id',
        ]);

        // [PERUBAHAN ADA DI SINI]
        // Mengubah disk 'public' menjadi 's3'
        // Laravel akan otomatis mengupload file ke bucket S3 yang dikonfigurasi
        $imagePath = $request->file('image')->store('events', 's3');

        $event = Event::create([
            'name' => $data['name'],
            'date' => $data['date'],
            'time' => $data['time'],
            'location' => $data['location'],
            'image' => $imagePath, // Path yang disimpan sekarang adalah path relative di S3
            'description' => $data['description'],
            'slot_peserta' => $data['slot_peserta'],
            'skkk_points' => $data['skkk_points'] ?? 0,
            'skkk_category' => $data['skkk_category'],
            'skkk_points_volunteer' => $data['skkk_points_volunteer'],
            'registration_phase' => 'volunteer_open',
            'created_by' => Auth::id(),
        ]);

        $event->users()->attach($data['admin_ids']);

        return redirect()->route('admin.dashboard')->with('success', 'Event created and admin(s) assigned successfully.');
    }

    public function editEvent($id)
    {
        $event = Event::findOrFail($id);
        $this->authorizeEventAccess($event); // jika admin

        $admins = User::where('role', 'admin')->get();
        return view('admin.edit-event', compact('event', 'admins'));
    }

    public function updateEvent(Request $request, $id)
    {
        $event = Event::findOrFail($id);
        $this->authorizeEventAccess($event);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'time' => 'required|string',
            'location' => 'required|string|max:255',
            'description' => 'required|string',
            'slot_peserta' => 'required|integer|min:1',
            'skkk_points' => 'required|numeric|min:0',
            'skkk_points_volunteer' => 'required|numeric|min:0',
            'skkk_category' => 'nullable|string|max:1',
            'admin_ids' => 'required|array',
            'admin_ids.*' => 'exists:users,id',
        ]);

        $event->update($data);
        $event->users()->sync($data['admin_ids']); // sync mengganti semua admin yang ditugaskan

        return redirect()->route('admin.dashboard')->with('success', 'Event berhasil diperbarui.');
    }



    public function manageVolunteers($id)
    {
        $event = Event::findOrFail($id);
        $this->authorizeEventAccess($event);

        $volunteerPositions = VolunteerPosition::where('event_id', $id)->with('volunteerApplications.user')->get();

        return view('admin.manage-volunteers', compact('event', 'volunteerPositions'));
    }

    public function showVolunteerPositions($id)
    {
        $event = Event::findOrFail($id);
        $this->authorizeEventAccess($event);
        $volunteerPositions = VolunteerPosition::where('event_id', $id)->get();

        return view('admin.volunteer-positions', compact('event', 'volunteerPositions'));
    }

    public function createVolunteerPosition(Request $request, $eventId)
    {
        $event = Event::findOrFail($eventId);
        $this->authorizeEventAccess($event);

        $data = $request->validate([
            'position_name' => 'required|string|max:255',
            'description' => 'required|string',
            'initial_quota' => 'required|integer|min:1',
            'deadline' => 'required|date|after:now',
        ]);

        $data['event_id'] = $eventId;

        VolunteerPosition::create($data);

        return redirect()->route('admin.volunteer-positions', $eventId)
            ->with('success', 'Volunteer position created successfully.');
    }


    public function deleteVolunteerPosition($positionId, $eventId)
    {
        $event = Event::findOrFail($eventId);
        $position = VolunteerPosition::findOrFail($positionId);
        $eventId = $position->event_id;
        $this->authorizeEventAccess($event);

        // Check if there are applications for this position
        if ($position->volunteerApplications()->count() > 0) {
            return back()->withErrors(['position' => 'Cannot delete position with existing applications.']);
        }

        $position->delete();

        return redirect()->route('admin.volunteer-positions', $eventId)
            ->with('success', 'Volunteer position deleted successfully.');
    }

    public function showParticipants($id)
    {
        $event = Event::findOrFail($id);
        $this->authorizeEventAccess($event);
        $participants = EventRegistration::where('event_id', $id)
            ->with('user')
            ->get();

        return view('admin.participants', compact('event', 'participants'));
    }

    public function acceptVolunteer($applicationId)
    {
        $application = VolunteerApplication::findOrFail($applicationId);
        $event = Event::findOrFail($application->event_id);
        $this->authorizeEventAccess($event);

        //validasi apakah bisa menerima aplikasi ini
        if (!$application->canMakeFinalDecision() && !$application->isPending()) {
            return back()->withErrors(['accept' => 'Cannot accept this application at current status.']);
        }

        $application->update([
            'status' => 'accepted',
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
            'skkk' => $event->skkk_points_volunteer ?? 0, //dari event, jika tidak ada fallback ke 0
        ]);

        return back()->with('success', 'Volunteer application accepted.');
    }

    public function rejectVolunteer($applicationId)
    {
        $application = VolunteerApplication::findOrFail($applicationId);
        $event = Event::findOrFail($application->event_id);
        $this->authorizeEventAccess($event);

        $application->update([
            'status' => 'rejected',
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now()
        ]);

        return back()->with('success', 'Volunteer application rejected.');
    }

    public function approveParticipant($registrationId)
    {
        $registration = EventRegistration::findOrFail($registrationId);
        $event = Event::findOrFail($registration->event_id);
        $this->authorizeEventAccess($event);

        // Jangan izinkan approve kalau status bukan pending
        if ($registration->status !== 'pending') {
            return back()->withErrors(['status' => 'Peserta tidak dalam status pending.']);
        }

        $registration->update([
            'status' => 'approved',
            'approved_by' => Auth::id()
        ]);

        return back()->with('success', 'Participant registration approved.');
    }


    public function rejectParticipant($registrationId)
    {
        $registration = EventRegistration::findOrFail($registrationId);
        $event = Event::findOrFail($registration->event_id);
        $this->authorizeEventAccess($event);

        $registration->update([
            'status' => 'rejected',
            'approved_by' => Auth::id()
        ]);

        return back()->with('success', 'Participant registration rejected.');
    }

    public function updateEventStatus(Request $request, $id)
    {
        $event = Event::findOrFail($id);
        $status = $request->input('status');

        $validStatuses = ['volunteer_open', 'participant_open', 'closed', 'completed'];

        if (!in_array($status, $validStatuses)) {
            return back()->withErrors(['status' => 'Invalid status.']);
        }

        if ($status === 'completed') {
            // Refresh model biar pasti data terbaru
            $event->refresh();
            // Tambahkan SKKK ke peserta
            foreach ($event->eventRegistrations()->where('status', 'approved')->get() as $registration) {
                $registration->update([
                    'skkk' => $event->skkk_points
                ]);
            }

            // Tambahkan SKKK ke panitia
            foreach ($event->volunteerApplications()->where('status', 'accepted')->get() as $application) {
                $application->update([
                    'skkk' => $event->skkk_points_volunteer ?? $event->skkk_points // fallback kalau belum ada kolom volunteer
                ]);
            }
        }


        $event->update(['registration_phase' => $status]);

        return back()->with('success', 'Event status updated successfully.');
    }

    public function manageUsers(Request $request)
    {
        $role = $request->query('role');
        $query = User::query();

        if (Auth::user()->role === 'admin') {
            $query->where('role', 'user');
        } elseif ($role) {
            $query->where('role', $role);
        }

        $users = $query->orderBy('name')->get();

        // Tambahan aman:
        $admins = User::where('role', 'admin')->with('managedEvents')->get();
        $students = User::where('role', 'user')->get();
        $events = Event::orderBy('name')->get();

        return view('admin.users', compact('admins', 'students', 'events'));
    }


    public function assignAdminToEvent(Request $request)
    {
        if (!Auth::user()->isSuperAdmin()) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'event_ids' => 'required|array',
            'event_ids.*' => 'exists:events,id',
        ]);

        $userId = $request->user_id;

        foreach ($request->event_ids as $eventId) {
            $event = Event::findOrFail($eventId);
            $event->users()->syncWithoutDetaching([$request->user_id]);
        }

        return back()->with('success', 'Admin berhasil ditugaskan ke event(s).');
    }


    public function unassignAdminFromEvent(Request $request)
    {
        if (!Auth::user()->isSuperAdmin()) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'event_id' => 'required|exists:events,id',
        ]);

        $event = Event::findOrFail($request->event_id);
        $event->users()->detach($request->user_id);

        return back()->with('success', 'Admin berhasil dicabut dari event.');
    }

    private function authorizeEventAccess(Event $event)
    {
        if (Auth::user()->isSuperAdmin())
            return;

        if (Auth::user()->isAdmin() && !$event->users->contains(Auth::id())) {
            redirect()->route('admin.dashboard')->with('error', 'Anda tidak punya wewenang untuk event ini.')->send();
        }
    }

    // New method: Schedule Interview
    public function scheduleInterview(Request $request, $applicationId)
    {
        $application = VolunteerApplication::findOrFail($applicationId);
        $event = Event::findOrFail($application->event_id);
        $this->authorizeEventAccess($event);

        // Validate that application is in pending status
        if (!$application->canScheduleInterview()) {
            return back()->withErrors(['interview' => 'Cannot schedule interview for this application.']);
        }

        $data = $request->validate([
            'interview_date' => 'required|date|after:now',
            'interview_time' => 'required|date_format:H:i',
            'interview_location' => 'required|string|max:255',
            'interview_notes' => 'nullable|string|max:1000',
        ]);

        $application->update([
            'status' => 'interview_scheduled',
            'interview_date' => $data['interview_date'],
            'interview_time' => $data['interview_time'],
            'interview_location' => $data['interview_location'],
            'interview_notes' => $data['interview_notes'],
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now()
        ]);

        return back()->with('success', 'Interview scheduled successfully.');
    }

    public function updateInterviewSchedule(Request $request, $applicationId)
    {
        $application = VolunteerApplication::findOrFail($applicationId);
        $event = Event::findOrFail($application->event_id);
        $this->authorizeEventAccess($event);

        if (!$application->isInterviewScheduled()) {
            return back()->withErrors(['interview' => 'Interview not scheduled for this application.']);
        }

        $data = $request->validate([
            'interview_date' => 'required|date|after:now',
            'interview_time' => 'required|date_format:H:i',
            'interview_location' => 'required|string|max:255',
            'interview_notes' => 'nullable|string|max:1000',
        ]);

        $application->update([
            'interview_date' => $data['interview_date'],
            'interview_time' => $data['interview_time'],
            'interview_location' => $data['interview_location'],
            'interview_notes' => $data['interview_notes'],
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now()
        ]);

        return back()->with('success', 'Interview schedule updated successfully.');
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);

        // Optional: Cegah hapus superadmin
        if ($user->role === 'superadmin') {
            return back()->withErrors(['delete' => 'Cannot delete Super Admin.']);
        }

        $user->delete();

        return redirect()->route('admin.users')->with('success', 'User deleted successfully.');
    }

    public function editVolunteerPosition($id)
    {
        $position = VolunteerPosition::findOrFail($id);
        $event = $position->event;
        return view('admin.edit-volunteer-position', compact('position', 'event'));
    }

    public function updateVolunteerPosition(Request $request, $id)
    {
        $request->validate([
            'position_name' => 'required|string|max:255',
            'initial_quota' => 'required|integer|min:1',
            'description' => 'required|string',
            'deadline' => 'required|date',
        ]);

        $position = VolunteerPosition::findOrFail($id);
        $position->update([
            'position_name' => $request->position_name,
            'initial_quota' => $request->initial_quota,
            'description' => $request->description,
            'deadline' => $request->deadline,
        ]);

        return redirect()->route('admin.volunteer-positions', $position->event_id)->with('success', 'Posisi berhasil diperbarui.');
    }

}