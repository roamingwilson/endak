@extends('layouts.home')
@section('title', 'تفاصيل الخدمة')
<?php $lang = config('app.locale'); ?>
@php $user = auth()->user(); @endphp
@section('content')
<div class="container mt-4">
    @if(auth()->check() && auth()->id() == $service->user_id)
        <div class="mb-3 d-flex justify-content-end gap-2">
            <a href="{{ route('services.edit', $service->id) }}" class="btn btn-warning mx-1">
                <i class="fas fa-edit"></i> تعديل الخدمة
            </a>
            <form action="{{ route('services.destroy', $service->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف الخدمة؟');" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger mx-1">
                    <i class="fas fa-trash"></i> حذف الخدمة
                </button>
            </form>
        </div>
    @endif
    {{--  @if(isset($hasSubDepartments) && $hasSubDepartments)
        <div class="alert alert-info mb-3">
            هذا القسم لديه أقسام فرعية:
            <ul>
                @foreach($service->department->sub_departments as $sub)
                    <li>{{ $sub->name_ar ?? $sub->name_en }}</li>
                @endforeach
            </ul>
        </div>
    @endif  --}}
    <h2>تفاصيل الخدمة</h2>
    <div class="card p-4">
        {{--  <h4>بيانات الخدمة الأساسية</h4>
        <ul class="list-group mt-3">
            <li class="list-group-item"><strong>نوع الخدمة:</strong> {{ $service->type }}</li>
            <li class="list-group-item"><strong>من المدينة:</strong> {{ $service->from_city }}</li>
            <li class="list-group-item"><strong>إلى المدينة:</strong> {{ $service->to_city }}</li>
            <li class="list-group-item"><strong>الحي:</strong> {{ $service->neighborhood }}</li>
            <li class="list-group-item"><strong>إلى الحي:</strong> {{ $service->to_neighborhood }}</li>
            <li class="list-group-item"><strong>الموديل:</strong> {{ $service->model }}</li>
            <li class="list-group-item"><strong>التاريخ:</strong> {{ $service->date }}</li>
            <li class="list-group-item"><strong>الوقت:</strong> {{ $service->time }}</li>
            <li class="list-group-item"><strong>الجنس:</strong> {{ $service->gender }}</li>
            <li class="list-group-item"><strong>ملاحظات:</strong> {{ $service->notes }}</li>
        </ul>  --}}
        <hr>
        <h4>الحقول المخصصة</h4>
        <ul class="list-group mt-3">
            @if($service->department && $service->department->fields)
                @foreach($service->department->fields as $field)
                    <li class="list-group-item">
                        <strong>{{ $field->name_ar }} ({{ $field->name_en }}) :</strong>
                        @php
                            $value = $service->custom_fields[$field->name] ?? null;
                        @endphp
                        {{--  @if (isset($service->images))
                        @foreach ($service->images as $item)
                            <img width="80px" height="80px" src="{{ asset('storage/' . $item->path) }}"
                                alt="">
                        @endforeach
                        <hr>
                    @endif  --}}
                        @if($field->type === 'image' || $field->type === 'images[]')
                            @if(is_array($value))
                                @foreach($value as $img)
                                    <img src="{{ asset('storage/' . (is_object($img) && isset($img->path) ? $img->path : $img)) }}" alt="صورة" style="max-width:120px; margin:5px;">
                                @endforeach
                            @elseif($value)
                                <img src="{{ asset('storage/' . (is_object($value) && isset($value->path) ? $value->path : $value)) }}" alt="صورة" style="max-width:120px;">
                            @else
                                <span>لا يوجد صورة</span>
                            @endif
                        @else
                            {{ $value ?? '-' }}
                        @endif
                    </li>
                @endforeach
            @else
                <li class="list-group-item text-danger">لا توجد حقول مخصصة أو القسم غير مرتبط بالخدمة.</li>
            @endif
        </ul>
        @if($service->notes_voice)
            <div class="mb-3">
                <label><i class="fas fa-microphone"></i> {{ __('ملاحظة صوتية') }}</label>
                <audio controls style="display:block; margin-top:10px;">
                    <source src="{{ asset('storage/' . $service->notes_voice) }}" type="audio/wav">
                    {{ __('متصفحك لا يدعم تشغيل الصوت') }}
                </audio>
                <a href="{{ asset('storage/' . $service->notes_voice) }}" class="btn btn-success mt-2" download>
                    {{ __('تحميل التسجيل الصوتي') }}
                </a>
            </div>
        @endif
    </div>
