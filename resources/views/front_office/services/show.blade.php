@extends('layouts.home')
@section('title', 'تفاصيل الخدمة')
<?php $lang = config('app.locale'); ?>
@php $user = auth()->user(); @endphp
@php
    $groupedFields = $service->department && $service->department->fields ? $service->department->fields->groupBy('input_group') : collect();
@endphp
@php
    $allowedMain = auth()->user()?->getAllDepartments()['main']->pluck('id')->toArray() ?? [];
    $allowedSub = auth()->user()?->getAllDepartments()['sub']->pluck('id')->toArray() ?? [];
    // عدّل حسب متغيرات القسم/القسم الفرعي في الصفحة:
    $serviceDepartmentId = $service->department_id ?? null;
    $serviceSubDepartmentId = $service->sub_department_id ?? null;
    if ($serviceSubDepartmentId) {
        $isAllowed = in_array($serviceSubDepartmentId, $allowedSub);
    } else {
        $isAllowed = in_array($serviceDepartmentId, $allowedMain);
    }
@endphp
@php
    $myOffer = null;
    if(auth()->check() && auth()->user()->role_id == 3) {
        $myOffer = $service->comments->where('service_provider', auth()->id())->first();
    }
@endphp
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
    <div class="card p-4">
        {{-- بيانات الخدمة الأساسية --}}
        <div class="mb-4">
            <div class="row g-3">
                @if($service->department)
                    <div class="col-md-6">
                        <div class="d-flex align-items-center gap-2" style="background: #fff; border-radius: 8px; padding: 12px; box-shadow: 0 1px 4px #e3e8ef;">
                            <span style="font-weight:bold; color:#1976d2; min-width: 100px;">
                                <i class="fas fa-folder me-1"></i> {{ app()->getLocale() == 'ar' ? 'القسم' : 'Department' }}
                            </span>
                            <span style="flex:1; font-size:1.08em; color:#333;">{{ app()->getLocale() == 'ar' ? $service->department->name_ar : $service->department->name_en }}</span>
                        </div>
                    </div>
                @endif

                @if($service->selected_sub_department_id && $service->department && $service->department->sub_departments)
                    @php
                        $selectedSubDepartment = $service->department->sub_departments->where('id', $service->selected_sub_department_id)->first();
                    @endphp
                    @if($selectedSubDepartment)
                        <div class="col-md-6">
                            <div class="d-flex align-items-center gap-2" style="background: #fff; border-radius: 8px; padding: 12px; box-shadow: 0 1px 4px #e3e8ef;">
                                <span style="font-weight:bold; color:#1976d2; min-width: 100px;">
                                    <i class="fas fa-layer-group me-1"></i> {{ app()->getLocale() == 'ar' ? 'القسم الفرعي' : 'Sub Department' }}
                                </span>
                                <span style="flex:1; font-size:1.08em; color:#333;">{{ app()->getLocale() == 'ar' ? $selectedSubDepartment->name_ar : $selectedSubDepartment->name_en }}</span>
                            </div>
                        </div>

                        @endif

                        @endif

                @if($service->from_city)
                    <div class="col-md-6">
                        <div class="d-flex align-items-center gap-2" style="background: #fff; border-radius: 8px; padding: 12px; box-shadow: 0 1px 4px #e3e8ef;">
                            <span style="font-weight:bold; color:#1976d2; min-width: 100px;">
                                <i class="fas fa-map-marker-alt me-1"></i> {{ app()->getLocale() == 'ar' ? 'من المدينة' : 'From City' }}
                            </span>
                            <span style="flex:1; font-size:1.08em; color:#333; word-break:break-word; max-width:180px; display:inline-block;">
                                {{ $service->from_city }}
                            </span>
                        </div>
                    </div>
                @endif



            </div>
        </div>
        <hr>
        {{--  <h4>الحقول المخصصة</h4>  --}}
        @if($groupedFields->count())
            <div class="mb-4">
                <h4 class="text-center mb-3" style="color:#1976d2; font-weight:bold;">
                    {{ app()->getLocale() == 'ar' ? 'الخدمات المطلوبة' : 'Requested Services' }}
                </h4>
            @foreach($groupedFields as $group => $fields)
                @php
                    $repeatable = $fields->first()->is_repeatable ?? false;
                    $groupValues = $service->custom_fields[$group] ?? [];
                @endphp
                @if($group)
                    <div class="card mb-4 shadow-sm border-0" style="background: #f8fafc;">
                        <div class="card-header bg-info text-white text-center" style="font-size:1.1rem; font-weight:bold; border-radius: 8px 8px 0 0;">
                            {{ $group }}
                        </div>
                        <div class="card-body py-3">
                            @if($repeatable && is_array($groupValues) && count($groupValues))
                                @foreach($groupValues as $idx => $groupInstance)
                                    <div class="row g-3 align-items-center mb-3" style="border-bottom:1px dashed #b3e5fc;">
                                        <div class="col-12 mb-2">
                                            <span class="badge bg-primary">مجموعة رقم {{ $idx+1 }}</span>
                                        </div>
                                        @foreach($fields as $field)
                                            @php $value = $groupInstance[$field->name] ?? null; @endphp
                                            @if($field->type === 'checkbox' && !$value)
                                                @continue
                                            @endif
                                            <div class="col-md-3 mb-2 d-flex align-items-center gap-2" style="background: #fff; border-radius: 8px; padding: 10px 12px; box-shadow: 0 1px 4px #e3e8ef;">
                                                <span style="font-weight:bold; color:#1976d2; min-width: 90px; display: flex; align-items: center;">
                                                    <i class="fas fa-tag me-1"></i> {{ app()->getLocale() == 'ar' ? $field->name_ar : $field->name_en }}
                                                </span>
                                                <span style="flex:1; font-size:1.08em; color:#333;">
                                                    @if($field->type === 'image' || $field->type === 'images[]')
                                                        @if(is_array($value))
                                                            @foreach($value as $img)
                                                                <img src="{{ asset('storage/' . (is_object($img) && isset($img->path) ? $img->path : $img)) }}" alt="صورة" style="max-width:80px; margin:3px; border-radius:6px;">
                                                            @endforeach
                                                        @elseif($value)
                                                            <img src="{{ asset('storage/' . (is_object($value) && isset($value->path) ? $value->path : $value)) }}" alt="صورة" style="max-width:80px; border-radius:6px;">
                                                        @else
                                                            <span class="text-muted">{{ app()->getLocale() == 'ar' ? 'لا يوجد صورة' : 'No image' }}</span>
                                                        @endif
                                                    @elseif($field->type === 'checkbox')
                                                        <span>{!! $value ? '<i class="fas fa-check-circle text-success"></i> ' . (app()->getLocale() == 'ar' ? 'نعم' : 'Yes') : '<i class="fas fa-times-circle text-danger"></i> ' . (app()->getLocale() == 'ar' ? 'لا' : 'No') !!}</span>
                                                    @elseif($field->type === 'select' && is_array($field->options))
                                                        <span>{{ $value ?? '-' }}</span>
                                                    @elseif($field->type === 'date')
                                                        <span>{{ $value ? \Carbon\Carbon::parse($value)->format('Y-m-d') : '-' }}</span>
                                                    @elseif($field->type === 'time')
                                                        <span>{{ $value ? \Carbon\Carbon::parse($value)->format('H:i') : '-' }}</span>
                                                    @elseif($field->type === 'textarea')
                                                        <div style="white-space: pre-line; color:#444;">{{ $value ?? '-' }}</div>
                                                    @else
                                                        <span>{{ $value ?? '-' }}</span>
                                                    @endif
                                                </span>
                                            </div>
                                        @endforeach
                                    </div>
                                @endforeach
                            @elseif($repeatable)
                                <div class="alert alert-info text-center">لا توجد مجموعات مكررة مدخلة.</div>
                            @else
                                <div class="row g-3 align-items-center">
                                    @foreach($fields as $field)
                                        @php $value = $service->custom_fields[$field->name] ?? null; @endphp
                                        @if($field->type === 'checkbox' && !$value)
                                            @continue
                                        @endif
                                        <div class="col-md-3 mb-2 d-flex align-items-center gap-2" style="background: #fff; border-radius: 8px; padding: 10px 12px; box-shadow: 0 1px 4px #e3e8ef;">
                                            <span style="font-weight:bold; color:#1976d2; min-width: 90px; display: flex; align-items: center;">
                                                <i class="fas fa-tag me-1"></i> {{ app()->getLocale() == 'ar' ? $field->name_ar : $field->name_en }}
                                            </span>
                                                                            <span style="flex:1; font-size:1.08em; color:#333;">
                                    @if($field->type === 'title')
                                        <div class="alert alert-info mb-0" style="font-size: 0.9rem; padding: 8px 12px;">
                                            <i class="fas fa-heading"></i> {{ $field->value ?? (app()->getLocale() == 'ar' ? $field->name_ar : $field->name_en) }}
                                        </div>
                                    @elseif($field->type === 'image' || $field->type === 'images[]')
                                                    @if(is_array($value))
                                                        @foreach($value as $img)
                                                            <img src="{{ asset('storage/' . (is_object($img) && isset($img->path) ? $img->path : $img)) }}" alt="صورة" style="max-width:80px; margin:3px; border-radius:6px;">
                                                        @endforeach
                                                    @elseif($value)
                                                        <img src="{{ asset('storage/' . (is_object($value) && isset($value->path) ? $value->path : $value)) }}" alt="صورة" style="max-width:80px; border-radius:6px;">
                                                    @else
                                                        <span class="text-muted">{{ app()->getLocale() == 'ar' ? 'لا يوجد صورة' : 'No image' }}</span>
                                                    @endif
                                                @elseif($field->type === 'checkbox')
                                                    <span>{!! $value ? '<i class="fas fa-check-circle text-success"></i> ' . (app()->getLocale() == 'ar' ? 'نعم' : 'Yes') : '<i class="fas fa-times-circle text-danger"></i> ' . (app()->getLocale() == 'ar' ? 'لا' : 'No') !!}</span>
                                                @elseif($field->type === 'select' && is_array($field->options))
                                                    <span>{{ $value ?? '-' }}</span>
                                                @elseif($field->type === 'date')
                                                    <span>{{ $value ? \Carbon\Carbon::parse($value)->format('Y-m-d') : '-' }}</span>
                                                @elseif($field->type === 'time')
                                                    <span>{{ $value ? \Carbon\Carbon::parse($value)->format('H:i') : '-' }}</span>
                                                @elseif($field->type === 'textarea')
                                                    <div style="white-space: pre-line; color:#444;">{{ $value ?? '-' }}</div>
                                                @else
                                                    <span>{{ $value ?? '-' }}</span>
                                                @endif
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            @endforeach
        @endif

        {{-- الحقول العادية (غير مجمعة) --}}
        @if($groupedFields->has(null))
            <div class="mb-4">
            <div class="card mb-4 shadow-sm border-0" style="background: #f8fafc;">
                <div class="card-header bg-secondary text-white text-center" style="font-size:1.1rem; font-weight:bold; border-radius: 8px 8px 0 0;">
                    {{ app()->getLocale() == 'ar' ? 'معلومات إضافية' : 'Additional Info' }}
                </div>
                <div class="card-body py-3">
                    <div class="row g-3 align-items-center">
                        @foreach($groupedFields[null] as $field)
                            <div class="col-12 mb-2 d-flex align-items-center gap-2" style="background: #fff; border-radius: 8px; padding: 10px 12px; box-shadow: 0 1px 4px #e3e8ef;">
                                <span style="font-weight:bold; color:#1976d2; min-width: 90px; display: flex; align-items: center;">
                                    <i class="fas fa-tag me-1"></i> {{ app()->getLocale() == 'ar' ? $field->name_ar : $field->name_en }}
                                </span>
                                <span style="flex:1; font-size:1.08em; color:#333;">
                                    @php $value = $service->custom_fields[$field->name] ?? null; @endphp
                                    @if($field->type === 'image' || $field->type === 'images[]')
                                        @if(is_array($value))
                                            @foreach($value as $img)
                                                <img src="{{ asset('storage/' . (is_object($img) && isset($img->path) ? $img->path : $img)) }}" alt="صورة" style="max-width:80px; margin:3px; border-radius:6px;">
                                            @endforeach
                                        @elseif($value)
                                            <img src="{{ asset('storage/' . (is_object($value) && isset($value->path) ? $value->path : $value)) }}" alt="صورة" style="max-width:80px; border-radius:6px;">
                                        @else
                                            <span class="text-muted">{{ app()->getLocale() == 'ar' ? 'لا يوجد صورة' : 'No image' }}</span>
                                        @endif
                                    @elseif($field->type === 'checkbox')
                                        <span>{!! $value ? '<i class="fas fa-check-circle text-success"></i> ' . (app()->getLocale() == 'ar' ? 'نعم' : 'Yes') : '<i class="fas fa-times-circle text-danger"></i> ' . (app()->getLocale() == 'ar' ? 'لا' : 'No') !!}</span>
                                    @elseif($field->type === 'select' && is_array($field->options))
                                        <span>{{ $value ?? '-' }}</span>
                                    @elseif($field->type === 'date')
                                        <span>{{ $value ? \Carbon\Carbon::parse($value)->format('Y-m-d') : '-' }}</span>
                                    @elseif($field->type === 'time')
                                        <span>{{ $value ? \Carbon\Carbon::parse($value)->format('H:i') : '-' }}</span>
                                    @elseif($field->type === 'textarea')
                                        <div style="white-space: pre-line; color:#444;">{{ $value ?? '-' }}</div>
                                    @else
                                        <span>{{ $value ?? '-' }}</span>
                                    @endif
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        @if($service->notes)
            <div class="mb-3">
                <div class="d-flex align-items-center gap-2" style="background: #fff; border-radius: 8px; padding: 12px; box-shadow: 0 1px 4px #e3e8ef;">
                    <span style="font-weight:bold; color:#28a745; min-width: 100px;">
                        <i class="fas fa-sticky-note me-1"></i> {{ app()->getLocale() == 'ar' ? 'ملاحظات' : 'Notes' }}
                    </span>
                    <span style="flex:1; font-size:1.08em; color:#333; white-space: pre-line;">
                        {{ $service->notes }}
                    </span>
                </div>
            </div>
        @endif

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
@if (auth()->check() && auth()->id() == $service->user_id)
<section class="section d-flex align-items-center justify-content-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-8">
                <div class="card mb-4">
                                    <div class="card-body pb-0 align-items-center" style="height: 100%;">
                    <h5 class="mb-4 text-center">
                        <i class="fas fa-gavel"></i> {{ $lang == 'ar' ? 'العروض' : 'Offers' }}
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
                </div>
            </div>
        </div>
    </section>
