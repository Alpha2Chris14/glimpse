<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EventTest extends TestCase
{
    use RefreshDatabase;

    public function test_event_creation()
    {
        $event = Event::factory()->create();

        $this->assertDatabaseHas('events', [
            'id' => $event->id,
        ]);
    }
}
