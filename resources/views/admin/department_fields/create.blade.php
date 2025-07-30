@extends('layouts.dashboard.dashboard')

@section('content')
    <div class="container">
        <h1>Add Field to {{ $department->name_en }}</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.departments.fields.store', $department->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <a href="{{ route('admin.sub_departments.create') }}" class="btn btn-primary">إضافة قسم فرعي جديد</a>
            </div>
            <div class="mb-3">
                <label for="sub_department_id">القسم الفرعي (اختياري)</label>
                    <select name="sub_department_id" id="sub_department_id" class="form-control">
        <option value="">اختر القسم الفرعي</option>
        @foreach($subDepartments as $sub)
            <option value="{{ $sub->id }}" {{ $selectedSubDepartmentId == $sub->id ? 'selected' : '' }}>{{ $sub->name_ar }}</option>
        @endforeach
    </select>
            </div>
            @include('admin.department_fields._dynamic_field_form', ['field' => null])
        </form>
    </div>
    // fileds name stratege
    <table class="table table-bordered text-center">
        <thead>
            <tr>
                <thead>
                    <tr>
                        <th>Field Name (English)</th>
                        <th>اسم الحقل (بالعربية)</th>
                    </tr>
                </thead>
                <tbody>

                    <tr><td>neighborhood</td><td>الحي</td></tr>
                    <tr><td>from_city</td><td>من المدينة</td></tr>
                    <tr><td>from_neighborhood</td><td>من الحي</td></tr>
                    <tr><td>to_city</td><td>إلى المدينة</td></tr>
                    <tr><td>to_neighborhood</td><td>إلى الحي</td></tr>
                    <tr><td>model</td><td>الموديل</td></tr>
                    <tr><td>year</td><td>السنة</td></tr>
                    <tr><td>brand</td><td>الماركة</td></tr>
                    <tr><td>part_number</td><td>رقم القطعة</td></tr>
                    <tr><td>equip_type</td><td>نوع المعدة</td></tr>
                    <tr><td>car_type</td><td>نوع السيارة</td></tr>
                    <tr><td>location</td><td>الموقع</td></tr>
                    <tr><td>gender</td><td>الجنس</td></tr>
                    <tr><td>time</td><td>الوقت</td></tr>
                    <tr><td>date</td><td>التاريخ</td></tr>
                    <tr><td>day</td><td>اليوم</td></tr>
                    <tr><td>quantity</td><td>الكمية</td></tr>
                </tbody>
    </table>


@endsection
