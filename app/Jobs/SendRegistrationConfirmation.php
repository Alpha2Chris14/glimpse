<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Mail\RegistrationConfirmationMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendRegistrationConfirmation implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $participant;
    protected $event;

    public function __construct($participant, $event)
    {
        $this->participant = $participant;
        $this->event = $event;
    }

    public function handle()
    {
        // Mail::to($this->participant->email)
        //     ->send(new RegistrationConfirmationMail($this->participant, $this->event));
        Mail::raw("Hello {$this->participant->name}, you have successfully registered for the event: {$this->event->title}.", function ($message) {
            $message->to($this->participant->email)
                ->subject('Event Registration Confirmation');
        });
    }
}
