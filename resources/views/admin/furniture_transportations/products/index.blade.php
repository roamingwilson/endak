@extends('layouts.dashboard.dashboard')
@section('title')
<?php $lang = config('app.locale'); ?>

{{ ($lang == 'ar')? 'منتجات قسم نقل عفش' : 'Product Furniture Transportations' }}
@endsection

@section('page_name')
{{ ($lang == 'ar')? 'منتجات قسم نقل عفش' : 'Product Furniture Transportations' }}
@endsection
@section('content')
    <form action="" method="get">

        <div class="col-md-12">
            <div class="input-group mb-3 d-flex justify-content-between align-items-center">
                <div class="remv_control d-flex justify-content-between align-items-center">
                    <select name="status" class="form-control remv_focus">
                        <option value="">@if($lang == 'ar') {{ "حالة المنتج" }} @else {{ "Status" }} @endif</option>
                        <option value="1" {{selected('1', request('status'))}}>@if($lang == 'ar') {{ "عرض" }} @elseif($lang == 'en') {{ "Active" }}@endif</option>
                        <option value="4" {{selected('2', request('status'))}}>@if($lang == 'ar') {{ "اخفاء" }} @elseif($lang == 'en') {{ "Dis Active" }}@endif</option>
                    </select>
                    <button type="submit" name="bulk_action_btn" value="update_status" class="btn btn-primary mr-2">
                        <i class="la la-refresh"></i> {{__('general.update')}}
                    </button>
                </div>
                <div class="d-flex">
                    
                    <button type="submit" name="bulk_action_btn" value="delete" class="btn btn-danger delete_confirm mr-2">
                        <i class="la la-trash"></i> {{ __('general.delete') }}
                    </button>
                    <a href="{{ route('main_furniture_transportations.product.create') }}" class="btn btn-primary" data-toggle="tooltip" title="@lang('general.add')">
                        <i class="la la-plus"></i>
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-sm-12">
            @if ($products->count())
                <div class="cls_table table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th><input class="bulk_check_all" type="checkbox" /></th>
                                <th>@lang('department.name_ar')</th>
                                <th>@lang('department.name_en')</th> 
                                <th>@lang('general.status')</th> 
                                <th>{{ __('department.image') }}</th>
                                <th>@lang('general.actions')</th>
                            </tr>
                        </thead>

                        @foreach ($products as $product)
                            <tr>
                                <td>
                                    <label>
                                        <input class="check_bulk_item" name="bulk_ids[]" type="checkbox"
                                            value="{{ $product->id }}" />
                                        <span class="text-muted">#{{ $product->id }}</span>
                                    </label>
                                </td>
                                <td>{{ $product->name_ar }} </td>
                                <td>{{ $product->name_en }} </td> 
                                <td>@if($product->status == 'active' && $lang == 'ar') {{ "معروض" }} @elseif($product->status == 'active' && $lang == 'en') {{ "Active" }} @elseif($product->status == 'disactive' && $lang == 'ar') {{ "غير معروض" }} @else {{ "Dis Active" }} @endif</td> 
                                <td> <img src="{{ $product->image_url }}" alt="" width="200px" height="150px" />
                                </td>
                                <td>
                                    <a href="{{ route('main_furniture_transportations.product.edit', $product->id) }}"
                                        class="btn btn-primary btn-sm"><i class="la la-pencil"></i> </a>
                                    <a href="{{ route('main_furniture_transportations.product.delete', $product->id) }}"
                                        class="btn btn-danger btn-sm" title="@lang('general.delete')"><i
                                            class="la la-trash-o"></i> </a>

                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            @else
                {!! no_data() !!}
            @endif

            {!! $products->links() !!}

        </div>
    </form>

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
