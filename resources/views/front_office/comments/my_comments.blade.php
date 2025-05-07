@extends('layouts.home')
@section('title')
    <?php $lang = config('app.locale'); ?>
    {{ $lang == 'ar' ? 'عروضي' :   "Comments"  }}
@endsection
@section('content')



<div class="container py-5">
    <h2 class="text-center mb-4"> {{ $lang == 'ar' ? 'عروضي' :   "My Offers"  }}</h2>

    <!-- عرض العروض مع تنظيم الألوان والخطوط -->
    <div class="row justify-content-center">
        @foreach ($comments as $comment)
        {{-- الحصول على الخدمة المرتبطة بالتعليق --}}
        @php
            $service = $comment->commentable; // خدمة مرتبطة بالتعليق

        @endphp

        {{-- إخفاء العروض التي تكون حالة الخدمة فيها "pending" --}}
        @if ($service && $service->status !== 'pending') <!-- إخفاء العروض التي حالتها "pending" -->
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card shadow-sm border-0 rounded">
                    <div class="card-body">
                        <h5 class="card-title text-primary">{{ $lang == 'ar' ? 'العميل' : 'Customer' }} : {{ $comment->customer->fullname  }}</h5>


                        @if (isset($comment->price))
                            <p class="card-text"><strong>{{ $lang == 'ar' ? 'السعر' : 'Price' }}: </strong> {{ $comment->price }} {{ $lang == 'ar' ? 'ر.س' : 'SAR' }}</p>
                        @endif
                        @if (isset($service->type))
                            <p class="card-text"><strong>{{ $lang == 'ar' ?'قسم الخدمة' : 'Department' }}: </strong> {{ ($lang == 'ar') ? $service->departments->name_ar : $service->departments->name_en }}</p>
                        @endif
                        @if (isset($service->equip_type))
                            <p class="card-text"><strong>{{ $lang == 'ar' ? 'نوع المعدة او السيارة' : 'Equipment Type' }}: </strong> {{ $service->equip_type}} {{ __('') }}</p>
                        @endif
                        @if (isset($comment->notes))
                            <p class="card-text"><strong>{{ $lang == 'ar' ? 'ملحوظة' : 'note' }}: </strong> {{ $comment->notes }} {{ __('') }}</p>
                        @endif

                            <p class="card-text"><strong>{{ $lang == 'ar' ? 'الوقت' : 'Time' }}: </strong>{{ $comment->created_at->diffFOrHumans() }}</p>



                        <div class="d-flex justify-content-between mt-3">
                            <a href="{{ route('web.send_message', $comment->customer->id) }}" class="btn btn-outline-primary btn-sm">
                                <i class="fe fe-mail"></i> {{ $lang == 'ar' ? 'ارسال رسالة' : 'send message' }}
                            </a>
                            <a class="btn btn-success btn-sm" href="{{route('general_comments.edit',$comment->id)}}">
                                <i class="fe fe-check-circle"></i> {{ $lang == 'ar' ? 'تعديل' : 'Edit' }}
                            </a>
                            <form action="{{route('general_comments.destroy',$comment->id)}}" method="post" class="d-inline">
                                @csrf
                                @method('DELETE')

                                <button class="btn btn-danger btn-sm" type="submit">
                                    <i class="fe fe-check-circle"></i> {{ $lang == 'ar' ? 'حذف العرض' : 'Delete' }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endforeach
    </div>

    <!-- التنقل بين الصفحات -->
    <div class="d-flex justify-content-center mt-4">
        {{ $comments->links() }}
    </div>
</div>
@endsection
@section('style')
<style>
            @media (max-width: 768px) {
    body {
        padding-top: 90px; /* عشان الـ navbar ما يغطيش الصفحة */
    }
}

@media (min-width: 769px) {
    body {
        padding-top: 70px; /* أو حسب ارتفاع الـ navbar */
    }
}
</style>

@endsection
