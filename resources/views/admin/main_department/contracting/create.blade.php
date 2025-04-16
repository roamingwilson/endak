@extends('layouts.dashboard.dashboard')

@section('title')
    <?php $lang = config('app.locale'); ?>

    {{ ($lang == 'ar')? 'المقاولات' : "Contracting" }}
    @endsection

@section('page_name')
{{ ($lang == 'ar')? 'المقاولات' : "Contracting" }}

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
        <form action="{{ route('admin.contracting.store_sub_department',) }}" method="post"
            enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <lable class="" for="">{{ __('department.name_ar') }}</lable>
                <input type="text" name="name_ar" class="form-control mt-2"   />
            </div>
            <div class="form-group">
                <lable class="" for="">{{ __('department.name_en') }}</lable>
                <input type="text" name="name_en" class="form-control mt-2"
                      />
            </div>


            <div class="form-group">
                <lable class="" for="">{{ __('settings.logo') }}</lable>
                <input type="file" name="image" class="form-control mt-2 dropify" />
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-success">{{ __('general.save') }}</button>
            </div>
        </form>
    </div>
@endsection
