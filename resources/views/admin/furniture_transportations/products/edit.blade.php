@extends('layouts.dashboard.dashboard')
@section('title')
{{ __('products.edit') }}
@endsection

@section('page_name')
{{ __('products.edit') }}
@endsection



@section('content')

        <div class="col-sm-12">
            <form action="{{ route('main_furniture_transportations.product.update' , $product->id) }}" method="post" id="createproductForm" enctype="multipart/form-data">
                @csrf
                @method('patch')
                <div class="form-group row {{ $errors->has('name_ar') ? 'has-error' : '' }} ">
                    <label class="col-sm-3 control-label" for="department_name">@lang('department.name_ar') <em>*</em></label>
                    <div class="col-sm-7">
                        <input type="text" name="name_ar"  placeholder="@lang('department.name_ar')" value="{{ $product->name_ar }}"
                            class="form-control">
                        {!! $errors->has('name_ar') ? '<p class="help-block">' . $errors->first('name_ar') . '</p>' : '' !!}
                    </div>
                </div>
                <div class="form-group row {{ $errors->has('name_en') ? 'has-error' : '' }} ">
                    <label class="col-sm-3 control-label" for="department_name_en">@lang('department.name_en') <em>*</em></label>
                    <div class="col-sm-7">
                        <input type="text" name="name_en"  placeholder="@lang('department.name_en')" value="{{ old('name_en')?old('name_en'): $product->name_en }}"
                             class="form-control">
                        {!! $errors->has('name_en') ? '<p class="help-block">' . $errors->first('name_en') . '</p>' : '' !!}
                    </div>
                </div>
               
                <div class="form-group row {{ $errors->has('image') ? 'has-error' : '' }} ">
                    <label class="col-sm-3 control-label " for="image">@lang('department.image')
                    </label>
                    <div class="col-sm-7">
                        <input type="file" name="image"  placeholder="@lang('department.image')" id="image"
                            class="form-control dropify" data-default-file="{{ asset($product->image_url) }}">
                        {!! $errors->has('image') ? '<p class="help-block">' . $errors->first('image') . '</p>' : '' !!}
                    </div>
                </div>


                <div class="form-group row">
                    <div class="col-sm-9">
                        <button type="submit" class="btn btn-primary">{{__('products.update')}}</button>
                    </div>
                </div>
            </form>

        {{-- </div> --}}

    </div>

@endsection

@section('js')
    <script>
        $(".topics").select2({
            topics: true,
            tokenSeparators: [',', ' ']
        })
    </script>
    <script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
    <script>
        // Replace the <textarea id="editor1"> with a CKEditor
        // instance, using default configuration.
        CKEDITOR.replace( 'product_content' );
    </script>
    <script>
        // Replace the <textarea id="editor1"> with a CKEditor
        // instance, using default configuration.
        CKEDITOR.replace( 'product_content_en' );
    </script>
@endsection
