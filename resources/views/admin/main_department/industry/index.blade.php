
@extends('layouts.dashboard.dashboard')
@section('title')
<?php $lang = config('app.locale'); ?>

{{ ($lang == 'ar')? $department->name_ar : $department->name_en }}

@endsection
@section('page_name')
{{ ($lang == 'ar')? $department->name_ar : $department->name_en }}

@endsection
@section('content')
    <div>
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">    {{ ($lang == 'ar')? $department->name_ar : $department->name_en }}

                    </h3>
                </div>
                <div class="card-body">
                    <table id="example2" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <td class="width30">{{ __("department.name_ar") }}</td>
                                <td>{{ $department->name_ar ?? '#' }}</td>
                            </tr>
                            <tr>
                                <td class="width30">{{ __("department.name_en") }}</td>
                                <td>{{ $department->name_en ?? '#' }}</td>
                            </tr>

                            <tr>
                                <td class="width30">{{ __("settings.logo") }}</td>
                                <td>
                                    <div class="image" >
                                        <img width="100" height="100" src="{{ $department->image_url ?? "" }}" alt="Not" class="custom_img">

                                    </div>
                                </td>
                            </tr>





                            <tr>
                                <td class="width30">{{ ($lang == 'ar')?  'اضافة قسم رئيسي' : "Add category " }} </td>
                                <td>
                                    <a href="{{ route('indscategories.index') }}">
                                        <button class="btn btn-info">
                                            {{ __("general.add") }}
                                        </button>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td class="width30">{{ ($lang == 'ar')?  'اضافة قسم فرعي' : "Add Sub category" }} </td>
                                <td>
                                    <a href="{{ route('indsubcategories.index') }}">
                                        <button class="btn btn-info">
                                            {{ __("general.add") }}
                                        </button>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td class="width30">{{ ($lang == 'ar')?  'اضافة  منتجات' : "Add  product" }} </td>
                                <td>
                                    <a href="{{ route('indproducts.create') }}">
                                        <button class="btn btn-info">
                                            {{ __("general.add") }}
                                        </button>
                                    </a>
                                </td>
                            </tr>




                            <tr>
                                <td class="width30">{{ ($lang == 'ar')?  'عرض الاقسام الرئيسة' : "Show Category" }} </td>
                                <td>
                                    <a href="{{ route('indsustry.cat') }}">
                                        <button class="btn btn-info">
                                            {{ __("general.show") }}
                                        </button>
                                    </a>
                                </td>
                            </tr>

                            <tr>
                                <td class="width30">{{ ($lang == 'ar')?  'عرض الاقسام الفرعية' : "Show Sub Category" }} </td>
                                <td>
                                    <a href="{{ route('indsustry.subcat') }}">
                                        <button class="btn btn-info">
                                            {{ __("general.show") }}
                                        </button>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td class="width30">{{ ($lang == 'ar')?  'عرض المنتجات ' : "Show Product" }} </td>
                                <td>
                                    <a href="{{ route('indsustry.pro') }}">
                                        <button class="btn btn-info">
                                            {{ __("general.show") }}
                                        </button>
                                    </a>
                                </td>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
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
