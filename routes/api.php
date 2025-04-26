<?php

use App\Http\Controllers\EventController;
use App\Http\Controllers\ParticipantController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix('events')->group(function () {
    Route::get('/', [EventController::class, 'listEvents']);  // List all events with available slots
    Route::get('{event}/participants', [EventController::class, 'participants']);
    Route::post('/', [EventController::class, 'store']);  // Create a new event
    Route::delete('{id}', [EventController::class, 'destroy']);  // Soft delete an event
    Route::post('{id}/restore', [EventController::class, 'restore']);  // Restore a soft deleted event
    Route::delete('{id}/force', [EventController::class, 'forceDelete']);  // Permanently delete an event
    Route::get('trashed', [EventController::class, 'trashed']);  // List all soft deleted events
});

Route::prefix('participants')->group(function () {
    Route::post('/', [ParticipantController::class, 'store']); // Create participant
    Route::post('/register', [ParticipantController::class, 'register']); // Register to event
    Route::delete('/{id}', [ParticipantController::class, 'destroy']); // Soft delete participant
    Route::get('/trashed/list', [ParticipantController::class, 'trashed']); // List soft deleted
    Route::post('/{id}/restore', [ParticipantController::class, 'restore']); // Restore soft deleted
    Route::delete('/force-delete/{id}', [ParticipantController::class, 'forceDelete']); // Force delete soft deleted
});
