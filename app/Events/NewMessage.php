<?php

namespace App\Events;

use App\Message;
use App\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Support\Facades\Auth;

class NewMessage implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Message $message)
    {
        //
        $this->message = $message;
        $this->message->from_name = User::where('id', $this->message->from)->first()->name;
        $this->message->to_name = User::where('id', $this->message->to)->first() ? User::where('id', $this->message->to)->first()->name : '';
        if ($this->message->from == auth()->id()) {
            $this->message->status = 's';
        }
        if ($this->message->to == auth()->id()) {
            $this->message->status = 'r';
        }
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {


        return new Channel('home.' . $this->message->to);
    }

    public function broadcastWith()
    {
        $s = '';
        if ($this->message->from == auth()->id()) {
            $s = 's';
        } else {
            $s = 'r';
        }
        return [
            'id' =>$this->message->id,
            'text' => $this->message->text,
            'from_name' =>  User::findOrfail($this->message->from)->name,
            'to_name' =>  User::findOrfail($this->message->to) ? User::findOrfail($this->message->to)->name : '',
            'ststus' => $s
        ];
    }
}
