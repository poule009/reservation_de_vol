<?php
// app/Events/SeatUpdated.php

namespace App\Events;

use App\Models\Seat;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SeatUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $seat;
    public $flightId;

    /**
     * Create a new event instance.
     */
    public function __construct(Seat $seat)
    {
        $this->seat = $seat;
        $this->flightId = $seat->flight_id;
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): Channel
    {
        return new Channel('flight.' . $this->flightId);
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'seat.updated';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'seat_id' => $this->seat->id,
            'seat_number' => $this->seat->seat_number,
            'status' => $this->seat->status,
            'reservation_id' => $this->seat->reservation_id,
        ];
    }
}