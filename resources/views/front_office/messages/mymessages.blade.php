@extends('layouts.home')

@section('style')
<style>
    {{--  :root {
        --primary-color: #4361ee;
        --secondary-color: #3f37c9;
        --accent-color: #4895ef;
        --danger-color: #f72585;
        --success-color: #4cc9f0;
        --light-color: #f8f9fa;
        --dark-color: #212529;
        --gray-color: #6c757d;
        --border-radius: 12px;
        --box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }  --}}

    .chat-container {
        height: 100vh;
        display: flex;
        flex-direction: column;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .chat-layout {
        display: flex;
        flex: 1;
        height: 100vh;
    }

    .conversations-sidebar {
        width: 300px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-right: 1px solid rgba(255, 255, 255, 0.1);
        display: flex;
        flex-direction: column;
        overflow-y: auto;
    }

    .sidebar-header {
        color: white;
        padding: 20px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        background: rgba(255, 255, 255, 0.1);
    }

    .sidebar-header h5 {
        margin: 0 0 15px 0;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .search-container {
        position: relative;
    }

    .search-input {
        width: 100%;
        padding: 10px 35px 10px 15px;
        border: none;
        border-radius: 20px;
        background: rgba(255, 255, 255, 0.2);
        color: white;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .search-input::placeholder {
        color: rgba(255, 255, 255, 0.7);
    }

    .search-input:focus {
        outline: none;
        background: rgba(255, 255, 255, 0.3);
        box-shadow: 0 0 0 2px rgba(255, 255, 255, 0.2);
    }

    .search-icon {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: rgba(255, 255, 255, 0.7);
        font-size: 14px;
    }

    .conversations-list {
        flex: 1;
        overflow-y: auto;
        padding: 10px 0;
    }

    .conversation-item {
        display: flex;
        align-items: center;
        padding: 15px 20px;
        cursor: pointer;
        transition: all 0.3s ease;
        border-radius: 10px;
        margin: 5px 10px;
        position: relative;
    }

    .conversation-item:hover {
        background: rgba(255, 255, 255, 0.15);
        transform: translateX(5px);
    }

    .conversation-item.active {
        background: rgba(255, 255, 255, 0.2);
        border-left: 4px solid #fff;
    }

    .conversation-avatar {
        position: relative;
        margin-right: 15px;
    }

    .conversation-avatar img {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid rgba(255, 255, 255, 0.3);
    }

    .online-indicator {
        position: absolute;
        bottom: 2px;
        right: 2px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        border: 2px solid #667eea;
        background-color: #28a745;
    }

    .online-indicator.offline {
        background-color: #6c757d;
    }

    .conversation-info {
        flex: 1;
        min-width: 0;
    }

    .conversation-name {
        font-weight: 600;
        color: white;
        font-size: 14px;
        margin-bottom: 4px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .conversation-preview {
        font-size: 12px;
        color: rgba(255, 255, 255, 0.8);
        margin-bottom: 2px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .conversation-time {
        font-size: 11px;
        color: rgba(255, 255, 255, 0.6);
    }

    .chat-area {
        flex: 1;
        display: flex;
        flex-direction: column;
        padding: 0;
        overflow: hidden;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
    }

    .chat-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 15px 20px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        display: flex;
        align-items: center;
        gap: 15px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        z-index: 10;
    }

    .chat-header-info h5 {
        color: white;
        margin: 0;
        font-weight: 600;
    }

    .chat-header-info small {
        color: rgba(255, 255, 255, 0.8);
    }

    .chat-header-actions {
        display: flex;
        gap: 10px;
    }

    .chat-content {
        flex: 1;
        overflow-y: auto;
        padding: 20px;
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        display: flex;
        flex-direction: column-reverse;
    }

    .messages-list {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .message-item {
        display: flex;
        max-width: 80%;
        animation: fadeInUp 0.3s ease-out;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .message-item.sent {
        align-self: flex-end;
    }

    .message-item.received {
        align-self: flex-start;
    }

    .message-content {
        padding: 15px 18px;
        border-radius: 20px;
        position: relative;
        word-wrap: break-word;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .message-item.sent .message-content {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-bottom-right-radius: 8px;
    }

    .message-item.received .message-content {
        background: white;
        color: #333;
        border-bottom-left-radius: 8px;
        border: 1px solid #e9ecef;
    }

    .message-avatar {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid rgba(255, 255, 255, 0.3);
    }

    .message-text {
        font-size: 15px;
        line-height: 1.4;
        margin-bottom: 8px;
    }

    .message-meta {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-top: 8px;
        font-size: 12px;
    }

    .message-item.sent .message-meta {
        justify-content: flex-end;
        color: rgba(255, 255, 255, 0.8);
    }

    .message-item.received .message-meta {
        justify-content: flex-start;
        color: #6c757d;
    }

    .message-actions {
        display: none;
        position: absolute;
        top: -10px;
        right: -10px;
        background: white;
        border-radius: 50%;
        width: 28px;
        height: 28px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        z-index: 2;
    }

    .message-item:hover .message-actions {
        display: flex;
    }

    .message-delete-btn {
        background: var(--danger-color);
        color: white;
        border: none;
        border-radius: 50%;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        margin: auto;
        font-size: 12px;
    }

    .chat-form {
        background: white;
        padding: 20px;
        border-top: 1px solid #e9ecef;
        display: flex;
        gap: 15px;
        align-items: flex-end;
        box-shadow: 0 -4px 15px rgba(0, 0, 0, 0.1);
    }

    .message-input-container {
        flex: 1;
        position: relative;
    }

    .message-input {
        width: 100%;
        padding: 15px 60px 15px 20px;
        border: 2px solid #e9ecef;
        border-radius: 25px;
        resize: none;
        min-height: 50px;
        max-height: 120px;
        background-color: #f8f9fa;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .message-input:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        background-color: white;
    }

    .attachment-btn, .send-btn {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        border: none;
        transition: all 0.2s;
    }

    .attachment-btn {
        background-color: #e9ecef;
        color: var(--gray-color);
    }

    .attachment-btn:hover {
        background-color: #dee2e6;
    }

    .send-btn {
        background-color: var(--primary-color);
        color: white;
    }

    .send-btn:hover {
        background-color: var(--secondary-color);
    }

    .send-btn:disabled {
        background-color: #6c757d;
        cursor: not-allowed;
        opacity: 0.6;
    }

    /* Voice recording styles */
    .voice-controls {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-top: 10px;
    }

    .voice-btn {
        background-color: #dc3545;
        color: white;
        border: none;
        height: 40px;
        width: 40px;
        border-radius: 50%;
        cursor: pointer;
        transition: background-color 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .voice-btn:hover {
        background-color: #c82333;
    }

    .voice-btn:disabled {
        background-color: #6c757d;
        cursor: not-allowed;
    }

    .voice-btn.recording {
        background-color: #dc3545;
        animation: pulse 1s infinite;
    }

    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.1); }
        100% { transform: scale(1); }
    }

    .voice-timer {
        font-size: 12px;
        color: #dc3545;
        font-weight: bold;
    }

    .voice-playback {
        margin-top: 10px;
        width: 100%;
    }

    .voice-status {
        font-size: 12px;
        color: #dc3545;
        margin-bottom: 5px;
    }

    /* Audio message styling */
    .audio-message {
        margin-top: 10px;
    }

    .audio-message audio {
        border-radius: 25px;
        background: rgba(255, 255, 255, 0.1);
    }

    /* Voice recorder styles */
    .voice-recorder {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 15px;
        background-color: #f8f9fa;
        border-radius: 10px;
        margin-top: 10px;
    }

    .voice-recorder-btn {
        background-color: #dc3545;
        color: white;
        border: none;
        height: 50px;
        width: 50px;
        border-radius: 50%;
        cursor: pointer;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .voice-recorder-btn:hover {
        background-color: #c82333;
    }

    .voice-recorder-btn.recording {
        background-color: #dc3545;
        animation: pulse 1s infinite;
    }

    .voice-recorder-timer {
        font-size: 16px;
        font-weight: bold;
        color: #dc3545;
        min-width: 60px;
    }

    .voice-recorder-actions {
        display: flex;
        gap: 10px;
    }

    .voice-recorder-action-btn {
        background-color: #6c757d;
        color: white;
        border: none;
        height: 40px;
        width: 40px;
        border-radius: 50%;
        cursor: pointer;
        transition: background-color 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .voice-recorder-action-btn:hover {
        background-color: #5a6268;
    }

    .voice-recorder-action-btn:disabled {
        background-color: #adb5bd;
        cursor: not-allowed;
        opacity: 0.6;
    }

    .voice-recorder-playback {
        width: 100%;
        margin-top: 10px;
    }

    /* Attachment button styles */
    .attachment-btn {
        background-color: #28a745;
        color: white;
        border: none;
        height: 50px;
        width: 50px;
        border-radius: 50%;
        cursor: pointer;
        transition: background-color 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 5px;
    }

    .attachment-btn:hover {
        background-color: #218838;
    }

    /* Send button styles */
    .send-btn {
        background-color: #007bff;
        color: white;
        border: none;
        height: 50px;
        width: 50px;
        border-radius: 50%;
        cursor: pointer;
        transition: background-color 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .send-btn:hover {
        background-color: #0056b3;
    }

    .send-btn:disabled {
        background-color: #6c757d;
        cursor: not-allowed;
        opacity: 0.6;
    }

    /* Image message styling */
    .image-message {
        margin-top: 10px;
        max-width: 300px;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .image-message img {
        width: 100%;
        height: auto;
        display: block;
        transition: transform 0.3s ease;
    }

    .image-message img:hover {
        transform: scale(1.02);
    }

    /* Typing indicator */
    .typing-indicator {
        display: flex;
        padding: 10px 15px;
        background-color: white;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
        align-self: flex-start;
        margin-bottom: 10px;
    }

    .typing-dot {
        width: 8px;
        height: 8px;
        background-color: var(--gray-color);
        border-radius: 50%;
        margin: 0 2px;
        animation: typingAnimation 1.4s infinite ease-in-out;
    }

    .typing-dot:nth-child(1) {
        animation-delay: 0s;
    }

    .typing-dot:nth-child(2) {
        animation-delay: 0.2s;
    }

    .typing-dot:nth-child(3) {
        animation-delay: 0.4s;
    }

    @keyframes typingAnimation {
        0%, 60%, 100% { transform: translateY(0); }
        30% { transform: translateY(-5px); }
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .chat-layout {
            flex-direction: column;
        }

        .conversations-sidebar {
            width: 100%;
            height: 200px;
            border-right: none;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            position: relative;
        }

        .sidebar-toggle {
            display: flex;
        }

        .mobile-sidebar-toggle {
            display: flex;
        }

        .conversations-list {
            display: flex;
            overflow-x: auto;
            padding: 10px;
        }

        .conversation-item {
            min-width: 200px;
            margin: 0 5px;
            flex-shrink: 0;
        }

        .conversation-item:hover {
            transform: translateY(-2px);
        }

        /* Hide sidebar by default on mobile */
        .conversations-sidebar.hidden {
            display: none;
        }

        .chat-header {
            padding: 12px 15px;
        }

        .chat-content {
            padding: 15px 10px;
        }

        .chat-form {
            padding: 15px;
        }

        .image-message {
            max-width: 250px;
        }
    }

    /* Make disabled buttons more visible */
    button:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    /* Ensure buttons are clickable when enabled */
    button:not(:disabled) {
        cursor: pointer;
    }

    /* Add hover effects for enabled buttons */
    .attachment-btn:not(:disabled):hover,
    .send-btn:not(:disabled):hover,
    .voice-recorder-btn:not(:disabled):hover,
    .voice-recorder-action-btn:not(:disabled):hover {
        transform: scale(1.05);
        transition: transform 0.2s ease;
    }

    /* Button styling */
    .btn {
        border-radius: 50%;
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: none;
        font-size: 18px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
    }

    .btn-success {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
    }

    .btn-warning {
        background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
        color: white;
    }

    .btn-primary {
        background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
        color: white;
    }

    .btn-danger {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        color: white;
    }

    .btn-secondary {
        background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
        color: white;
    }

    /* Voice controls styling */
    #voiceControls {
        background: rgba(255, 255, 255, 0.95);
        border-radius: 15px;
        padding: 15px;
        margin: 15px 0;
        border: 1px solid #e9ecef;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    #voiceTimer {
        background: rgba(0, 0, 0, 0.1);
        padding: 5px 10px;
        border-radius: 15px;
        font-weight: bold;
        color: #333;
    }

    .no-conversations {
        text-align: center;
        padding: 40px 20px;
        color: rgba(255, 255, 255, 0.7);
    }

    .no-conversations i {
        font-size: 48px;
        margin-bottom: 15px;
        display: block;
    }

    .no-conversations p {
        margin: 0;
        font-size: 14px;
    }

    .sidebar-toggle {
        position: absolute;
        top: 20px;
        right: 20px;
        background: rgba(255, 255, 255, 0.2);
        border: none;
        color: white;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: none;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .sidebar-toggle:hover {
        background: rgba(255, 255, 255, 0.3);
    }

    .mobile-sidebar-toggle {
        background: rgba(255, 255, 255, 0.2);
        border: none;
        color: white;
        width: 35px;
        height: 35px;
        border-radius: 50%;
        display: none;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        margin-right: 10px;
        transition: all 0.3s ease;
    }

    .mobile-sidebar-toggle:hover {
        background: rgba(255, 255, 255, 0.3);
    }

    /* Image preview styling */
    #imagePreview {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border: 2px solid #28a745;
        border-radius: 12px;
        padding: 15px;
        margin-top: 15px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        animation: slideDown 0.3s ease-out;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .image-info {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 10px;
    }

    #imageName {
        font-weight: 600;
        color: #333;
        font-size: 14px;
        word-break: break-all;
    }

    #imageSize {
        font-size: 12px;
        color: #666;
        margin-top: 2px;
    }

    #removeImageBtn {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        border: none;
        color: white;
        transition: all 0.3s ease;
    }

    #removeImageBtn:hover {
        transform: scale(1.1);
        box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3);
    }

    #imagePreviewImg {
        max-width: 120px;
        max-height: 120px;
        border-radius: 8px;
        object-fit: cover;
        border: 2px solid #fff;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }
</style>
@endsection

@section('content')
@php
    $current_url = url()->current();
    $urlParts = explode('/', $current_url);
    $recipientId = (int) end($urlParts);
    $recipient = App\Models\User::find($recipientId);
@endphp

<div class="chat-container">
    <div class="chat-layout">
        <!-- Sidebar for conversations -->
        <div class="conversations-sidebar" id="conversationsSidebar">
            <div class="sidebar-header">
                <h5><i class="fas fa-comments"></i> المحادثات</h5>
                <div class="search-container">
                    <input type="text" id="searchConversations" placeholder="البحث في المحادثات..." class="search-input">
                    <i class="fas fa-search search-icon"></i>
                </div>
                <button class="sidebar-toggle" id="sidebarToggle" onclick="toggleSidebar()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="conversations-list" id="conversationsList">
                @forelse($conversations as $conversation)
                    @php
                        $otherUser = $conversation->sender_id == auth()->id() ? $conversation->recipient : $conversation->sender;
                        $lastMessage = $conversation->messages()->latest()->first();
                        $isActive = $otherUser->id == $recipientId;
                    @endphp
                    <div class="conversation-item {{ $isActive ? 'active' : '' }}"
                         onclick="window.location.href='{{ route('web.send_message', $otherUser->id) }}'"
                         data-name="{{ strtolower($otherUser->first_name . ' ' . $otherUser->last_name) }}">
                        <div class="conversation-avatar">
                            <img src="{{ asset('storage/' . ($otherUser->image ?? 'users/user.png')) }}"
                                 alt="{{ $otherUser->first_name }}"
                                 onerror="this.onerror=null;this.src='{{ asset('storage/users/user.png') }}';">
                            <div class="online-indicator {{ $otherUser->isOnline() ? 'online' : 'offline' }}"></div>
                        </div>
                        <div class="conversation-info">
                            <div class="conversation-name">{{ $otherUser->first_name }} {{ $otherUser->last_name }}</div>
                            @if($lastMessage)
                                <div class="conversation-preview">
                                    @if($lastMessage->image)
                                        <i class="fas fa-image"></i> صورة
                                    @elseif($lastMessage->voice_note)
                                        <i class="fas fa-microphone"></i> رسالة صوتية
                                    @else
                                        {{ Str::limit($lastMessage->message, 30) }}
                                    @endif
                                </div>
                                <div class="conversation-time">{{ $lastMessage->created_at->diffForHumans() }}</div>
                            @else
                                <div class="conversation-preview">لا توجد رسائل</div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="no-conversations">
                        <i class="fas fa-comment-slash"></i>
                        <p>لا توجد محادثات</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Chat Area -->
        <div class="chat-area">
            <div class="chat-header">
                <button class="mobile-sidebar-toggle" id="mobileSidebarToggle" onclick="toggleSidebar()">
                    <i class="fas fa-bars"></i>
                </button>
                <img src="{{ asset('storage/' . ($recipient->image ?? 'user.png')) }}"
                     alt="{{ $recipient->first_name }}"
                     class="message-avatar"
                     onerror="this.onerror=null;this.src='{{ asset('storage/users/user.png') }}';">

                <div class="chat-header-info">
                    <h5 style="margin: 0;">{{ $recipient->first_name }} {{ $recipient->last_name }}</h5>
                    <small class="text-muted">
                        @if($recipient->isOnline())
                            <span class="badge badge-success">Online</span>
                        @else
                            <span class="badge badge-secondary">Offline</span>
                        @endif
                    </small>
                </div>

                <div class="chat-header-actions">
                    <button class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-phone"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-video"></i>
                    </button>
                </div>
            </div>

            <div class="chat-content" id="chatContent">
                <ul class="messages-list" id="messagesList">
                    @foreach ($messages as $message)
                        @php
                            $isCurrentUser = $message->sender_id == auth()->id();
                            $sender = $message->sender;
                        @endphp
                        <li class="message-item {{ $isCurrentUser ? 'sent' : 'received' }}" data-message-id="{{ $message->id }}">
                            <div class="message-content">
                                @if($isCurrentUser)
                                    <div class="message-actions">
                                        <button class="message-delete-btn" title="Delete message" onclick="deleteMessage({{ $message->id }})">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                @endif

                                @if(!empty($message->message))
                                    <div class="message-text">{{ $message->message }}</div>
                                @endif

                                @if(isset($message->image))
                                    <div class="image-message">
                                        <img src="{{ asset('messages/' . $message->image) }}" alt="Message image" onerror="this.style.display='none';">
                                    </div>
                                @endif

                                @if(isset($message->voice_note))
                                    <div class="audio-message">
                                        <audio controls>
                                            <source src="{{ asset('storage/' . $message->voice_note) }}" type="audio/wav">
                                            Your browser does not support the audio element.
                                        </audio>
                                    </div>
                                @endif

                                @if(empty($message->message) && !isset($message->image) && !isset($message->voice_note))
                                    <div class="message-text">{{ __('messages.empty_message') }}</div>
                                @endif

                                <div class="message-meta">
                                    <span>{{ $message->created_at->format('h:i A') }}</span>
                                    @if($isCurrentUser)
                                        @if($message->read_at)
                                            <i class="fas fa-check-double text-info" title="Read at {{ $message->read_at->format('h:i A') }}"></i>
                                        @else
                                            <i class="fas fa-check" title="Sent"></i>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>

            <form action="{{ route('messages.store') }}" method="post" class="chat-form" enctype="multipart/form-data" id="messageForm">
                @csrf
                <input type="hidden" name="recipient_id" value="{{ $recipientId }}">
                <input type="hidden" name="voice_note_data" id="voiceNoteInput">

                <div class="message-input-container">
                    <textarea name="message" rows="1" class="message-input" placeholder="اكتب رسالتك هنا..." id="messageInput"></textarea>

                    <!-- Image Preview -->
                    <div id="imagePreview" style="display: none;">
                        <div class="image-info">
                            <i class="fas fa-image" style="color: #28a745; font-size: 20px;"></i>
                            <div style="flex: 1;">
                                <div id="imageName"></div>
                                <div id="imageSize"></div>
                            </div>
                            <button type="button" id="removeImageBtn" class="btn btn-sm btn-danger" style="width: 30px; height: 30px; border-radius: 50%;">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <img id="imagePreviewImg">
                    </div>
                </div>

                <!-- Simple Voice Recording -->
                <div id="voiceControls" style="display: none; margin: 10px 0;">
                    <button type="button" id="startVoiceBtn" class="btn btn-danger">
                        <i class="fas fa-microphone"></i> بدء التسجيل
                    </button>
                    <button type="button" id="stopVoiceBtn" class="btn btn-secondary" style="display: none;">
                        <i class="fas fa-stop"></i> إيقاف التسجيل
                    </button>
                    <span id="voiceTimer" style="margin-left: 10px; font-weight: bold;">00:00</span>
                    <audio id="voicePlayback" controls style="display: none; margin-top: 10px; width: 100%;"></audio>
                </div>

                <div style="display: flex; gap: 10px; align-items: center;">
                    <!-- Image Upload Button -->
                    <button type="button" id="imageBtn" class="btn btn-success" style="border-radius: 50%; width: 50px; height: 50px;">
                        <i class="fas fa-image"></i>
                    </button>
                    <input type="file" name="image" id="imageInput" style="display: none;" accept="image/*">

                    <!-- Voice Recording Button -->
                    <button type="button" id="voiceBtn" class="btn btn-warning" style="border-radius: 50%; width: 50px; height: 50px;">
                        <i class="fas fa-microphone"></i>
                    </button>

                    <!-- Send Button -->
                    <button type="submit" id="sendBtn" class="btn btn-primary" style="border-radius: 50%; width: 50px; height: 50px;">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM loaded, initializing chat...');

        // Get elements
        const messageForm = document.getElementById('messageForm');
        const messageInput = document.getElementById('messageInput');
        const imageBtn = document.getElementById('imageBtn');
        const imageInput = document.getElementById('imageInput');
        const voiceBtn = document.getElementById('voiceBtn');
        const voiceControls = document.getElementById('voiceControls');
        const startVoiceBtn = document.getElementById('startVoiceBtn');
        const stopVoiceBtn = document.getElementById('stopVoiceBtn');
        const voiceTimer = document.getElementById('voiceTimer');
        const voicePlayback = document.getElementById('voicePlayback');
        const voiceNoteInput = document.getElementById('voiceNoteInput');
        const sendBtn = document.getElementById('sendBtn');

        let mediaRecorder = null;
        let audioChunks = [];
        let recordingInterval = null;
        let recordingSeconds = 0;

        console.log('Elements found:', {
            messageForm: !!messageForm,
            messageInput: !!messageInput,
            imageBtn: !!imageBtn,
            imageInput: !!imageInput,
            voiceBtn: !!voiceBtn,
            voiceControls: !!voiceControls,
            startVoiceBtn: !!startVoiceBtn,
            stopVoiceBtn: !!stopVoiceBtn,
            voiceTimer: !!voiceTimer,
            voicePlayback: !!voicePlayback,
            voiceNoteInput: !!voiceNoteInput,
            sendBtn: !!sendBtn
        });

        // Image upload functionality
        if (imageBtn && imageInput) {
            imageBtn.addEventListener('click', function() {
                console.log('Image button clicked');
                imageInput.click();
            });

            imageInput.addEventListener('change', function() {
                console.log('Image selected:', this.files.length);
                if (this.files.length > 0) {
                    const file = this.files[0];
                    displayImagePreview(file);
                    updateSendButtonState();
                }
            });
        }

        // Display image preview
        function displayImagePreview(file) {
            const imagePreview = document.getElementById('imagePreview');
            const imageName = document.getElementById('imageName');
            const imageSize = document.getElementById('imageSize');
            const imagePreviewImg = document.getElementById('imagePreviewImg');

            // Display image name
            imageName.textContent = file.name;

            // Display file size
            const sizeInMB = (file.size / (1024 * 1024)).toFixed(2);
            imageSize.textContent = `${sizeInMB} MB`;

            // Create preview
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreviewImg.src = e.target.result;
            };
            reader.readAsDataURL(file);

            // Show preview
            imagePreview.style.display = 'block';

            // Add remove functionality
            const removeImageBtn = document.getElementById('removeImageBtn');
            removeImageBtn.onclick = function() {
                removeImagePreview();
            };
        }

        // Remove image preview
        function removeImagePreview() {
            const imagePreview = document.getElementById('imagePreview');
            const imageInput = document.getElementById('imageInput');

            imagePreview.style.display = 'none';
            imageInput.value = '';
            updateSendButtonState();
        }

        // Voice recording functionality
        if (voiceBtn && voiceControls) {
            voiceBtn.addEventListener('click', function() {
                console.log('Voice button clicked');
                if (voiceControls.style.display === 'none') {
                    voiceControls.style.display = 'block';
                } else {
                    voiceControls.style.display = 'none';
                }
            });
        }

        // Start voice recording
        if (startVoiceBtn) {
            startVoiceBtn.addEventListener('click', function() {
                console.log('Start voice recording clicked');
                startVoiceRecording();
            });
        }

        // Stop voice recording
        if (stopVoiceBtn) {
            stopVoiceBtn.addEventListener('click', function() {
                console.log('Stop voice recording clicked');
                stopVoiceRecording();
            });
        }

        // Voice recording functions
        function startVoiceRecording() {
            console.log('Starting voice recording...');
            navigator.mediaDevices.getUserMedia({ audio: true })
                .then(stream => {
                    mediaRecorder = new MediaRecorder(stream);
                    audioChunks = [];
                    recordingSeconds = 0;

                    mediaRecorder.ondataavailable = (event) => {
                        audioChunks.push(event.data);
                    };

                    mediaRecorder.onstop = () => {
                        const audioBlob = new Blob(audioChunks, { type: 'audio/wav' });
                        const audioUrl = URL.createObjectURL(audioBlob);

                        // Convert to base64
                        const reader = new FileReader();
                        reader.readAsDataURL(audioBlob);
                        reader.onloadend = () => {
                            voiceNoteInput.value = reader.result;
                            voicePlayback.src = audioUrl;
                            voicePlayback.style.display = 'block';
                            updateSendButtonState();
                            console.log('Voice recording saved');
                        };

                        // Stop all tracks
                        stream.getTracks().forEach(track => track.stop());
                    };

                    mediaRecorder.start();
                    startVoiceBtn.style.display = 'none';
                    stopVoiceBtn.style.display = 'inline-block';

                    // Start timer
                    recordingInterval = setInterval(() => {
                        recordingSeconds++;
                        const minutes = Math.floor(recordingSeconds / 60);
                        const seconds = recordingSeconds % 60;
                        voiceTimer.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                    }, 1000);

                    console.log('Voice recording started');
                })
                .catch(error => {
                    console.error('Error accessing microphone:', error);
                    alert('لا يمكن الوصول إلى الميكروفون. يرجى السماح بالوصول إلى الميكروفون.');
                });
        }

        function stopVoiceRecording() {
            console.log('Stopping voice recording...');
            if (mediaRecorder && mediaRecorder.state === 'recording') {
                mediaRecorder.stop();
            }

            if (recordingInterval) {
                clearInterval(recordingInterval);
                recordingInterval = null;
            }

            startVoiceBtn.style.display = 'inline-block';
            stopVoiceBtn.style.display = 'none';
            voiceTimer.textContent = '00:00';
            recordingSeconds = 0;
        }

        // Message input handling
        if (messageInput) {
            messageInput.addEventListener('input', function() {
                updateSendButtonState();
            });
        }

        // Update send button state
        function updateSendButtonState() {
            const hasText = messageInput && messageInput.value.trim() !== '';
            const hasVoice = voiceNoteInput && voiceNoteInput.value !== '';
            const hasImage = imageInput && imageInput.files.length > 0;

            if (sendBtn) {
                const shouldEnable = hasText || hasVoice || hasImage;
                sendBtn.disabled = !shouldEnable;
                console.log('Send button state:', shouldEnable ? 'enabled' : 'disabled', {
                    hasText,
                    hasVoice,
                    hasImage
                });
            }
        }

        // Form submission
        if (messageForm) {
            messageForm.addEventListener('submit', function(e) {
                console.log('Form submitted');
                const hasText = messageInput && messageInput.value.trim() !== '';
                const hasVoice = voiceNoteInput && voiceNoteInput.value !== '';
                const hasImage = imageInput && imageInput.files.length > 0;

                if (!hasText && !hasVoice && !hasImage) {
                    e.preventDefault();
                    alert('يرجى إضافة رسالة أو تسجيل صوتي أو صورة قبل الإرسال.');
                    return;
                }

                console.log('Form submission allowed');
            });
        }

        // Auto-resize textarea
        if (messageInput) {
            messageInput.addEventListener('input', function() {
                this.style.height = 'auto';
                this.style.height = (this.scrollHeight) + 'px';
            });
        }

        // Initial state
        updateSendButtonState();
        console.log('Chat initialization complete');

        // Search functionality for conversations
        const searchInput = document.getElementById('searchConversations');
        const conversationsList = document.getElementById('conversationsList');
        const conversationItems = document.querySelectorAll('.conversation-item');

        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase().trim();

                conversationItems.forEach(item => {
                    const name = item.getAttribute('data-name') || '';
                    if (name.includes(searchTerm)) {
                        item.style.display = 'flex';
                        item.style.animation = 'fadeInUp 0.3s ease-out';
                    } else {
                        item.style.display = 'none';
                    }
                });

                // Show no results message if no conversations match
                const visibleItems = Array.from(conversationItems).filter(item =>
                    item.style.display !== 'none'
                );

                let noResultsMsg = document.querySelector('.no-results');
                if (visibleItems.length === 0 && searchTerm !== '') {
                    if (!noResultsMsg) {
                        noResultsMsg = document.createElement('div');
                        noResultsMsg.className = 'no-conversations no-results';
                        noResultsMsg.innerHTML = `
                            <i class="fas fa-search"></i>
                            <p>لا توجد نتائج للبحث</p>
                        `;
                        conversationsList.appendChild(noResultsMsg);
                    }
                } else if (noResultsMsg) {
                    noResultsMsg.remove();
                }
            });
        }
    });

    // Delete message function
    function deleteMessage(messageId) {
        if (confirm('هل أنت متأكد من حذف هذه الرسالة؟')) {
            fetch(`/messages/${messageId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (response.ok) {
                    const messageElement = document.querySelector(`li[data-message-id="${messageId}"]`);
                    if (messageElement) {
                        messageElement.remove();
                    }
                } else {
                    alert('فشل في حذف الرسالة');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('حدث خطأ أثناء حذف الرسالة');
            });
        }
    }

    // Scroll to bottom of chat
    function scrollToBottom() {
        const chatContent = document.querySelector('.chat-content');
        if (chatContent) {
            chatContent.scrollTop = chatContent.scrollHeight;
        }
    }

    // Initial scroll to bottom
    document.addEventListener('DOMContentLoaded', function() {
        scrollToBottom();
    });

    // Sidebar toggle function
    function toggleSidebar() {
        const sidebar = document.getElementById('conversationsSidebar');
        if (sidebar) {
            sidebar.classList.toggle('hidden');
        }
    }

    // Auto-hide sidebar on mobile when clicking outside
    document.addEventListener('click', function(event) {
        const sidebar = document.getElementById('conversationsSidebar');
        const mobileToggle = document.getElementById('mobileSidebarToggle');

        if (window.innerWidth <= 768 && sidebar && !sidebar.contains(event.target) && !mobileToggle.contains(event.target)) {
            sidebar.classList.add('hidden');
        }
    });
</script>
@endsection
