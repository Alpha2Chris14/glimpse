<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Event;
use App\Models\Participant;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ParticipantTest extends TestCase
{
    use RefreshDatabase;

    public function test_participant_can_register_for_event()
    {
        $event = Event::factory()->create(['max_participants' => 5]);
        $participant = Participant::factory()->create();

        $response = $this->postJson("/api/events/{$event->id}/register", [
            'participant_id' => $participant->id,
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('event_registrations', [
            'event_id' => $event->id,
            'participant_id' => $participant->id,
        ]);
    }
}
