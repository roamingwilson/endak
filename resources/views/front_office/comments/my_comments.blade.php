@extends('layouts.home')
@section('title')
    <?php $lang = config('app.locale'); ?>
    {{ $lang == 'ar' ? 'عروضي' :   "Comments"  }}
@endsection
@section('content')
    <div class="page">

        <?php $current_url = url()->current();
        $url = explode('/', $current_url);
        $id = (int) end($url);
        $department = App\Models\Department::where('id', $id)->first();
        $lang = config('app.locale');
        ?>

        <div class="main-content app-content">
            <section>
                <div class="section banner-4 banner-section">
                    <div class="container">
                        <div class="row align-items-center">
                            <div class="col-md-12 text-center">
                                <div class="">
                                    <p class="mb-3 content-1 h5 text-white">{{ $lang == 'ar' ? 'عروضي' :   "Comments" }}
                                        </p>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </section>
        </div>
            <section class="section">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-8">
                            <div class="row">
                                 {{-- @forelse ($comments as $comment)
                                    <div class="col-md-6">
                                        <div class="card">
                                            <!--<div class="position-relative">-->

                                            <!--    @if(isset($comment->files))-->
                                            <!--            @foreach ($comment->files as $item)-->

                                            <!--            <img width="100px" height="100px" src="{{ Storage::url( $item->file) }}" alt="">-->
                                            <!--            <a href="{{ Storage::url($item->file) }}" target="_blank">Download</a>-->
                                            <!--            @endforeach-->
                                            <!--    @endif-->
                                            <!--</div>-->
                                            <div class="card-body d-flex flex-column">
                                                @if(isset($comment->post_id) && isset($comment->post->title))

                                                <h5><a href="{{ route('web.posts.show' , $comment->post_id) }}">{{ $comment->post->title }}</a></h5>
                                                @endif
                                                <div class="tx-muted">{{ $comment->description }}</div>
                                                <div class="d-flex align-items-center pt-4 mt-auto">
                                                    <div class="avatar me-3 cover-image rounded-circle">
                                                        <img src="{{ $comment->post->user->image ?? asset('images/user.png') }}"
                                                            class="rounded-circle" alt="img" width="40">
                                                    </div>


                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    {!! no_data() !!}
                                @endforelse
                            </div> --}}

                            @forelse ($comments as $comment)

                            @php

                            @endphp
                            <div class="col-12 border mb-4 p-4 br-5">
                                <div class="d-flex align-items-center">
                                    <h5 class="mt-0 mr-3">
                                        {{ $comment->user->first_name . ' ' . $comment->user->last_name }}
                                    </h5>
                                    {{-- @if (auth()->check() && auth()->id() == $service->user_id) --}}
                                        <a class="dropdown-item mb-2"
                                            href="{{ route('web.send_message', $comment->user->id) }}">
                                            <i class="fe fe-mail mx-1"></i> {{ __('messages.send_message') }}
                                        </a>


                                </div>

                                @if (isset($comment->price))
                                    <p>{{ __('general.price') . ' : ' . $comment->price }}</p>
                                @endif
                                @if (isset($comment->body))
                                    <p>{{ 'Body : ' . $comment->body }}</p>
                                @endif
                                @if (isset($comment->date))
                                    <p>{{ __('general.date') . ' : ' . $comment->date }}</p>
                                @endif
                                @if (isset($comment->time))
                                    <p>{{ __('general.time') . ' : ' . \Carbon\Carbon::parse($comment->time)->format('h:i A') }}
                                    </p>
                                @endif
                                @if (isset($comment->city))
                                    <p>{{ ($lang == 'ar' ? 'المدينة' : 'City') . ' : ' . $comment->city }}</p>
                                @endif
                                @if (isset($comment->neighborhood))
                                    <p>{{ ($lang == 'ar' ? 'الحي' : 'Neighborhood') . ' : ' . $comment->neighborhood }}
                                    </p>
                                @endif
                                @if (isset($comment->location))
                                    <p>{{ ($lang == 'ar' ? 'الموقع' : 'Location') . ' : ' . $comment->location }}
                                    </p>
                                @endif
                                @if (isset($comment->day))
                                    <p>{{ __('general.day') . ' : ' . $comment->day }}</p>
                                @endif
                                @if (isset($comment->number_of_days_of_warranty))
                                    <p>{{ ($lang == 'ar' ? 'عدد ايام الضمان' : 'Number of Days of Warranty') . ' : ' . $comment->number_of_days_of_warranty }}
                                    </p>
                                @endif
                                @if (isset($comment->notes))
                                    <p>{{ ($lang == 'ar' ? 'ملاحظات عن العمل المطلوب' : 'Notes') . ' : ' . $comment->notes }}
                                    </p>
                                @endif
                            </div>
                        @empty
                            {!! no_data() !!}
                        @endforelse

                        </div>
                        {!! $comments->links() !!}


                    </div>
                </div>
            </section>

@endsection
