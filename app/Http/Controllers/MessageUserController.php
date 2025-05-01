<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Message;
use App\Models\Conversation;
use Illuminate\Http\Request;
use App\Services\MessageServices;

class MessageUserController extends Controller
{
    public $message_service;

    public function __construct(MessageServices $message_service)
    {
        $this->message_service = $message_service;
    }
    public function send($id){

        $recipient = User::findOrFail($id);
        $sender = auth()->user();

        $messages = Message::where('sender_id' , $sender->id)->orWhere('recipient_id' , $sender->id)->get();
        $mymessages = Message::where(function($query) use ($sender, $id) {
            $query->where('sender_id', $sender->id)
                  ->where('recipient_id', $id);
        })
        ->orWhere(function($query) use ($sender, $id) {
            $query->where('sender_id', $id)
                  ->where('recipient_id', $sender->id);
        })
        ->orderBy('created_at', 'asc')->get();


        $conversations = Conversation::where(function($query) use ($sender){
            $query->where('sender_id' , $sender->id);
        })->orWhere(function($query) use ($sender){
            $query->where('recipient_id' , $sender->id);
        })->orderBy('created_at', 'asc')->get();

        return view('front_office.messages.mymessages' ,compact('messages' , 'sender' , 'recipient' , 'mymessages' , 'conversations'));
    }


public function store(Request $request)
{
    $request->validate([
        'recipient_id' => 'required',
        'message' => 'required',
    ]);

    $sender = auth()->user();
    $data = $request->all();
    $data['sender_id'] = $sender->id;
    $id = $request->recipient_id;

    // البحث عن المحادثة الحالية أو إنشاؤها
    $conversation = Conversation::where(function($query) use ($sender, $id) {
            $query->where('sender_id', $sender->id)
                  ->where('recipient_id', $id);
        })
        ->orWhere(function($query) use ($sender, $id) {
            $query->where('sender_id', $id)
                  ->where('recipient_id', $sender->id);
        })->first();

    // في حالة عدم وجود محادثة، إنشاء واحدة جديدة
    if (!$conversation) {
        $conversation = Conversation::create([
            'sender_id' => $sender->id,
            'recipient_id' => $request->recipient_id,
        ]);
    }

    if ($request->hasFile('image')) {
        $image = $request->file('image');
        $imageName = time() . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('messages'), $imageName);

    }

    $is_create = Message::create([
        'conversation_id' => $conversation->id, // استخدم $conversation->id
        'message' => $request->message,
        'sender_id' => $sender->id,
        'recipient_id' => $request->recipient_id,
        'image' => $imageName ?? null,
    ]);

    return redirect()->back();
}
}
