<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Message;
use App\Models\Conversation;
use Illuminate\Http\Request;
use App\Services\MessageServices;
use Illuminate\Support\Facades\Storage;

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

        // ابحث عن المحادثة بين المستخدمين أو أنشئ واحدة جديدة
        $conversation = Conversation::where(function($query) use ($sender, $id) {
                $query->where('sender_id', $sender->id)
                      ->where('recipient_id', $id);
            })
            ->orWhere(function($query) use ($sender, $id) {
                $query->where('sender_id', $id)
                      ->where('recipient_id', $sender->id);
            })->first();

        if (!$conversation) {
            $conversation = Conversation::create([
                'sender_id' => $sender->id,
                'recipient_id' => $recipient->id,
            ]);
        }

        // جلب الرسائل فقط بين المستخدمين
        $messages = Message::where('conversation_id', $conversation->id)
            ->orderBy('created_at', 'asc')
            ->get();

        // جلب كل المحادثات الخاصة بالمستخدم الحالي للـ sidebar
        $conversations = Conversation::where(function($query) use ($sender){
            $query->where('sender_id' , $sender->id)
                  ->orWhere('recipient_id' , $sender->id);
        })->orderBy('created_at', 'asc')->get();

        return view('front_office.messages.mymessages', compact('messages', 'sender', 'recipient', 'conversations'));
    }


public function store(Request $request)
{
    $request->validate([
        'recipient_id' => 'required',
        'message' => 'nullable|string',
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

    $imageName = null;
    $voiceNotePath = null;

    // Handle image upload
    if ($request->hasFile('image')) {
        $image = $request->file('image');
        $imageName = time() . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('messages'), $imageName);
    }

    // Handle voice note (base64 encoded audio)
    if ($request->filled('voice_note_data')) {
        $voiceData = $request->input('voice_note_data');
        // Remove the data:audio/wav;base64, part
        $voiceData = preg_replace('/^data:audio\/(wav|mpeg|ogg);base64,/', '', $voiceData);
        $voiceData = base64_decode($voiceData);

        $voiceFileName = 'messages/voice_notes/' . uniqid() . '.wav';
        Storage::disk('public')->put($voiceFileName, $voiceData);
        $voiceNotePath = $voiceFileName;
    }

    $messageData = [
        'conversation_id' => $conversation->id,
        'message' => $request->message ?? '',
        'sender_id' => $sender->id,
        'recipient_id' => $request->recipient_id,
    ];

    // Add image and voice note if they exist
    if ($imageName) {
        $messageData['image'] = $imageName;
    }
    if ($voiceNotePath) {
        $messageData['voice_note'] = $voiceNotePath;
    }

    $is_create = Message::create($messageData);

    return redirect()->back();
}
}
