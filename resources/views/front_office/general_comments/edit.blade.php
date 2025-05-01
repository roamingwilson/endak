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
<div class="card mt-4">
    <div class="card-body">
        <p class="h5 mb-4">{{ $lang == 'ar' ? 'تعديل العرض' : 'Edit Offer' }}</p>

        <form action="{{ route('general_comments.update', $comment->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')



            {{-- السعر --}}
            <div class="form-group mb-3">
                <label for="price">{{ __('general.price') }}</label>
                <input type="text" name="price" id="price" class="form-control" value="{{ $comment->price }}" placeholder="{{ $lang == 'ar' ? 'أدخل السعر' : 'Enter price' }}">
            </div>

            {{-- الملاحظات --}}
            <div class="form-group mb-4">
                <label for="notes">{{ $lang == 'ar' ? 'ملاحظات عن العمل المطلوب' : 'Notes about the required work' }}</label>
                <textarea name="notes" id="notes" class="form-control" rows="4" placeholder="{{ $lang == 'ar' ? 'اكتب ملاحظاتك هنا' : 'Write your notes here' }}">{{ $comment->notes }}</textarea>
            </div>

            {{-- زر التحديث --}}
            <button type="submit" class="btn btn-success">
                {{ $lang == 'ar' ? 'تحديث' : 'Update' }}
            </button>
        </form>
    </div>
</div>
@endif

@endsection
