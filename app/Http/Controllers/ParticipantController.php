<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Jobs\SendRegistrationConfirmation;
use App\Models\Event;
use App\Models\Participant;
use Illuminate\Http\Request;

class ParticipantController extends Controller
{
    /**
     * Store a newly created participant.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:participants,email'],
        ]);

        $participant = Participant::create($validated);

        return response()->json([
            'message' => 'Participant created successfully.',
            'data' => $participant
        ], 201);
    }

    /**
     * Register a participant to an event.
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'participant_id' => 'required|exists:participants,id',
            'event_id' => 'required|exists:events,id',
        ]);

        $event = Event::findOrFail($validated['event_id']);
        $participant = Participant::findOrFail($validated['participant_id']);

        // Check if full
        if ($event->participants()->count() >= $event->max_participants) {
            return response()->json(['message' => 'Event is full'], 409);
        }

        // Check time conflict
        $conflict = $participant->events()
            ->where(function ($q) use ($event) {
                $q->whereBetween('start_time', [$event->start_time, $event->end_time])
                    ->orWhereBetween('end_time', [$event->start_time, $event->end_time])
                    ->orWhere(function ($q2) use ($event) {
                        $q2->where('start_time', '<=', $event->start_time)
                            ->where('end_time', '>=', $event->end_time);
                    });
            })->exists();

        if ($conflict) {
            return response()->json(['message' => 'Time conflict with another event'], 409);
        }

        // Attach participant
        $event->participants()->attach($participant->id);

        // Dispatch confirmation email job
        dispatch(new SendRegistrationConfirmation($participant, $event));

        return response()->json(['message' => 'Participant registered successfully and confirmation email sent.'], 200);
    }

    public function destroy($id)
    {
        $participant = Participant::find($id);

        if (!$participant) {
            return response()->json(['message' => 'Participant not found.'], 404);
        }

        $participant->delete();

        return response()->json(['message' => 'Participant was deleted using soft deleted.']);
    }

    public function restore($id)
    {
        $participant = Participant::onlyTrashed()->findOrFail($id);

        $participant->restore();

        return response()->json(['message' => 'Participant restored successfully.']);
    }

    public function forceDelete($id)
    {
        $participant = Participant::onlyTrashed()->find($id);

        if (!$participant) {
            return response()->json(['message' => 'Participant not found.'], 404);
        }

        $participant->forceDelete();

        return response()->json(['message' => 'Participant permanently deleted.']);
    }

    public function trashed()
    {
        $participants = Participant::onlyTrashed()->get();

        return response()->json($participants);
    }
}
