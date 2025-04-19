
@extends('layouts.dashboard.dashboard')
@section('title')
<?php $lang = config('app.locale'); ?>

{{ ($lang == 'ar')? 'معدات ثقيلة' : "Heavy equipment"}}

@endsection
@section('page_name')
{{ ($lang == 'ar')?'معدات ثقيلة' : "Heavy equipment"}}

@endsection
@section('content')
    <div>
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">    {{ ($lang == 'ar')?'معدات ثقيلة' : "Heavy equipment" }}

                    </h3>
                </div>
                <div class="card-body">
                    <table id="example2" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <td class="width30">{{ __("department.name_ar") }}</td>
                                <td>{{ $heavy_equip->name_ar ?? '#' }}</td>
                            </tr>
                            <tr>
                                <td class="width30">{{ __("department.name_en") }}</td>
                                <td>{{ $heavy_equip->name_en ?? '#' }}</td>
                            </tr>

                            <tr>
                                <td class="width30">{{ __("settings.logo") }}</td>
                                <td>
                                    <div class="image" >
                                        <img width="100" height="100" src="{{ $heavy_equip->image_url ?? "" }}" alt="Not" class="custom_img">

                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td class="width30">{{ __("general.edit") }}</td>
                                <td>
                                    <a href="{{ route('admin.heavy_equip.edit' , $heavy_equip->id) }}">
                                        <button class="btn btn-info">
                                            {{ __("general.edit") }}
                                        </button>
                                    </a>
                                </td>
                            </tr>
                            @if ($heavy_equip->heavy_equip_id == 0)


                            <tr>
                                <td class="width30">{{ ($lang == 'ar')?  'اضافة قسم فرعي' : "Add Sub Department" }} </td>
                                <td>
                                    <a href="{{ route('admin.heavy_equip.add_sub_department' , $heavy_equip->id) }}">
                                        <button class="btn btn-info">
                                            {{ __("general.add") }}
                                        </button>
                                    </a>
                                </td>
                            </tr>
                            @endif
                            @if ($heavy_equip->heavy_equip_id == 0)


                            <tr>
                                <td class="width30">{{ ($lang == 'ar')?  'عرض الاقسام الفرعية' : "Show Sub Department" }} </td>
                                <td>
                                    <a href="{{ route('admin.heavy_equip.show_sub_departments_list' , $heavy_equip->id) }}">
                                        <button class="btn btn-info">
                                            {{ __("general.show") }}
                                        </button>
                                    </a>
                                </td>
                            </tr>
                            @endif
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
