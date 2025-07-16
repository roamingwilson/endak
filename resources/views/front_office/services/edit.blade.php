@extends('layouts.home')
@section('title', 'تعديل الخدمة')
@section('content')
<div class="container mt-4">
    <h2>تعديل بيانات الخدمة</h2>
    <div class="card p-4">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ route('services.update', $service->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <input type="hidden" name="user_id" value="{{ $service->user_id }}">
            <input type="hidden" name="department_id" value="{{ $service->department_id }}">
            <input type="hidden" name="type" value="{{ $service->type }}">
            {{-- الحقول المخصصة الأساسية داخل custom_fields --}}

            {{-- باقي الحقول المخصصة --}}
            <h4 class="mb-3">الحقول المخصصة</h4>
            @if($service->department && $service->department->fields)
                @foreach($service->department->fields as $field)
                    <div class="mb-3">
                        <label for="custom_fields[{{ $field->name }}]">
                            {{ $field->name_ar }} ({{ $field->name_en }})
                        </label>
                        @php
                            $value = old('custom_fields.' . $field->name, $service->custom_fields[$field->name] ?? null);
                        @endphp
                        @if($field->type === 'select' && is_array($field->options))
                            <select name="custom_fields[{{ $field->name }}]" id="custom_fields[{{ $field->name }}]" class="form-control">
                                @foreach($field->options as $option)
                                    <option value="{{ $option }}" {{ $value == $option ? 'selected' : '' }}>{{ $option }}</option>
                                @endforeach
                            </select>
                        @elseif($field->type === 'checkbox')
                            <input type="checkbox" name="custom_fields[{{ $field->name }}]" id="custom_fields[{{ $field->name }}]" value="1" class="form-check-input" {{ $value ? 'checked' : '' }}>
                        @elseif($field->type === 'image')
                            @if($value)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $value) }}" alt="صورة" style="max-width:120px; margin:5px;">
                                </div>
                            @endif
                            <input type="file" name="custom_fields[{{ $field->name }}]" id="custom_fields[{{ $field->name }}]" accept="image/*" class="form-control">
                        @elseif($field->type === 'date')
                            <input type="date" name="custom_fields[{{ $field->name }}]" id="custom_fields[{{ $field->name }}]" class="form-control" value="{{ $value }}">
                        @elseif($field->type === 'time')
                            <input type="time" name="custom_fields[{{ $field->name }}]" id="custom_fields[{{ $field->name }}]" class="form-control" value="{{ $value }}">
                        @elseif($field->type === 'textarea')
                            <textarea name="custom_fields[{{ $field->name }}]" id="custom_fields[{{ $field->name }}]" class="form-control">{{ $value }}</textarea>
                        @else
                            <input type="{{ $field->type }}" name="custom_fields[{{ $field->name }}]" id="custom_fields[{{ $field->name }}]" class="form-control" value="{{ $value }}">
                        @endif
                    </div>
                @endforeach
            @else
                <div class="alert alert-warning">لا توجد حقول مخصصة أو القسم غير مرتبط بالخدمة.</div>
            @endif
            <div class="mb-3">
                <label><i class="fas fa-microphone"></i> {{ __('ملاحظة صوتية') }}</label>
                <div class="voice-note-container">
                    <div id="recordingStatus" style="margin-bottom: 8px; color: #d9534f; display: none;"></div>
                    <button id="startRecord" type="button" class="btn btn-primary">بدء التسجيل</button>
                    <button id="stopRecord" type="button" class="btn btn-danger" disabled>ايقاف التسجيل</button>
                    <button id="resetRecord" type="button" class="btn btn-secondary" style="display:none;">إعادة التسجيل</button>
                    <span id="recordingTimer" style="margin-left: 10px; font-weight: bold; display:none;">00:00</span>
                    <audio id="audioPlayback" controls style="display: none; margin-top: 10px;"></audio>
                    <a id="downloadLink" style="display: none; margin-top: 10px;" class="btn btn-success">تنزيل التسجيل</a>
                    <input type="hidden" name="voice_note_data" id="voiceNoteData">
                </div>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-success px-4">حفظ التعديلات</button>
                <a href="{{ route('services.show', $service->id) }}" class="btn btn-secondary px-4">إلغاء</a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('script')
<script>
let mediaRecorder;
let audioChunks = [];
let timerInterval;
let seconds = 0;
function updateTimer() {
    seconds++;
    const min = String(Math.floor(seconds / 60)).padStart(2, '0');
    const sec = String(seconds % 60).padStart(2, '0');
    document.getElementById('recordingTimer').textContent = `${min}:${sec}`;
}
document.getElementById('startRecord').onclick = async function() {
    audioChunks = [];
    seconds = 0;
    document.getElementById('recordingStatus').style.display = 'block';
    document.getElementById('recordingStatus').textContent = 'يتم التسجيل...';
    document.getElementById('recordingTimer').style.display = 'inline';
    document.getElementById('recordingTimer').textContent = '00:00';
    document.getElementById('stopRecord').disabled = false;
    document.getElementById('startRecord').disabled = true;
    document.getElementById('resetRecord').style.display = 'none';
    document.getElementById('audioPlayback').style.display = 'none';
    document.getElementById('downloadLink').style.display = 'none';
    timerInterval = setInterval(updateTimer, 1000);
    if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
        const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
        mediaRecorder = new MediaRecorder(stream);
        mediaRecorder.start();
        mediaRecorder.ondataavailable = function(e) {
            audioChunks.push(e.data);
        };
        mediaRecorder.onstop = function() {
            clearInterval(timerInterval);
            document.getElementById('recordingStatus').style.display = 'none';
            document.getElementById('recordingTimer').style.display = 'none';
            const audioBlob = new Blob(audioChunks, { type: 'audio/wav' });
            const audioUrl = URL.createObjectURL(audioBlob);
            document.getElementById('audioPlayback').src = audioUrl;
            document.getElementById('audioPlayback').style.display = 'block';
            document.getElementById('downloadLink').href = audioUrl;
            document.getElementById('downloadLink').download = 'voice_note.wav';
            document.getElementById('downloadLink').style.display = 'inline-block';
            // تحويل الصوت إلى base64
            const reader = new FileReader();
            reader.onloadend = function() {
                document.getElementById('voiceNoteData').value = reader.result;
            };
            reader.readAsDataURL(audioBlob);
            document.getElementById('resetRecord').style.display = 'inline-block';
            document.getElementById('startRecord').disabled = false;
            document.getElementById('stopRecord').disabled = true;
        };
    }
};
document.getElementById('stopRecord').onclick = function() {
    if (mediaRecorder && mediaRecorder.state === 'recording') {
        mediaRecorder.stop();
    }
};
document.getElementById('resetRecord').onclick = function() {
    document.getElementById('audioPlayback').style.display = 'none';
    document.getElementById('downloadLink').style.display = 'none';
    document.getElementById('voiceNoteData').value = '';
    document.getElementById('resetRecord').style.display = 'none';
    document.getElementById('startRecord').disabled = false;
    document.getElementById('stopRecord').disabled = true;
    seconds = 0;
    document.getElementById('recordingTimer').textContent = '00:00';
};
</script>
@endsection
