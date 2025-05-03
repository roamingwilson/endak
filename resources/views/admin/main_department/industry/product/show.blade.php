@extends('layouts.dashboard.dashboard')
{{-- @section('title') المنتجات @endsection --}}
<?php $lang = config('app.locale'); ?>
@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between mb-3">
        <h4>{{ $lang == 'ar' ? 'قائمة المنتجات' : 'Product List' }}</h4>
        <a href="{{ route('indproducts.create') }}" class="btn btn-primary">{{ $lang == 'ar' ? 'إضافة منتج' : 'Add Product' }}</a>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered text-center align-middle">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>{{ $lang == 'ar' ? 'العنوان' : 'Title' }}</th>
                    <th>{{ $lang == 'ar' ? 'الصورة' : 'Image' }}</th>
                    <th>{{ $lang == 'ar' ? 'الوصف' : 'Description' }}</th>
                    <th>{{ $lang == 'ar' ? 'السعر' : 'Price' }}</th>
                    <th>{{ $lang == 'ar' ? 'تاريخ الإضافة' : 'Date Added' }}</th>
                    <th>{{ $lang == 'ar' ? 'العمليات' : 'Actions' }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($category as $index => $product)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $product->title }}</td>
                        <td>
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $lang == 'ar' ? 'الصورة' : 'Image' }}" width="80">
                        </td>
                        <td>{{ $product->description }}</td>
                        <td>{{ number_format($product->price, 2) }} {{ $lang == 'ar' ? 'ريال' : 'SAR' }}</td>
                        <td>{{ $product->created_at->format('Y-m-d') }}</td>
                        <td>
                            <a href="{{ route('indproducts.edit', $product->id) }}" class="btn btn-sm btn-info">{{ $lang == 'ar' ? 'تعديل' : 'Edit' }}</a>
                            <form action="{{ route('indproducts.destroy', $product->id) }}" method="POST" style="display:inline-block;" class="delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger delete-btn">{{ $lang == 'ar' ? 'حذف' : 'Delete' }}</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $category->links() }}
    </div>
</div>
@endsection

@section('js')
<script>
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!confirm('{{ $lang == 'ar' ? 'هل أنت متأكد أنك تريد حذف هذا المنتج؟' : 'Are you sure you want to delete this product?' }}')) {
                e.preventDefault();
            }
        });
    });
</script>
@endsection
