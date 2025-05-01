@extends('layouts.home')
@section('title')
    <?php $lang = config('app.locale'); ?>
    {{ $lang == 'ar' ? 'عروضي' :   "Comments"  }}
@endsection
@section('content')



<div class="container py-5">
    <h2 class="text-center mb-4">{{ __('My Offers') }}</h2>

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
                        <h5 class="card-title text-primary">{{ $comment->customer->fullname  }}</h5>


                        @if (isset($comment->price))
                            <p class="card-text"><strong>{{ __('السعر') }}: </strong> {{ $comment->price }} {{ __('ر.س') }}</p>
                        @endif
                        @if (isset($service->type))
                            <p class="card-text"><strong>{{ __('قسم الخدمة') }}: </strong> {{ $service->departments->name_ar  }} {{ __('') }}</p>
                        @endif
                        @if (isset($service->equip_type))
                            <p class="card-text"><strong>{{ __('نوع المعدة او السيارة') }}: </strong> {{ $service->equip_type}} {{ __('') }}</p>
                        @endif
                        @if (isset($comment->notes))
                            <p class="card-text"><strong>{{ __('ملحوظة ') }}: </strong> {{ $comment->notes }} {{ __('') }}</p>
                        @endif

                            <p class="card-text"><strong>{{ __('التاريخ') }}: </strong>{{ $comment->created_at->diffFOrHumans() }}</p>

                        @if (isset($comment->time))
                            <p class="card-text"><strong>{{ __('الوقت') }}: </strong>{{ \Carbon\Carbon::parse($comment->time)->format('h:i A') }}</p>
                        @endif

                        <div class="d-flex justify-content-between mt-3">
                            <a href="{{ route('web.send_message', $comment->customer->id) }}" class="btn btn-outline-primary btn-sm">
                                <i class="fe fe-mail"></i> {{ __('Send Message') }}
                            </a>
                            <a class="btn btn-success btn-sm" href="{{route('general_comments.edit',$comment->id)}}">
                                <i class="fe fe-check-circle"></i> {{ __('Edit') }}
                            </a>
                            <form action="{{route('general_comments.destroy',$comment->id)}}" method="post" class="d-inline">
                                @csrf
                                @method('DELETE')

                                <button class="btn btn-danger btn-sm" type="submit">
                                    <i class="fe fe-check-circle"></i> {{ __('delete') }}
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
