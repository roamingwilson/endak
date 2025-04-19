
@extends('layouts.dashboard.dashboard')
@section('title')
<?php $lang = config('app.locale'); ?>

{{ ($lang == 'ar')? 'صناعة البلاستيك' : "Industry" }}
@endsection
@section('page_name')
{{ ($lang == 'ar')? 'صناعة البلاستيك' : "Industry" }}
@endsection
@section('content')
<div class="container mt-5">
    <h2 class="mb-4">إضافة قسم جديد</h2>

    {{-- رسائل النجاح أو الخطأ --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- نموذج إضافة قسم --}}
    <form action="{{ route('indscategories.store') }}" method="POST">
        @csrf
        @if(isset($industry))
    <input type="hidden" name="industry_id" value="{{ $industry->id }}">
@endif

<div class="mb-3">
    <label for="name" class="form-label">اسم القسم</label>
    <input type="text" name="name" id="name"
    class="form-control @error('name') is-invalid @enderror"
    value="{{ old('name') }}" placeholder="مثال: خامات بلاستيك">


</div>



        <button type="submit" class="btn btn-primary">حفظ القسم</button>
        <a href="{{ route('indscategories.index') }}" class="btn btn-secondary">رجوع</a>
    </form>
</div>
<div class="container mt-5">


    {{-- رسائل الفيدباك --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($categories->count() > 0)
        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>الاسم</th>

                    <th>تاريخ الإنشاء</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categories as $category)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $category->name }}</td>

                        <td>{{ $category->created_at->format('Y-m-d') }}</td>
                        <td>
                            <a href="{{ route('indscategories.edit', $category->id) }}" class="btn btn-sm btn-primary">تعديل</a>

                            <form action="{{ route('indscategories.destroy', $category->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('هل أنت متأكد من حذف هذا القسم؟');">
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
            لا توجد أقسام مضافة حتى الآن.
        </div>
    @endif
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
