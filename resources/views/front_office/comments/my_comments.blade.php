@extends('layouts.home')
@section('title')
    <?php $lang = config('app.locale'); ?>
    {{ $lang == 'ar' ? 'عروضي' :   "Comments"  }}
@endsection
@section('content')



<div class="container py-5">
    <div class="text-center mb-5">
        <h2 class="fw-bold mb-2" style="color: #ff9800;"><i class="fas fa-handshake me-2"></i>{{ $lang == 'ar' ? 'العروض المقدمة' : 'All Offers' }}</h2>
        <p class="text-muted">{{ $lang == 'ar' ? 'كل العروض التي تم تقديمها على الطلبات' : 'All offers you have made on requests.' }}</p>
    </div>
    <div class="row justify-content-center">
        @foreach ($comments as $comment)
        @php
            $service = $comment->commentable;
        @endphp
        @if ($service && $service->status !== 'pending')
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card shadow border-0 rounded-4 h-100 offer-card">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex align-items-center mb-2">
                            <span class="badge bg-primary bg-opacity-10 text-primary fs-5 p-2 rounded-circle"><i class="fas fa-user"></i></span>
                            <div class="ms-3">
                                <h5 class="card-title fw-bold mb-0 text-primary">{{ $lang == 'ar' ? 'العميل' : 'Customer' }} : {{ $comment->customer->fullname }}</h5>
                                <small class="text-muted"><i class="far fa-clock me-1"></i>{{ $comment->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                        <ul class="list-unstyled mb-3 mt-2">
                            @if (isset($comment->price))
                                <li><i class="fas fa-money-bill-wave text-success me-1"></i> <span class="fw-semibold">{{ $lang == 'ar' ? 'السعر' : 'Price' }}:</span> {{ $comment->price }} {{ $lang == 'ar' ? 'ر.س' : 'SAR' }}</li>
                            @endif
                            @if (isset($service->type))
                                <li><i class="fas fa-layer-group text-warning me-1"></i> <span class="fw-semibold">{{ $lang == 'ar' ?'قسم الخدمة' : 'Department' }}:</span> {{ ($lang == 'ar') ? $service->departments->name_ar : $service->departments->name_en }}</li>
                            @endif
                            @if (isset($service->subDepartment) && $service->subDepartment)
                                <li><i class="fas fa-sitemap text-success me-1"></i> <span class="fw-semibold">{{ $lang == 'ar' ? 'القسم الفرعي' : 'Sub-Department' }}:</span> {{ $lang == 'ar' ? $service->subDepartment->name_ar : $service->subDepartment->name_en }}</li>
                            @endif
                            @if (isset($service->equip_type))
                                <li><i class="fas fa-cogs text-info me-1"></i> <span class="fw-semibold">{{ $lang == 'ar' ? 'نوع المعدة او السيارة' : 'Equipment Type' }}:</span> {{ $service->equip_type }}</li>
                            @endif
                            @if (isset($comment->notes))
                                <li><i class="fas fa-sticky-note text-secondary me-1"></i> <span class="fw-semibold">{{ $lang == 'ar' ? 'ملحوظة' : 'Note' }}:</span> {{ $comment->notes }}</li>
                            @endif
                        </ul>
                        <div class="d-flex justify-content-between align-items-center mt-auto gap-2">
                            <a href="{{ route('web.send_message', $comment->customer->id) }}" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                                <i class="fe fe-mail"></i> {{ $lang == 'ar' ? 'ارسال رسالة' : 'Send Message' }}
                            </a>
                            <a class="btn btn-success btn-sm rounded-pill px-3" href="{{route('general_comments.edit',$comment->id)}}">
                                <i class="fe fe-check-circle"></i> {{ $lang == 'ar' ? 'تعديل' : 'Edit' }}
                            </a>
                            <form action="{{route('general_comments.destroy',$comment->id)}}" method="post" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm rounded-pill px-3" type="submit">
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
