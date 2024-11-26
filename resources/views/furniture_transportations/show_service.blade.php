@extends('layouts.home')
@section('title')
    {{ __('department.departments') }}
@endsection

@section('content')
    <?php
    $lang = config('app.locale');
    ?>
    <div class="main-content app-content">
        <section>
            <div class="section banner-4 banner-section">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-md-12 text-center">
                            <div class="">
                                <p class="mb-3 content-1 h5 text-white">{{ ($lang == 'ar')? 'نقل عفش' : 'Furniture Transportations' }} 
                                    {{-- <span
                                        class="tx-info-dark">{{ __('general.center') }}
                                    </span> --}}
                                    </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    @if (auth()->user()->role_id == 3)
        <section class="section">
            <div class="container">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="row">
                            @forelse ($services as $service)
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="position-relative">
                                            <a href="{{ route('departments.show', $service->id) }}">
                                                @if ($service->image)
                                                    <img class="card-img-top" src="{{ $service->image_url }}" alt="img"
                                                        width="300" height="300">
                                                @else
                                                    <img class="card-img-top" src="{{ asset('images/logo.jpg') }}"
                                                        alt="nn" width="300" height="300">
                                                @endif
                                            </a>
                                        </div>
                                        <div class="card-body d-flex flex-column">
                                            <h5><a href="{{ route('departments.show', $service->id) }}">
                                                    {{ $lang == 'ar' ? $service->name_ar : $service->name_en }}</a></h5>
                                            <div class="tx-muted">
                                                {{ $lang == 'ar' ? $service->description_ar : $service->description_en }}
                                            </div>

                                        </div>
                                    </div>
                                </div>

                            @empty
                                {!! no_data() !!}
                            @endforelse
                        </div>
                        {!! $services->links() !!}
                    </div>
                </div>
        </section>
    @else
        <section class="profile-cover-container mb-2" >
            <div class="main-content app-content">
                {{-- <section>
                    <div class="section banner-4 banner-section">
                        <div class="container">
                            <div class="row align-items-center">
                                <div class="col-md-12 text-center">
                                    <div class="">
                                        <p class="mb-3 content-1 h5 text-white">{{ __('posts.my_posts') }} 
                                        </p>
                                       
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
    
                </section> --}}
    
                <section class="section">
                    <div class="container">
                        <div class="row">
                            <div class="col-xl-8">
                                <div class="row">
                                  <?php $furniture_transportation_services = App\Models\FurnitureTransportationService::get(); ?>
                                    @forelse ($furniture_transportation_services as $furniture_transportation_service)
                                        <div class="col-md-6">
                                            <div class="card">
                                                <div class="position-relative">
                                                    
                                                    
                                                    <span class="badge bg-secondary blog-badge">{{ $furniture_transportation_service->add_order }}</span>
                                                </div>
                                                <div class="card-body d-flex flex-column">
                                                    <h5><a href="{{ route('main_furniture_transportations_show_my_service' , $furniture_transportation_service->id) }}">    {{ ($lang == 'ar')? 'نقل عفش' : 'Furniture Transportations' }} </a></h5>
                                                    <div class="tx-muted">    {{ ($lang == 'ar')? 'نقل عفش' : 'Furniture Transportations' }}
                                                    </div>
                                                    <div class="d-flex align-items-center pt-4 mt-auto">
                                                        <div class="avatar me-3 cover-image rounded-circle">
                                                            <img src="{{ $furniture_transportation_service->user->image ?? asset('images/user.png') }}"
                                                                class="rounded-circle" alt="img" width="40">
                                                        </div>
                                                        <div>
                                                            <a href="javascript:void(0);"
                                                                class="h6">{{ $furniture_transportation_service->user->first_name . ' ' . $furniture_transportation_service->user->last_name }}</a>
                                                            <small
                                                                class="d-block tx-muted">{{ $furniture_transportation_service->created_at->shortAbsoluteDiffForHumans() }}</small>
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
                            
                        </div>
                    </div>
                </section>
            </div>


        </section>
    @endif
@endsection
@section('script')
    <script src="https://cdn.jsdelivr.net/npm/resumablejs@1.1.0/resumable.min.js"></script>
    <script>
        document.querySelectorAll('input[type=checkbox][name="selected_products[]"]').forEach((checkbox) => {
            checkbox.addEventListener('change', function() {
                const quantityInput = document.getElementById(`quantity-${this.value}`);
                if (this.checked) {
                    quantityInput.style.display = 'block';
                } else {
                    quantityInput.style.display = 'none';
                    quantityInput.value = '';
                }
            });
        });
    </script>
@endsection
