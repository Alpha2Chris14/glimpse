<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Participant extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'name',
        'email',
    ];


    // public function events()
    // {
    //     return $this->belongsToMany(Event::class)->withTimestamps();
    // }
    public function events()
    {
        return $this->belongsToMany(Event::class, 'event_participant', 'participant_id', 'event_id')
            ->withTimestamps();
    }
}
