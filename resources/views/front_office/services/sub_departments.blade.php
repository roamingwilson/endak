@extends('layouts.home')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3>{{ $service->department->name_ar ?? $service->department->name_en }}</h3>
                    <p class="text-muted">اختر القسم الفرعي المناسب</p>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($service->department->sub_departments as $subDepartment)
                            <div class="col-md-6 mb-3">
                                <div class="card h-100">
                                    @if($subDepartment->image)
                                        <img src="{{ asset('storage/' . $subDepartment->image) }}" class="card-img-top" alt="{{ $subDepartment->name_ar }}" style="height: 200px; object-fit: cover;">
                                    @endif
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $subDepartment->name_ar }}</h5>
                                        <p class="card-text">{{ $subDepartment->description_ar ?? $subDepartment->description_en }}</p>
                                        <a href="{{ route('show_myservice', ['id' => $service->id, 'sub_department_id' => $subDepartment->id]) }}" class="btn btn-primary">
                                            اختر هذا القسم
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
