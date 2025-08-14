@extends('layouts.home')

@section('title')
    {{ app()->getLocale() == 'ar' ? 'إدارة المدن' : 'Manage Cities' }}
@endsection

@section('content')
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow border-0 rounded-4">
                    <div class="card-header bg-primary text-white text-center py-3">
                        <h4 class="mb-0">
                            <i class="fas fa-map-marker-alt me-2"></i>
                            {{ app()->getLocale() == 'ar' ? 'إدارة المدن التي يمكنني العمل فيها' : 'Manage Cities I Can Work In' }}
                        </h4>
                    </div>
                    <div class="card-body p-4">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle me-2"></i>
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form action="{{ route('provider.cities.update') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-4">
                                <p class="text-muted">
                                    <i class="fas fa-info-circle me-2"></i>
                                    {{ app()->getLocale() == 'ar'
                                        ? 'اختر المدن التي يمكنك تقديم الخدمة فيها. ستظهر لك الطلبات في هذه المدن فقط.'
                                        : 'Select the cities where you can provide services. You will only see orders from these cities.' }}
                                </p>
                            </div>

                            <div class="row">
                                @foreach($cities as $city)
                                    <div class="col-md-6 col-lg-4 mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox"
                                                   name="cities[]"
                                                   value="{{ $city->id }}"
                                                   id="city_{{ $city->id }}"
                                                   {{ $selectedCities->where('governement_id', $city->id)->count() > 0 ? 'checked' : '' }}>
                                            <label class="form-check-label" for="city_{{ $city->id }}">
                                                <i class="fas fa-city me-2 text-primary"></i>
                                                {{ app()->getLocale() == 'ar' ? $city->name_ar : $city->name_en }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            @error('cities')
                                <div class="alert alert-danger mt-3">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    {{ $message }}
                                </div>
                            @enderror

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                                <a href="{{ route('home') }}" class="btn btn-secondary me-md-2">
                                    <i class="fas fa-arrow-left me-2"></i>
                                    {{ app()->getLocale() == 'ar' ? 'رجوع' : 'Back' }}
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>
                                    {{ app()->getLocale() == 'ar' ? 'حفظ التغييرات' : 'Save Changes' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

