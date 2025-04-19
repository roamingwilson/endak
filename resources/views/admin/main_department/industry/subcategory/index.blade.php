
@extends('layouts.dashboard.dashboard')
@section('title')
<?php $lang = config('app.locale'); ?>

{{ ($lang == 'ar')? 'صناعة البلاستيك' : "Industry" }}
@endsection
@section('page_name')
{{ ($lang == 'ar')? 'صناعة البلاستيك' : "Industry" }}
@endsection
@section('content')
    <div>
        <div class="container mt-5">
            <h2 class="mb-4">إضافة قسم فرعي جديد</h2>

            {{-- عرض الأخطاء --}}
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- نموذج الإضافة --}}
            <form action="{{ route('indsubcategories.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label">اسم القسم الفرعي</label>
                    <input type="text" name="name" id="name"
                           class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name') }}" placeholder="مثال: عبوات بلاستيك">

                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>



                <div class="mb-3">
                    <label for="category_id" class="form-label">القسم الرئيسي</label>
                    <select name="inds_category_id" id="category_id"
                            class="form-select @error('category_id') is-invalid @enderror">
                        <option value="">-- اختر قسم رئيسي --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>

                    @error('category_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">إضافة القسم الفرعي</button>

            </form>
        </div>
        <div class="container mt-5">


            {{-- رسائل الفيدباك --}}
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if($subcategories->count() > 0)
                <table class="table table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>الاسم</th>

                            <th>القسم الرئيسي</th>
                            <th>تاريخ الإنشاء</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($subcategories as $subcategory)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $subcategory->name }}</td>

                                <td>{{ $subcategory->category->name ?? 'غير محدد' }}</td>
                                <td>{{ $subcategory->created_at->format('Y-m-d') }}</td>
                                <td>
                                    <a href="{{ route('indsubcategories.edit', $subcategory->id) }}" class="btn btn-sm btn-primary">تعديل</a>

                                    <form action="{{ route('indsubcategories.destroy', $subcategory->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('هل أنت متأكد من حذف هذا القسم الفرعي؟');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">حذف</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="alert alert-warning text-center">
                    لا توجد أقسام فرعية مضافة حتى الآن.
                </div>
            @endif
        </div>
    </div>

@if (Session::has('success'))
<script>
    swal("Message", "{{ Session::get('success') }}", 'success', {
        button: true,
        button: "Ok",
        timer: 3000,
    })
</script>
@endif
@if (Session::has('info'))
<script>
    swal("Message", "{{ Session::get('info') }}", 'info', {
        button: true,
        button: "Ok",
        timer: 3000,
    })
</script>
@endif
@endsection
