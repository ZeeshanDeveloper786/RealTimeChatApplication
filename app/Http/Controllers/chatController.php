<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\message;
use App\Models\User;
use App\Events\messageSent;
use Illuminate\Support\Facades\Event;

class chatController extends Controller
{
    //
    public function fetchMessage(Request $request){
        // dd($request->active_id);
        //  $messages = message::with('user')->get();
        $user = auth()->user();
        $messages = message::with('user')->whereIn('reciver_id', [$request->active_id,$user['id']])->whereIn('user_id', [$request->active_id,$user['id']])->get();
         return response()->json($messages);
    }

    public function sendMessage(Request $request){
        $message = auth()->user()->message()->create([
            'message' => $request->message,
            'reciver_id' => $request->active_id
        ]);
        \Log::debug($message->user);

        Event::dispatch(new messageSent($message));
        // broadcast(new messageSent($message));
        \Log::debug(3);

        return ['status' => 'success'];
    }
    
}
