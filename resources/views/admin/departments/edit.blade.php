@extends('layouts.dashboard.dashboard')

@section('title')
    <?php $lang = config('app.locale'); ?>

    {{ ($lang == 'ar')? $department->name_ar :$department->name_en }}

    @endsection

@section('page_name')
{{ ($lang == 'ar')? $department->name_ar :$department->name_en }}

@endsection

@section('content')
    <div class="container">
        @if ($errors->any())
            <div class="alert alert-danger">
                <h3>Error Occured!</h3>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{route('admin.departments.update',$department->id)}}" method="post"
            enctype="multipart/form-data">
            @csrf
            @method('put')
            <div class="form-group">
                <lable class="" for="">{{ __('department.name_ar') }}</lable>
                <input type="text" name="name_ar" class="form-control mt-2" value="{{ old('name_ar', $department->name_ar) }}" />
            </div>
            <div class="form-group">



            <div class="form-group">
                <lable class="" for="">{{ __('settings.logo') }}</lable>
                <input type="file" name="image" class="form-control mt-2 dropify"
                    value="{{ old('logo', $department->image_url) }}" data-default-file="{{ asset($department->image) }}" />
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-success">{{ __('general.save') }}</button>
            </div>
        </form>
    </div>
@endsection