@endif

{{-- خانة إضافة عرض جديد تظهر للجميع ما عدا صاحب الخدمة --}}
@if (!auth()->check() || (auth()->id() != $service->user_id))
    @if(auth()->check() && auth()->user()->role_id == 3 && $myOffer)
        <div class="alert alert-info text-center my-4">
            <i class="fas fa-check-circle text-success"></i>
            {{ $lang == 'ar' ? 'لقد قدمت عرضاً لهذه الخدمة بالفعل.' : 'You have already submitted an offer for this service.' }}<br>
            <span class="fw-bold">{{ $lang == 'ar' ? 'السعر:' : 'Price:' }}</span> {{ $myOffer->price ?? '-' }}<br>
            <span class="fw-bold">{{ $lang == 'ar' ? 'ملاحظات:' : 'Notes:' }}</span> {{ $myOffer->notes ?? '-' }}<br>
            <span class="fw-bold">{{ $lang == 'ar' ? 'تاريخ التقديم:' : 'Submitted at:' }}</span> {{ $myOffer->created_at ? $myOffer->created_at->format('Y-m-d H:i') : '-' }}
        </div>
    @elseif (!auth()->check())
        <div class="alert alert-info text-center my-4">
            {{ $lang == 'ar' ? 'لتقديم عرض سجل الآن' : 'To submit an offer, please login.' }}
            <a href="{{ route('login-page') }}" class="btn btn-primary mx-2">{{ $lang == 'ar' ? 'تسجيل الدخول' : 'Login' }}</a>
        </div>
    @else
        <div class="d-flex justify-content-center my-4">
            <div class="card shadow-lg" style="max-width: 420px; width:100%; border-radius: 18px;">
                <div class="card-body p-4">
                    <div class="text-center mb-3">
                        <h4 class="mt-2 mb-3" style="font-weight:bold; color:#1976d2;">{{ $lang == 'ar' ? 'إضافة عرض' : 'Add Offer' }}</h4>
                    </div>
                            <form class="form-horizontal m-t-20" action="{{ route('general_comments.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" value="{{ $service->id }}" name="service_id">
                                <input type="hidden" value="{{$service->type}}" name="body">
                                <div class="mb-3">
                            <label class="mb-2" for="price" style="font-weight:500;">{{ __('general.price') }}</label>
                            <input class="form-control mb-2 py-2" type="number" name="price" id="price" required placeholder="{{ $lang == 'ar' ? 'أدخل السعر المقترح' : 'Enter your offer price' }}">
                                </div>
                                <div class="mb-3">
                            <label class="mb-2" for="notes" style="font-weight:500;">{{ $lang == 'ar' ? 'ملاحظات عن العمل المطلوب' : 'Notes' }}</label>
                            <textarea class="form-control mb-2 py-2" cols="5" rows="4" name="notes" id="notes" placeholder="{{ $lang == 'ar' ? 'اكتب تفاصيل العرض أو أي ملاحظات' : 'Write your offer details or notes' }}"></textarea>
                                </div>
                        <button type="submit" class="btn btn-success btn-lg w-100 my-2" style="font-size:1.2em; border-radius: 8px;">
                            <i class="fas fa-paper-plane"></i> {{ $lang == 'ar' ? 'قدم عرض' : 'Add Offer' }}
                        </button>
                        <div class="text-muted small mt-2 text-center">
                            <i class="fas fa-info-circle"></i> {{ $lang == 'ar' ? 'العروض تظهر للعميل فقط بعد الموافقة.' : 'Offers are visible to the client only after approval.' }}
                                </div>
                            </form>
                        </div>
            </div>
        </div>
    @endif
@endif
@endsection