</div>
<section class="section d-flex align-items-center justify-content-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-8">
                <div class="card mb-4">
                    <div class="card-body pb-0 align-items-center" style="height: 100%;">
                        <h5 class="mb-4 text-center">
                            <i class="fas fa-gavel"></i> {{ $lang == 'ar' ? 'العروض المقدمة' : 'Offers' }}
                        </h5>
                        <div class="mb-4">
                            <div class="container">
                                @forelse ($service->comments as $comment)
                                    <div class="border rounded p-3 mb-4 bg-light">
                                        <div class="d-flex align-items-center mb-2">
                                            <h5 class="mb-0 mr-3 text-primary">
                                                <i class="fas fa-user-circle"></i>
                                                {{ $comment->user->first_name . ' ' . $comment->user->last_name }}
                                            </h5>
                                            @if (auth()->check() && auth()->id() == $service->user_id)
                                                <a class="btn btn-outline-info btn-sm mx-2"
                                                    href="{{ route('web.send_message', $comment->user->id) }}">
                                                    <i class="fe fe-mail mx-1"></i> {{ __('messages.send_message') }}
                                                </a>
                                                <form action="{{ route('general_orders.store') }}" method="post" class="d-inline">
                                                    @csrf
                                                    <input type="hidden" name="service_id" value="{{ $service->id }}">
                                                    <input type="hidden" name="service_provider_id" value="{{ $comment->user->id }}">
                                                    <input type="hidden" name="user_id" value="{{ $comment->customer->id }}">
                                                    <input type="hidden" name="status" value="pending">
                                                    <button class="btn btn-success btn-sm" type="submit">
                                                        {{ $lang == 'ar' ? 'قبول العرض' : 'Accept Offer' }}
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                @if (isset($comment->price))
                                                    <p><strong>{{ __('general.price') }}:</strong> {{ $comment->price }}</p>
                                                @endif
                                                @if (isset($comment->body))
                                                    <p><strong>نوع الخدمة:</strong> {{ $comment->body }}</p>
                                                @endif
                                                @if (isset($comment->date))
                                                    <p><strong>{{ __('general.date') }}:</strong> {{ $comment->date }}</p>
                                                @endif
                                                @if (isset($comment->time))
                                                    <p><strong>{{ __('general.time') }}:</strong> {{ \Carbon\Carbon::parse($comment->time)->format('h:i A') }}</p>
                                                @endif
                                            </div>
                                            <div class="col-md-6">
                                                @if (isset($comment->city))
                                                    <p><strong>{{ $lang == 'ar' ? 'المدينة' : 'City' }}:</strong> {{ $comment->city }}</p>
                                                @endif
                                                @if (isset($comment->neighborhood))
                                                    <p><strong>{{ $lang == 'ar' ? 'الحي' : 'Neighborhood' }}:</strong> {{ $comment->neighborhood }}</p>
                                                @endif
                                                @if (isset($comment->location))
                                                    <p><strong>{{ $lang == 'ar' ? 'الموقع' : 'Location' }}:</strong> {{ $comment->location }}</p>
                                                @endif
                                                @if (isset($comment->day))
                                                    <p><strong>{{ __('general.day') }}:</strong> {{ $comment->day }}</p>
                                                @endif
                                                @if (isset($comment->number_of_days_of_warranty))
                                                    <p><strong>{{ $lang == 'ar' ? 'عدد ايام الضمان' : 'Number of Days of Warranty' }}:</strong> {{ $comment->number_of_days_of_warranty }}</p>
                                                @endif
                                                @if (isset($comment->notes))
                                                    <p><strong>{{ $lang == 'ar' ? 'ملاحظات عن العمل المطلوب' : 'Notes' }}:</strong> {{ $comment->notes }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="alert alert-info text-center">{{ $lang == 'ar' ? 'لا توجد عروض بعد.' : 'No offers yet.' }}</div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
                @if ($user && $user->id != $service->user_id && $service->status == 'open')
                    <div class="card mt-4">
                        <div class="card-body">
                            <p class="h5 mb-4 text-center"><i class="fas fa-plus-circle"></i> {{ $lang == 'ar' ? 'إضافة عرض جديد' : 'Add Offer' }}</p>
                            <form class="form-horizontal m-t-20" action="{{ route('general_comments.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" value="{{ $service->id }}" name="service_id">
                                <input type="hidden" value="{{$service->type}}" name="body">
                                <div class="mb-3">
                                    <label class="mb-2" for="price">{{ __('general.price') }}</label>
                                    <input class="form-control mb-2" type="number" name="price" id="price" required>
                                </div>
                                <div class="mb-3">
                                    <label class="mb-2" for="notes">{{ $lang == 'ar' ? 'ملاحظات عن العمل المطلوب' : 'Notes' }}</label>
                                    <textarea class="form-control mb-2" cols="5" rows="5" name="notes" id="notes"></textarea>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary px-4">{{ $lang == 'ar' ? 'قدم عرض' : 'Add Offer' }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
