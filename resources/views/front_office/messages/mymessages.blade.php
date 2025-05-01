@extends('layouts.home')

@section('style')
<style>
    .chat-container {
        height: 100vh;
        overflow: hidden;
        background: #f4f7f9;
        display: flex;
        flex-direction: column;
    }

    .chat-area {
        flex: 1;
        padding: 20px;
        display: flex;
        flex-direction: column;
        background: #fff;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        height: 100%;
    }

    .chat-header {
        border-bottom: 2px solid #eee;
        margin-bottom: 20px;
        margin-top: 20px;
        padding-bottom: 10px;
        text-align: center;
    }

    .chat-content {
        flex: 1;
        overflow-y: auto;
        padding-right: 10px;
        height: 100%;
        display: flex;
        flex-direction: column-reverse;
        gap: 20px;
    }

    .messages-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .message-item {
        margin-bottom: 15px;
        display: flex;
        gap: 12px;
        padding: 10px;
    }

    .message-item.sent {
        justify-content: flex-end;
    }

    .message-item.received {
        justify-content: flex-start;
    }

    .message-content {
        max-width: 75%;
        background-color: #f1f1f1;
        padding: 12px 18px;
        border-radius: 20px;
        box-shadow: 0px 2px 8px rgba(0, 0, 0, 0.1);
        font-size: 14px;
        line-height: 1.5;
        word-wrap: break-word;
        position: relative;
    }

    .message-item.sent .message-content {
        background-color: #007bff;
        color: #fff;
        box-shadow: 0px 2px 8px rgba(0, 0, 0, 0.2);
    }

    .message-item.received .message-content {
        background-color: #e1e1e1;
        color: #333;
    }

    .message-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
    }

    .message-time {
        font-size: 12px;
        color: #888;
        position: absolute;
        bottom: -18px;
        right: 10px;
    }

    .message-item .message-text {
        word-wrap: break-word;
    }

    .chat-form {
        display: flex;
        flex-direction: row;
        gap: 15px;
        padding: 15px;
        border-top: 2px solid #ddd;
    }

    .message-input {
        flex: 1;
        padding: 12px;
        border: 2px solid #ddd;
        border-radius: 25px;
        background-color: #f9f9f9;
        resize: none;
        min-height: 60px;
    }

    .send-button {
        background-color: #007bff;
        color: white;
        border: none;
        height: 50px;
        padding: 12px 18px;
        border-radius: 25px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .send-button:hover {
        background-color: #0056b3;
    }

    /* تحسين التفاعل مع الصور داخل الرسائل */
    .message-content img {
        max-width: 100%;
        border-radius: 10px;
        margin-top: 10px;
    }

    /* تنسيق الرسائل عند التمرير */
    .message-item.sent:hover .message-content,
    .message-item.received:hover .message-content {
        box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.2);
        transition: all 0.3s;
    }

</style>
@endsection

@section('content')
<div class="chat-container">

    <!-- منطقة الشات الرئيسية -->
    <div class="chat-area">
        @php
            $current_url = url()->current();
            $urlParts = explode('/', $current_url);
            $recipientId = (int) end($urlParts);
            $recipient = App\Models\User::find($recipientId);
        @endphp

        <div class="chat-header">
            @if($recipient)
                <div style="display: flex; align-items: center; gap: 10px;">
                    <img src="{{ asset('storage/' . ($recipient->image ?? 'user.png')) }}"
                         alt="user"
                         class="message-avatar"
                         onerror="this.onerror=null;this.src='{{ asset('storage/users/user.png') }}';">
                    <h4>{{ $recipient->first_name }} {{ $recipient->last_name }}</h4>
                </div>
            @else
                <h4>{{ __('messages.chat_messages') }}</h4>
            @endif
        </div>

        <div class="chat-content">
            <ul class="messages-list">
                @foreach ($messages as $message)
                    @php
                        $isCurrentUser = $message->sender_id == auth()->id();
                        $sender = $message->sender; // Assuming sender relationship exists in your model
                    @endphp
                    <li class="message-item {{ $isCurrentUser ? 'sent' : 'received' }}">
                        <div style="display: flex; gap: 12px; align-items: flex-start;">
                            <!-- صورة المرسل أو المستقبل -->
                            <img src="{{ asset('storage/' . ($isCurrentUser ? auth()->user()->image : $recipient->image) ?? 'user.png') }}"
                                 alt="user"
                                 class="message-avatar"
                                 onerror="this.onerror=null;this.src='{{ asset('storage/users/user.png') }}';">
                            <div class="message-content">
                                <span class="message-text">{{ $message->message }}</span>

                                @if(isset($message->image))
                                    <div style="margin-top: 10px;">
                                        <img width="250px" height="250px" src="{{ asset('messages/' . $message->image) }}" alt="message image">
                                    </div>
                                @endif

                                <span class="message-time">{{ $message->created_at->shortAbsoluteDiffForHumans() }}</span>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>

        <form action="{{ route('messages.store') }}" method="post" class="chat-form" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="recipient_id" value="{{ $recipientId }}">
            <div class="form-group" style="flex: 1; display: flex;">
                <textarea name="message" rows="3" class="message-input" placeholder="{{ __('messages.type_and_enter') }}"></textarea>
                <input type="file" name="image" style="display: none;" id="file-upload">
                <label for="file-upload" class="send-button" style="background-color: #28a745;">📎</label>
                <button type="submit" class="send-button">{{ __('messages.send') }}</button>
            </div>
        </form>
    </div>
</div>
@endsection
