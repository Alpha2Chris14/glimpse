<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function listEvents()
    {
        $events = Event::withCount('participants')
            ->get()
            ->map(function ($event) {
                return [
                    'id' => $event->id,
                    'name' => $event->name,
                    'start' => $event->start_time,
                    'end' => $event->end_time,
                    'max_participants' => $event->max_participants,
                    'current_participants' => $event->participants_count,
                    'available_slots' => $event->max_participants - $event->participants_count,
                ];
            });

        return response()->json([
            'data' => $events
        ], 200);
    }

    public function participants($eventId)
    {
        $event = Event::with('participants')->find($eventId);

        if (!$event) {
            return response()->json(['message' => 'Event not found.'], 404);
        }

        $participants = $event->participants->map(function ($participant) {
            return [
                'id' => $participant->id,
                'name' => $participant->name,
                'email' => $participant->email,
                'registered_at' => $participant->pivot->created_at,
            ];
        });

        return response()->json([
            'event' => $event->name,
            'participants' => $participants
        ]);
    }



    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'start_time' => 'required|date|before:end_time',
            'end_time' => 'required|date|after:start_time',
            'max_participants' => 'required|integer|min:1',
        ]);

        $event = Event::create($validated);
        return response()->json(['message' => 'Event created', 'data' => $event], 201);
    }

    public function destroy($id)
    {
        $event = Event::find($id);

        if (!$event) {
            return response()->json(['message' => 'Event not found.'], 404);
        }

        $event->delete();

        return response()->json(['message' => 'Event was deleted using soft deleted.']);
    }

    // 🧹 Restore soft deleted registration
    public function restore($id)
    {
        $registration = Event::onlyTrashed()->findOrFail($id);

        $registration->restore();

        return response()->json(['message' => 'Event was restored successfully.']);
    }

    // Force delete soft deleted registration
    public function forceDelete($id)
    {
        $event = Event::onlyTrashed()->find($id);
        if (!$event) {
            return response()->json(['message' => 'Event not found.'], 404);
        }

        $event->forceDelete();

        return response()->json(['message' => 'Registration permanently deleted.']);
    }

    // List soft deleted registrations
    public function trashed()
    {
        $registrations = Event::onlyTrashed()->get();

        return response()->json($registrations);
    }
}
