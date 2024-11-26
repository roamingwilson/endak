@extends('layouts.dashboard.dashboard')
@section('title')
<?php $lang = config('app.locale'); ?>

{{ __('products.products') }}
@endsection

@section('page_name')
{{ __('products.create_product') }}
@endsection
@section('content')
        <div class="col-sm-12">

            <form action="{{ route('main_furniture_transportations.product.store') }}" method="post" id="createPostForm" enctype="multipart/form-data">
                @csrf

                <div class="form-group row {{ $errors->has('name_ar') ? 'has-error' : '' }} ">
                    <label class="col-sm-3 control-label" for="department_name">@lang('department.name_ar') <em>*</em></label>
                    <div class="col-sm-7">
                        <input type="text" name="name_ar" value="" placeholder="@lang('department.name_ar')"
                            class="form-control">
                        {!! $errors->has('name_ar') ? '<p class="help-block">' . $errors->first('name_ar') . '</p>' : '' !!}
                    </div>
                </div>
                <div class="form-group row {{ $errors->has('name_en') ? 'has-error' : '' }} ">
                    <label class="col-sm-3 control-label" for="department_name_en">@lang('department.name_en') <em>*</em></label>
                    <div class="col-sm-7">
                        <input type="text" name="name_en" value="" placeholder="@lang('department.name_en')"
                             class="form-control">
                        {!! $errors->has('name_en') ? '<p class="help-block">' . $errors->first('name_en') . '</p>' : '' !!}
                    </div>
                </div>
               
            
                <div class="form-group row {{ $errors->has('image') ? 'has-error' : '' }} ">
                    <label class="col-sm-3 control-label" for="image">@lang('department.image')
                    </label>
                    <div class="col-sm-7">
                        <input type="file" name="image" placeholder="@lang('department.image')" id="image"
                            class="form-control dropify">
                        {!! $errors->has('image') ? '<p class="help-block">' . $errors->first('image') . '</p>' : '' !!}
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-9">
                        <button type="submit" class="btn btn-primary">{{__('products.create_product')}}</button>
                    </div>
                </div>
            </form>

        </div>



@endsection

