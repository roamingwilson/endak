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
                                 @forelse ($comments as $comment)
                                 {{-- @dd($comment) --}}
                                    <div class="col-md-6">
                                        <div class="card">
                                            <!--<div class="position-relative">-->
                                                <div class="mb-4 border p-3">
                                                    {{-- مزود الخدمة --}}
                                                    <p><strong>مزود الخدمة:</strong>
                                                        {{ $comment->service_provider ? \App\Models\User::find($comment->service_provider)->first_name ?? 'غير معروف' : 'غير معروف' }}
                                                    </p>

                                                    {{-- العميل --}}
                                                    @if ($comment->customer_id)

                                                        <p><strong>العميل:</strong> {{ \App\Models\User::find($comment->customer_id)->first_name}}</p>
                                                    @else
                                                        <p><strong>العميل:</strong> غير معروف</p>
                                                    @endif

                                                    {{-- بيانات الكومنت --}}
                                                    <p><strong>الملاحظات:</strong> {{ $comment->notes }}</p>
                                                </div>
                                            <!--    @if(isset($comment->files))-->
                                            <!--            @foreach ($comment->files as $item)-->

                                            <!--            <img width="100px" height="100px" src="{{ Storage::url( $item->file) }}" alt="">-->
                                            <!--            <a href="{{ Storage::url($item->file) }}" target="_blank">Download</a>-->
                                            <!--            @endforeach-->
                                            <!--    @endif-->
                                            <!--</div>-->



                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    {!! no_data() !!}
                                @endforelse
                            </div>

                        </div>
                        {!! $comments->links() !!}


                    </div>
                </div>
            </section>

@endsection
