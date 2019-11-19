<?php

namespace App\Http\Controllers;

use App\Events\NewMessage;
use App\User;
use App\Message;
use App\SaveMessage;
use Illuminate\Http\Request;

class ApiMessageController extends Controller
{
   
    //
    public function getUser(Request $request)
    {
        # code...
        $users = User::all();
        return response()->json($users);
    }
    public function send(Request $request)
    {
        # code...
 // return response()->json($request);

        if ($request['id'] != null) {
            foreach ($request->id as $Id) {
                $message = new Message();
                $message->from = auth()->id();
                $message->to = $Id;
                $message->text = $request->meg;

                $message->save();
                event(new NewMessage($message));
            }
        } else {
            $message = new Message();
            $message->from = auth()->id();
            $message->to = 0;
            $message->text = $request->meg;
            $message->save();
        }


        $message->from_name = User::where('id', $message->from)->first()->name;
        // $message->to_name = User::where('id', $message->to)->first() ? User::where('id', $message->to)->first()->name : '';
        if ($message->from == auth()->id()) {
            $message->status = 's';
        }
        if ($message->to == auth()->id()) {
            $message->status = 'r';
        }
        return response()->json($message);
    }
    public function get(Request $request)
    {
        # code...

        $user1 = Message::where('from', auth()->id())->pluck('id')->toArray();
        $user2 = Message::where('to', auth()->id())->pluck('id')->toArray();

        $user = array_merge($user1, $user2);

        $messages = Message::whereIn('id', $user)->get();
        // dd($messages);
        foreach ($messages as $mess) {
            $mess->from_name = User::where('id', $mess->from)->first()->name;
            $mess->to_name = User::where('id', $mess->to)->first() ? User::where('id', $mess->to)->first()->name : '';
            if ($mess->from == auth()->id()) {
                $mess->status = 's';
            }
            if ($mess->to == auth()->id()) {
                $mess->status = 'r';
            }
        }


        return response()->json($messages);
    }
     public function user(Request $request)
    {
        $user =auth()->id();
        return response()->json($user);
    }
}
