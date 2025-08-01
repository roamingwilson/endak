@extends('layouts.dashboard.dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-plus-circle"></i> إضافة حقل جديد إلى {{ $department->name_ar }}
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.departments.fields.index', $department->id) }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> العودة للحقول
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h5><i class="icon fas fa-ban"></i> خطأ في التحقق!</h5>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.departments.fields.store', $department->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="department_id" value="{{ $department->id }}">

                        <!-- Sub Department Selection -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="sub_department_id" class="form-label">
                                        <i class="fas fa-sitemap"></i> القسم الفرعي (اختياري)
                                    </label>
                                    <select name="sub_department_id" id="sub_department_id" class="form-control @error('sub_department_id') is-invalid @enderror">
                                        <option value="">اختر القسم الفرعي</option>
                                        @foreach($subDepartments as $sub)
                                            <option value="{{ $sub->id }}" {{ $selectedSubDepartmentId == $sub->id ? 'selected' : '' }}>
                                                {{ $sub->name_ar }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('sub_department_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-plus"></i> إضافة قسم فرعي جديد
                                    </label>
                                    <a href="{{ route('admin.sub_departments.create') }}" class="btn btn-outline-primary btn-block">
                                        <i class="fas fa-plus"></i> إضافة قسم فرعي جديد
                                    </a>
                                </div>
                            </div>
                        </div>

                        @include('admin.department_fields._dynamic_field_form', ['field' => null])
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Field Names Reference -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-book"></i> مرجع أسماء الحقول الشائعة
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped text-center">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Field Name (English)</th>
                                    <th>اسم الحقل (بالعربية)</th>
                                    <th>الاستخدام</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr><td>neighborhood</td><td>الحي</td><td>للتوصيل والخدمات</td></tr>
                                <tr><td>from_city</td><td>من المدينة</td><td>لخدمات النقل</td></tr>
                                <tr><td>from_neighborhood</td><td>من الحي</td><td>لخدمات النقل</td></tr>
                                <tr><td>to_city</td><td>إلى المدينة</td><td>لخدمات النقل</td></tr>
                                <tr><td>to_neighborhood</td><td>إلى الحي</td><td>لخدمات النقل</td></tr>
                                <tr><td>model</td><td>الموديل</td><td>للسيارات والمعدات</td></tr>
                                <tr><td>year</td><td>السنة</td><td>للسيارات والمعدات</td></tr>
                                <tr><td>brand</td><td>الماركة</td><td>للسيارات والمعدات</td></tr>
                                <tr><td>part_number</td><td>رقم القطعة</td><td>لقطع الغيار</td></tr>
                                <tr><td>equip_type</td><td>نوع المعدة</td><td>للمعدات</td></tr>
                                <tr><td>car_type</td><td>نوع السيارة</td><td>للسيارات</td></tr>
                                <tr><td>location</td><td>الموقع</td><td>للخدمات العامة</td></tr>
                                <tr><td>gender</td><td>الجنس</td><td>للخدمات الشخصية</td></tr>
                                <tr><td>time</td><td>الوقت</td><td>للمواعيد</td></tr>
                                <tr><td>date</td><td>التاريخ</td><td>للمواعيد</td></tr>
                                <tr><td>day</td><td>اليوم</td><td>للمواعيد</td></tr>
                                <tr><td>quantity</td><td>الكمية</td><td>للمنتجات والخدمات</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
