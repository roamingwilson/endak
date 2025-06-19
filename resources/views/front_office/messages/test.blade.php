@extends('layouts.home')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>اختبار الأزرار</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('messages.store') }}" method="post" enctype="multipart/form-data" id="testForm">
                        @csrf
                        <input type="hidden" name="recipient_id" value="1">
                        <input type="hidden" name="voice_note_data" id="voiceNoteInput">

                        <div class="mb-3">
                            <label for="messageInput" class="form-label">الرسالة:</label>
                            <textarea name="message" class="form-control" id="messageInput" rows="3" placeholder="اكتب رسالتك هنا..."></textarea>
                        </div>

                        <!-- Voice Recording Controls -->
                        <div id="voiceControls" style="display: none; margin: 15px 0; padding: 15px; border: 1px solid #ddd; border-radius: 8px;">
                            <h6>التسجيل الصوتي:</h6>
                            <button type="button" id="startVoiceBtn" class="btn btn-danger">
                                <i class="fas fa-microphone"></i> بدء التسجيل
                            </button>
                            <button type="button" id="stopVoiceBtn" class="btn btn-secondary" style="display: none;">
                                <i class="fas fa-stop"></i> إيقاف التسجيل
                            </button>
                            <span id="voiceTimer" style="margin-left: 10px; font-weight: bold;">00:00</span>
                            <audio id="voicePlayback" controls style="display: none; margin-top: 10px; width: 100%;"></audio>
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex gap-2 mb-3">
                            <button type="button" id="imageBtn" class="btn btn-success">
                                <i class="fas fa-image"></i> إضافة صورة
                            </button>
                            <input type="file" name="image" id="imageInput" style="display: none;" accept="image/*">

                            <button type="button" id="voiceBtn" class="btn btn-warning">
                                <i class="fas fa-microphone"></i> تسجيل صوتي
                            </button>

                            <button type="submit" id="sendBtn" class="btn btn-primary">
                                <i class="fas fa-paper-plane"></i> إرسال
                            </button>
                        </div>

                        <!-- Status Display -->
                        <div id="status" class="alert alert-info" style="display: none;"></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Test page loaded');

    // Get elements
    const messageForm = document.getElementById('testForm');
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
    const status = document.getElementById('status');

    let mediaRecorder = null;
    let audioChunks = [];
    let recordingInterval = null;
    let recordingSeconds = 0;

    // Show status
    function showStatus(message, type = 'info') {
        status.textContent = message;
        status.className = `alert alert-${type}`;
        status.style.display = 'block';
        setTimeout(() => {
            status.style.display = 'none';
        }, 3000);
    }

    // Image upload functionality
    if (imageBtn && imageInput) {
        imageBtn.addEventListener('click', function() {
            console.log('Image button clicked');
            imageInput.click();
            showStatus('تم النقر على زر الصورة', 'success');
        });

        imageInput.addEventListener('change', function() {
            console.log('Image selected:', this.files.length);
            if (this.files.length > 0) {
                showStatus(`تم اختيار صورة: ${this.files[0].name}`, 'success');
                updateSendButtonState();
            }
        });
    }

    // Voice recording functionality
    if (voiceBtn && voiceControls) {
        voiceBtn.addEventListener('click', function() {
            console.log('Voice button clicked');
            if (voiceControls.style.display === 'none') {
                voiceControls.style.display = 'block';
                showStatus('تم فتح أدوات التسجيل الصوتي', 'success');
            } else {
                voiceControls.style.display = 'none';
                showStatus('تم إغلاق أدوات التسجيل الصوتي', 'info');
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
        showStatus('جاري بدء التسجيل الصوتي...', 'info');

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
                        showStatus('تم حفظ التسجيل الصوتي بنجاح', 'success');
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

                showStatus('التسجيل الصوتي يعمل الآن', 'success');
                console.log('Voice recording started');
            })
            .catch(error => {
                console.error('Error accessing microphone:', error);
                showStatus('لا يمكن الوصول إلى الميكروفون. يرجى السماح بالوصول إلى الميكروفون.', 'danger');
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

        showStatus('تم إيقاف التسجيل الصوتي', 'info');
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
                showStatus('يرجى إضافة رسالة أو تسجيل صوتي أو صورة قبل الإرسال.', 'warning');
                return;
            }

            showStatus('جاري إرسال الرسالة...', 'info');
            console.log('Form submission allowed');
        });
    }

    // Initial state
    updateSendButtonState();
    showStatus('تم تحميل الصفحة بنجاح. جرب الأزرار الآن!', 'success');
    console.log('Test page initialization complete');
});
</script>
@endsection
