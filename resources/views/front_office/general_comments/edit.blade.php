@extends('layouts.home')
@section('title')
    <?php $lang = config('app.locale'); ?>
         {{ ($lang == 'ar')?  ' تعديل العرض' : "air condition" }}


@endsection
@php
    $user = auth()->user();
@endphp
@section('content')
@if ($comment && $user && $user->id == $comment->service_provider)
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow border-0 rounded-4">
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <span class="d-inline-block mb-2" style="font-size:2.5rem;color:#ff9800;"><i class="fas fa-edit"></i></span>
                        <h3 class="fw-bold mb-1">{{ $lang == 'ar' ? 'تعديل العرض' : 'Edit Offer' }}</h3>
                        <p class="text-muted mb-0">{{ $lang == 'ar' ? 'يمكنك تعديل تفاصيل العرض المقدم للعميل' : 'You can edit the details of your offer to the customer.' }}</p>
                    </div>
                    <form action="{{ route('general_comments.update', $comment->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="price" class="form-label fw-semibold"><i class="fas fa-money-bill-wave text-success me-1"></i> {{ __('general.price') }}</label>
                            <input type="text" name="price" id="price" class="form-control rounded-pill" value="{{ $comment->price }}" placeholder="{{ $lang == 'ar' ? 'أدخل السعر' : 'Enter price' }}">
                        </div>
                        <div class="mb-4">
                            <label for="notes" class="form-label fw-semibold"><i class="fas fa-sticky-note text-secondary me-1"></i> {{ $lang == 'ar' ? 'ملاحظات عن العمل المطلوب' : 'Notes about the required work' }}</label>
                            <textarea name="notes" id="notes" class="form-control rounded-3" rows="4" placeholder="{{ $lang == 'ar' ? 'اكتب ملاحظاتك هنا' : 'Write your notes here' }}">{{ $comment->notes }}</textarea>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-success btn-lg rounded-pill">
                                <i class="fas fa-save me-2"></i>{{ $lang == 'ar' ? 'تحديث' : 'Update' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

@endsection
