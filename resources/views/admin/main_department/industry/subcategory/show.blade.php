@extends('layouts.dashboard.dashboard')
{{-- @section('title') {{ __('Subcategories') }} --}}
<?php $lang = config('app.locale'); ?>
@section('content')
    <div class="col-md-12 content-col">
        <h4 class="mb-3">{{ ($lang == 'ar')? ' الاقسام الفرعية' : " SubCategory" }}</h4>
        @if($category->count())
            <div class="table-responsive">
                <div class="d-flex justify-content-between mb-3">

                    <a href="{{ route('indsubcategories.index') }}" class="btn btn-primary">   {{ ($lang == 'ar')? ' إضافة قسم' : "Add SubCategory" }} </a>
                </div>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ ($lang == 'ar')? '  القسم' : " SubCategory" }}</th>
                            <th>{{ ($lang == 'ar')? 'القسم الرئيسي' :'Parent Category'}}</th>
                            <th>{{ $lang == 'ar' ? 'الاجراءات' : 'Actions' }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($category as $sub)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $sub->name }}</td>
                                <td>{{ optional($sub->category)->name }}</td>
                                <td>
                                    <a href="{{ route('indsubcategories.edit', $sub->id) }}" class="btn btn-sm btn-info">{{ $lang == 'ar' ? 'تعديل' : 'Edit' }}</a>
                                    <form action="{{ route('indsubcategories.destroy', $sub->id) }}" method="POST" style="display:inline-block;" class="delete-form">
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
        @else
            <p class="alert alert-warning">{{ __('No subcategories found') }}</p>
        @endif
    </div>
@endsection
