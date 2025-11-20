<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\VolunteerController;
use Illuminate\Support\Facades\Route;

// Redirect root to home
Route::get('/', function () {
    return redirect()->route('home');
});

// Public routes
Route::get('/home', [EventController::class, 'index'])->name('home');
Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/{id}', [EventController::class, 'show'])->name('events.show');

// Authentication routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected routes (require authentication)
Route::middleware('auth')->group(function () {
    // User dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Event registration routes
    Route::post('/events/{id}/register', [EventController::class, 'register'])->name('events.register');
    Route::get('/events/{id}/participant-form', [EventController::class, 'showParticipantForm'])->name('events.participant-form');
    Route::post('/events/{id}/register-participant', [EventController::class, 'registerParticipant'])->name('events.register-participant');

    // Volunteer registration routes
    Route::get('/volunteer/{eventId}/register', [VolunteerController::class, 'showRegistrationForm'])->name('volunteer.register');
    Route::post('/volunteer/{eventId}/register', [VolunteerController::class, 'register'])->name('volunteer.register-post');

    // Admin routes (protected by role checking in controller)
    Route::prefix('admin')->name('admin.')->group(function () {
        // Dashboard
        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
        Route::get('/users', [AdminController::class, 'manageUsers'])->name('users');

        // User management
        Route::delete('/users/{id}', [AdminController::class, 'deleteUser'])->name('delete-user');
        Route::post('/assign-event', [AdminController::class, 'assignAdminToEvent'])->name('assign-event');


        // Event management
        Route::get('/create-event', [AdminController::class, 'showCreateEventForm'])->name('create-event-form');
        Route::post('/create-event', [AdminController::class, 'createEvent'])->name('create-event');
        Route::post('/events/{id}/status', [AdminController::class, 'updateEventStatus'])->name('update-event-status');
        Route::get('/events/{id}/edit', [AdminController::class, 'editEvent'])->name('events.edit');
        Route::put('/events/{id}', [AdminController::class, 'updateEvent'])->name('events.update');


        // Volunteer management
        Route::get('/events/{id}/manage-volunteers', [AdminController::class, 'manageVolunteers'])->name('manage-volunteers');
        Route::get('/events/{id}/volunteer-positions', [AdminController::class, 'showVolunteerPositions'])->name('volunteer-positions');
        Route::post('/events/{id}/volunteer-positions', [AdminController::class, 'createVolunteerPosition'])->name('create-volunteer-position');
        Route::delete('/volunteer-positions/{id}', [AdminController::class, 'deleteVolunteerPosition'])->name('delete-volunteer-position');

        Route::get('/volunteer-positions/{id}/edit', [AdminController::class, 'editVolunteerPosition'])->name('edit-volunteer-position');
        Route::put('/volunteer-positions/{id}', [AdminController::class, 'updateVolunteerPosition'])->name('update-volunteer-position');



        // Route::post('/volunteers/{applicationId}/approve', [AdminController::class, 'approveVolunteer'])->name('approve-volunteer');
        Route::post('/volunteers/{applicationId}/reject', [AdminController::class, 'rejectVolunteer'])->name('reject-volunteer');
        Route::post('/volunteer/{application}/schedule-interview', [AdminController::class, 'scheduleInterview'])->name('schedule-interview');
        Route::post('/volunteer/{application}/accept', [AdminController::class, 'acceptVolunteer'])->name('accept-volunteer');
        Route::post('/volunteer/{application}/update-interview', [AdminController::class, 'updateInterviewSchedule'])->name('update-interview');

        // Participant management
        Route::get('/events/{id}/participants', [AdminController::class, 'showParticipants'])->name('participants');
        Route::post('/participants/{registrationId}/approve', [AdminController::class, 'approveParticipant'])->name('approve-participant');
        Route::post('/participants/{registrationId}/reject', [AdminController::class, 'rejectParticipant'])->name('reject-participant');
    });
});