<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Event extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'name',
        'start_time',
        'end_time',
        'max_participants'
    ];

    // public function registrations()
    // {
    //     return $this->belongsToMany(Participant::class, 'event_participant', 'event_id', 'participant_id')
    //         ->withTimestamps();
    // }

    public function participants()
    {
        return $this->belongsToMany(Participant::class, 'event_participant', 'event_id', 'participant_id')
            ->withTimestamps();
    }
}
