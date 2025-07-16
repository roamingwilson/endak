@extends('layouts.home')
@section('title')
    {{ __('department.departments') }}
@endsection


<?php $lang = config('app.locale'); ?>

@section('content')
    <style>
        .department-card {
            border-radius: 18px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.10);
            background: linear-gradient(135deg, #f8fafc 80%, #e3e8ef 100%);
            padding: 32px 24px 24px 24px;
            margin-bottom: 32px;
            transition: box-shadow 0.2s;
        }
        .department-card:hover {
            box-shadow: 0 8px 32px rgba(0,0,0,0.13);
        }
        .department-card img {
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.09);
            background: #fff;
            border: 2px solid #e3e3e3;
        }
        .field-card {
            border: 1px solid #e3e3e3;
            border-radius: 12px;
            background: #f5f7fa;
            margin-bottom: 18px;
            padding: 18px 16px 10px 16px;
            transition: box-shadow 0.2s;
            display: flex;
            align-items: center;
        }
        .field-card:hover {
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        }
        .field-label {
            font-weight: bold;
            color: #1a237e;
            margin-bottom: 8px;
            flex: 1 0 120px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .field-label i {
            color: #1976d2;
            font-size: 1.2em;
        }
        .form-control, select {
            border-radius: 8px;
            min-width: 120px;
        }
        .btn-success {
            border-radius: 8px;
            font-size: 1.1rem;
            padding: 12px 0;
            font-weight: bold;
            letter-spacing: 1px;
            box-shadow: 0 2px 8px rgba(76,175,80,0.08);
        }
        .section-title {
            font-size: 2rem;
            font-weight: bold;
            color: #1976d2;
            margin-bottom: 24px;
            text-align: center;
            letter-spacing: 1px;
        }
        /* تحسين البطاقات */
        .department-card, .card {
            transition: box-shadow 0.3s, transform 0.2s;
        }
        .department-card:hover, .card:hover {
            box-shadow: 0 12px 32px rgba(25, 118, 210, 0.13);
            transform: translateY(-4px) scale(1.01);
        }

        /* تحسين الحقول */
        .field-card {
            margin-bottom: 24px;
            border-left: 4px solid #1976d2;
            background: #f8fafc;
        }
        .field-card:focus-within {
            border-color: #43e97b;
            box-shadow: 0 0 0 2px #43e97b33;
        }

        /* تحسين الأزرار */
        .btn-success, .btn-primary, .btn-danger, .btn-secondary {
            transition: background 0.2s, color 0.2s, box-shadow 0.2s;
        }
        .btn-success:hover {
            background: linear-gradient(90deg, #43e97b 0%, #38f9d7 100%);
            color: #fff;
            box-shadow: 0 4px 16px #43e97b33;
        }
        .btn-primary:hover {
            background: #1565c0;
        }
        .btn-danger:hover {
            background: #c62828;
        }
        .btn-secondary:hover {
            background: #616161;
        }

        /* رسائل الأخطاء */
        .alert-danger {
            border-radius: 8px;
            font-size: 1.1rem;
            background: #fff3f3;
            color: #c62828;
            border: 1px solid #ffcdd2;
        }
        .alert-danger ul {
            margin-bottom: 0;
            padding-left: 1.5em;
        }
        .alert-danger li:before {
            content: "⚠️ ";
            margin-right: 4px;
        }

        /* منطقة التسجيل الصوتي */
        .voice-note-container {
            background: #e3e8ef;
            border-radius: 10px;
            padding: 12px 16px;
            margin-top: 8px;
            margin-bottom: 8px;
            position: relative;
        }
        #recordingStatus:before {
            content: '';
            display: inline-block;
            width: 10px;
            height: 10px;
            background: #d9534f;
            border-radius: 50%;
            margin-right: 6px;
            animation: blink 1s infinite;
        }
        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.2; }
        }

        /* دعم RTL */
        body[dir="rtl"] .department-card,
        body[dir="rtl"] .card,
        body[dir="rtl"] .field-card {
            direction: rtl;
            text-align: right;
        }
    </style>
    <div class="main-content app-content">
        <section>
            <div class="section banner-4 banner-section">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-md-12 text-center">
                            <div class="section-title">
                                <i class="fas fa-layer-group"></i>
                                {{ $lang == 'ar' ? 'بيانات القسم' : 'Department Details' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="section">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="department-card text-center mb-4">
                            @php
                                $img = (!empty($department->image) && file_exists(public_path('storage/' . $department->image)))
                                    ? asset('storage/' . $department->image)
                                    : asset('images/logo.jpg');
                            @endphp
                            <img src="{{ $img }}" alt="img" width="160" height="160" class="mb-3">
                            <h2 class="mb-2" style="color:#1976d2">{{ $lang == 'ar' ? $department->name_ar : $department->name_en }}</h2>
                            <p class="text-muted">{{ $lang == 'ar' ? $department->description_ar : $department->description_en }}</p>
                        </div>
                                        <div class="card">
                            <div class="card-header text-center bg-primary text-white" style="font-size:1.2rem; font-weight:bold;">
                                <i class="fas fa-concierge-bell"></i> {{ __('طلب خدمة من هذا القسم') }}
                            </div>
                            <div class="card-body">
                                <form action="{{ route('services.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="department_id" value="{{ $department->id }}">
                                    <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                                    <input type="hidden" name="type" value="{{ $department->name_en }}">
                                    @if ($errors->any())
                                        <div class="alert alert-danger">
                                            <ul>
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                    @foreach($department->fields as $field)
                                        <div class="field-card">
                                            <div class="field-label">
                                                <i class="fas fa-{{
                                                    $field->type === 'text' ? 'font' :
                                                    ($field->type === 'number' ? 'hashtag' :
                                                    ($field->type === 'select' ? 'list' :
                                                    ($field->type === 'checkbox' ? 'check-square' :
                                                    ($field->type === 'image' ? 'image' :
                                                    ($field->type === 'date' ? 'calendar-alt' :
                                                    ($field->type === 'time' ? 'clock' : 'edit'))))))
                                                }}"></i>
                                                <label for="custom_fields_{{ $field->name }}" style="margin-bottom:0; font-weight:bold;">
                                                    {{ $lang == 'ar' ? $field->name_ar : $field->name_en }}
                                                    {{--  {{ $lang == 'ar' ? 'ملاحظة صوتية' : 'Voice Note' }}  --}}
                                                </label>
                                            </div>
                                            <div style="flex:2;">
                                            @if($field->type === 'select' && is_array($field->options))
                                                <select name="custom_fields[{{ $field->name }}]" id="custom_fields_{{ $field->name }}" class="form-control">
                                                    @foreach($field->options as $option)
                                                        <option value="{{ $option }}" {{ old('custom_fields.' . $field->name) == $option ? 'selected' : '' }}>{{ $option }}</option>
                                                    @endforeach
                                                </select>
                                            @elseif($field->type === 'checkbox')
                                                <input type="checkbox" name="custom_fields[{{ $field->name }}]" id="custom_fields_{{ $field->name }}" value="1" class="form-check-input" {{ old('custom_fields.' . $field->name) ? 'checked' : '' }}>
                                            @elseif($field->type === 'image')
                                                <input type="file" name="custom_fields[{{ $field->name }}]" id="custom_fields_{{ $field->name }}" accept="image/*" class="form-control">
                                            @elseif($field->type === 'date')
                                                <input type="date" name="custom_fields[{{ $field->name }}]" id="custom_fields_{{ $field->name }}" class="form-control" value="{{ old('custom_fields.' . $field->name) }}">
                                            @elseif($field->type === 'time')
                                                <input type="time" name="custom_fields[{{ $field->name }}]" id="custom_fields_{{ $field->name }}" class="form-control" value="{{ old('custom_fields.' . $field->name) }}">
                                                        @else
                                                <input type="{{ $field->type }}" name="custom_fields[{{ $field->name }}]" id="custom_fields_{{ $field->name }}" class="form-control" value="{{ old('custom_fields.' . $field->name) }}">
                                                        @endif
                                            </div>
                                        </div>
                                    @endforeach
                                    <!-- Voice Note Section -->
                                    <div class="field-card">
                                        <div class="field-label">
                                            <i class="fas fa-microphone"></i> {{ $lang == 'ar' ? 'ملاحظة صوتية' : 'Voice Note' }}
                                        </div>
                                        <div style="flex:2;">
                                            <div class="voice-note-container">
                                                <div class="recordingStatus" style="margin-bottom: 8px; color: #d9534f; display: none;"></div>
                                                <button type="button" class="startRecord btn btn-primary">{{ $lang == 'ar' ? 'بدء التسجيل' : 'Start Recording' }}</button>
                                                <button type="button" class="stopRecord btn btn-danger" disabled>{{ $lang == 'ar' ? 'ايقاف التسجيل' : 'Stop Recording' }}</button>
                                                <button type="button" class="resetRecord btn btn-secondary" style="display:none;">{{ $lang == 'ar' ? 'إعادة التسجيل' : 'Reset Recording' }}</button>
                                                <span class="recordingTimer" style="margin-left: 10px; font-weight: bold; display:none;">00:00</span>
                                                <audio class="audioPlayback" controls style="display: none; margin-top: 10px;"></audio>
                                                <a class="downloadLink btn btn-success" style="display: none; margin-top: 10px;">{{ $lang == 'ar' ? 'تنزيل التسجيل' : 'Download Recording' }}</a>
                                                <input type="hidden" name="voice_note_data" class="voiceNoteData">
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-success btn-block mt-3">
                                        <i class="fas fa-paper-plane"></i> {{ __('إرسال الطلب') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        @if (auth()->check() && auth()->user()->role_id == 3)
            <section class="section">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="row">
                                @forelse ($services as $service)
                                    <div class="col-md-4 mb-4">
                                        <div class="card h-100">
                                            <div class="card-body d-flex flex-column">
                                                <h5 class="card-title" style="color:#1976d2; font-weight:bold;">
                                                    {{ $service->title ?? ($lang == 'ar' ? $service->name_ar : $service->name_en) }}
                                                </h5>
                                                <div class="mb-2 text-muted" style="font-size:0.95em;">
                                                    <i class="fas fa-user"></i> {{ $service->user->full_name ?? '-' }}
                                                </div>
                                                <div class="mb-2 text-muted" style="font-size:0.95em;">
                                                    <i class="fas fa-clock"></i> {{ $service->created_at ? $service->created_at->diffForHumans() : '-' }}
                                                </div>
                                                <a href="{{ route('show_myservice', $service->id) }}" class="btn btn-outline-primary mt-auto">
                                                    {{ $lang == 'ar' ? 'عرض التفاصيل' : 'View Details' }}
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12 text-center text-muted py-4">
                                        {{ $lang == 'ar' ? 'لا توجد خدمات متاحة' : 'No services available.' }}
                                    </div>
                                @endforelse
                            </div>
                            <div class="d-flex justify-content-center mt-3">
                                {!! $services->links() !!}
                            </div>
                        </div>
                    </div>
                </div>
        </section>
        @endif
@endsection

@section('script')
<script>
document.querySelectorAll('.voice-note-container').forEach(function(container) {
    let mediaRecorder;
    let audioChunks = [];
    let timerInterval;
    let seconds = 0;
    const startBtn = container.querySelector('.startRecord');
    const stopBtn = container.querySelector('.stopRecord');
    const resetBtn = container.querySelector('.resetRecord');
    const statusDiv = container.querySelector('.recordingStatus');
    const timerSpan = container.querySelector('.recordingTimer');
    const audioPlayback = container.querySelector('.audioPlayback');
    const downloadLink = container.querySelector('.downloadLink');
    const voiceInput = container.querySelector('.voiceNoteData');

    function updateTimer() {
        seconds++;
        const min = String(Math.floor(seconds / 60)).padStart(2, '0');
        const sec = String(seconds % 60).padStart(2, '0');
        timerSpan.textContent = `${min}:${sec}`;
    }

    startBtn.onclick = async function() {
        audioChunks = [];
        seconds = 0;
        statusDiv.style.display = 'block';
        statusDiv.textContent = 'يتم التسجيل...';
        timerSpan.style.display = 'inline';
        timerSpan.textContent = '00:00';
        stopBtn.disabled = false;
        startBtn.disabled = true;
        resetBtn.style.display = 'none';
        audioPlayback.style.display = 'none';
        downloadLink.style.display = 'none';
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
                statusDiv.style.display = 'none';
                timerSpan.style.display = 'none';
                const audioBlob = new Blob(audioChunks, { type: 'audio/wav' });
                const audioUrl = URL.createObjectURL(audioBlob);
                audioPlayback.src = audioUrl;
                audioPlayback.style.display = 'block';
                downloadLink.href = audioUrl;
                downloadLink.download = 'voice_note.wav';
                downloadLink.style.display = 'inline-block';
                // تحويل الصوت إلى base64
                const reader = new FileReader();
                reader.onloadend = function() {
                    voiceInput.value = reader.result;
                };
                reader.readAsDataURL(audioBlob);
                resetBtn.style.display = 'inline-block';
                startBtn.disabled = false;
                stopBtn.disabled = true;
            };
        }
    };
    stopBtn.onclick = function() {
        if (mediaRecorder && mediaRecorder.state === 'recording') {
            mediaRecorder.stop();
        }
    };
    resetBtn.onclick = function() {
        audioPlayback.style.display = 'none';
        downloadLink.style.display = 'none';
        voiceInput.value = '';
        resetBtn.style.display = 'none';
        startBtn.disabled = false;
        stopBtn.disabled = true;
        seconds = 0;
        timerSpan.textContent = '00:00';
    };
});
// فحص قبل الإرسال
const form = document.querySelector('form');
if(form) {
    form.onsubmit = function() {
        const voiceInput = form.querySelector('.voiceNoteData');
        if (voiceInput && !voiceInput.value) {
            // يمكنك منع الإرسال إذا كان التسجيل مطلوبًا:
            // alert('يرجى تسجيل ملاحظة صوتية أو التأكد من الضغط على إيقاف التسجيل قبل الحفظ.');
            // return false;
        }
    };
}
</script>
@endsection
