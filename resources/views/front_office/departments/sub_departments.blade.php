@extends('layouts.home')
@section('title', $department->name_ar ?? $department->name_en)

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header bg-primary text-white text-center">
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('departments') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left"></i> العودة للأقسام
                        </a>
                        <div>
                            <h3 class="mb-0">{{ $department->name_ar ?? $department->name_en }}</h3>
                            <p class="mb-0">اختر القسم الفرعي المناسب</p>
                        </div>
                        <div style="width: 100px;"></div> <!-- مساحة فارغة للتوازن -->
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($department->sub_departments as $subDepartment)
                            <div class="col-md-6 col-lg-4 mb-4">
                                <div class="card h-100 shadow-sm">
                                    @if($subDepartment->image)
                                        <img src="{{ asset('storage/' . $subDepartment->image) }}" class="card-img-top" alt="{{ $subDepartment->name_ar }}" style="height: 200px; object-fit: cover;">
                                    @else
                                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                            <i class="fas fa-building fa-3x text-muted"></i>
                                        </div>
                                    @endif
                                    <div class="card-body d-flex flex-column">
                                        <h5 class="card-title text-center">{{ $subDepartment->name_ar ?? $subDepartment->name_en }}</h5>
                                        <p class="card-text text-muted text-center">{{ $subDepartment->description_ar ?? $subDepartment->description_en }}</p>
                                        <div class="mt-auto text-center">
                                            <a href="{{ route('services.show', ['id' => $department->id, 'sub_department_id' => $subDepartment->id]) }}" class="btn btn-primary">
                                                <i class="fas fa-arrow-right"></i> اختر هذا القسم
                                            </a>
                                        </div>
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
