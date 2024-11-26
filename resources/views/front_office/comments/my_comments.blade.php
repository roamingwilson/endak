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
                            </div>

                        </div>
                        {!! $comments->links() !!}


                    </div>
                </div>
            </section>

@endsection
