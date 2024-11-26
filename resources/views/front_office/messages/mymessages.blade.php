@extends('layouts.home')
@section('style')

@section('content')
<div class="chat-container">
    <!-- الشريط الجانبي للمحادثات -->
    <div class="sidebar">
        <div class="conversations">
            <ul class="conversations-list chat-list">
                @forelse ($conversations as $conversation)
                    @php
                        $id = (auth()->id() == $conversation->sender_id) 
                            ? $conversation->recipient->id 
                            : $conversation->sender->id;
                        $userImage = (auth()->id() == $conversation->sender_id) 
                            ? $conversation->recipient->image_url 
                            : $conversation->sender->image_url;
                        $userName = (auth()->id() == $conversation->sender_id) 
                            ? $conversation->recipient->first_name . ' ' . $conversation->recipient->last_name
                            : $conversation->sender->first_name . ' ' . $conversation->sender->last_name;
                    @endphp
                    <li class="conversation-item">
                        <a href="{{ route('web.send_message', $id) }}" class="conversation-link">
                            <img src="{{ $userImage }}" alt="user" class="user-avatar">
                            <span class="user-name">{{ $userName }}</span>
                        </a>
                    </li>
                @empty
                    <li class="no-conversations">{{ __('messages.there_no_conversations') }}</li>
                @endforelse
            </ul>
        </div>
    </div>

    <!-- منطقة الشات الرئيسية -->
    <div class="chat-area">
        <div class="chat-header">
            <h4>{{ __('messages.chat_messages') }}</h4>
        </div>
        <div class="chat-content">
            <ul class="messages-list">
                @forelse ($mymessages as $item)
                    @php
                        $isCurrentUser = $item->sender_id == auth()->id();
                        $sender = !$isCurrentUser ? App\Models\User::find($item->sender_id) : null;
                    @endphp
                    <li class="message-item {{ $isCurrentUser ? 'sent' : 'received' }}">
                        @if (!$isCurrentUser && $sender)
                            <img src="{{ $sender->image_url }}" alt="user" class="message-avatar">
                        @endif
                        <div class="message-content">
                            <span class="message-text">{{ $item->message }}</span>
 							@if(isset($item->image))
                                                        	<img width="250px" height="250px" src="{{ asset('messages/' . $item->image ) }}" alt="user">
                                                    	@endif
                            <span class="message-time">{{ $item->created_at->shortAbsoluteDiffForHumans() }}</span>
                        </div>
                    </li>
                @empty
                    <li class="no-messages">{{ __('messages.no_messages') }}</li>
                @endforelse
            </ul>
        </div>
         <?php
                        $current_url = url()->current();
                        $url = explode('/', $current_url);
                        $reci = (int) end($url);
                        ?>
                        <form action="{{ route('messages.store') }}" method="post" class="chat-form"  enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="recipient_id" value="{{ $reci }}"  >
                            <div class="form-group">
                                <textarea name="message" id="" cols="100" rows="5" class="message-input" ></textarea>
                                <input  placeholder="Type and enter" class="form-control border-0" type="file" name="image" placeholder="Image">
                                {{-- <input type="text" name="message" placeholder="{{ __('messages.type_and_enter') }}" class="message-input"> --}}
                                <button type="submit" class="send-button">{{ __('messages.send') }}</button>
                            </div>
                        </form>
                        
                        <!--<form action="{{ route('messages.store') }}" method="post"  enctype="multipart/form-data" >-->
                        <!--    @csrf-->
                        <!--    <input type="hidden" name="recipient_id" value="{{ $reci }}">-->
                        <!--    <div class="card-body border-top">-->
                        <!--        <div class="row">-->
                        <!--            <div class="col-9">-->
                        <!--                <div class="input-field m-t-0 m-b-0">-->
                        <!--                    <input id="textarea1" placeholder="Type and enter"-->
                        <!--                        class="form-control border-0" type="text" name="message">-->
                        <!--                    <input  placeholder="Type and enter"-->
                        <!--                        class="form-control border-0" type="file" name="image" placeholder="Image">-->
                        <!--                </div>-->
                        <!--            </div>-->
                        <!--            <div class="col-3">-->
                        <!--                <button class=" btn-lg btn-cyan float-right text-white" type="submit">-->
                        <!--                    {{ __('messages.send') }}-->
                        <!--                </button>-->
                        <!--            </div>-->
                        <!--        </div>-->
                        <!--    </div>-->
                        <!--</form>-->
    </div>
</div>

<style>
    .chat-container {
        display: flex;
        height: 100vh;
        overflow: hidden;
        max-width: 100vw;
    }
    .sidebar {
        flex: 1;
        max-width: 20%;
        border-right: 1px solid #ccc;
        overflow-y: auto;
        padding: 10px;
        background: #fdca3d;
    }
    .conversations-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .conversation-item {
        margin-bottom: 10px;
    }
    .conversation-link {
        display: flex;
        align-items: center;
        text-decoration: none;
        padding: 10px;
        border-radius: 4px;
        transition: background-color 0.3s;
    }
    .conversation-link:hover {
        background-color: #e9e9e9;
     }
    .user-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        margin-right: 10px;
    }
    .user-name {
        font-size: 16px;
        color: #ffffff;
    }
    .user-name:hover {
        color: black;
    }
    .chat-area {
        flex: 3;
        display: flex;
        flex-direction: column;
        padding: 10px;
        overflow: hidden;
    }
    .chat-header {
        border-bottom: 1px solid #ccc;
        padding-bottom: 10px;
        margin-bottom: 10px;
    }
    .chat-content {
        flex: 1;
        overflow-y: auto;
    }
    .messages-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .message-item {
        display: flex;
        margin-bottom: 10px;
    }
    .message-item.sent {
        justify-content: flex-end;
    }
    .message-item.received {
        justify-content: flex-start;
    }
    .message-avatar {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        margin-right: 10px;
    }
    .message-content {
        max-width: 70%;
        background: #f1f1f1;
        padding: 10px;
        border-radius: 4px;
    }
    .message-text {
        display: block;
    }
    .message-time {
        font-size: 12px;
        color: #999;
        margin-top: 5px;
    }
    .chat-form {
        display: flex;
        border-top: 1px solid #ccc;
        padding: 10px;
    }
    .message-input {
        flex: 1;
        padding: 10px;
        /* width: 500px; */
        border: 1px solid #ccc;
        border-radius: 4px;
        margin-right: 10px;
    }
    .send-button {
        padding: 10px 15px;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }
    .send-button:hover {
        background-color: #0056b3;
    }

    @media (max-width: 768px) {
        .sidebar {
            max-width: 100%;
            flex: none;
            height: auto;
            border-right: none;
            border-bottom: 1px solid #ccc;
        }
        .chat-area {
            max-width: 100%;
            flex: none;
        }
    }
</style>

@endsection
@section('script')
    <script src="{{ asset('css/chat/perfect-scrollbar.jquery.min.js') }}"></script>


@endsection