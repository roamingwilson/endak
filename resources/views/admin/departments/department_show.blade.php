

@extends('layouts.dashboard.dashboard')


@section('content')
    <div class="content container-fluid">
        <!-- Page Title -->
        <div class="mb-3">
            <h2 class="h1 mb-0  d-flex align-items-center gap-2">
                {{__('department.details')}}
            </h2>
        </div>
        <!-- End Page Title -->
        <div class="card mt-3">
            <div class="card-body">
                <table id="example2" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <td class="width30">{{ __("department.name_ar") }}</td>
                            <td>{{ $department->name_ar ?? '#' }}</td>
                        </tr>
                        <tr>
                            <td class="width30">{{ __('department.name_en') }}</td>
                            <td>{{ $department->name_en ?? '#' }}</td>
                        </tr>
                        <tr>
                            <td class="width30">{{ __('department.description_ar') }}</td>
                            <td>{{ $department->description_ar ?? '#' }}</td>
                        </tr>
                        <tr>
                            <td class="width30">{{ __("department.description_en") }}</td>
                            <td>{{ $department->description_en ?? '#' }}</td>
                        </tr>
                        <tr>
                            <td class="width30">{{ __("department.status") }}</td>
                            <td>{{ ($department->status == 1) ? 'active' : "inactive" }}</td>
                        </tr>
                        <tr>
                            <td class="width30">{{ __("department.parent") }}</td>
                            <td>{{ ($department->parent_Department) ? $department->parent_Department : __('department.main') }}</td>
                        </tr>
                        @if (isset($department->products))
                        <tr>
                            <td class="width30">{{ __("products.products") }}</td>
                            <td>
                                @foreach ($department->products as $product)

                                <li>{{  $product->name_ar }}</li>
                                @endforeach
                            </td>
                        </tr>
                        @endif

                        <tr>
                            <td class="width30">{{ __("department.image") }}</td>
                            <td>
                                <div class="image" >
                                    <img width="100" height="100" src="{{ $department->image_url ?? "" }}" alt="Not" class="custom_img">

                                </div>
                            </td>
                        </tr>



                    </thead>
                </table>
            </div>
        </div>
        <!-- عرض الأقسام الفرعية -->
        @if($department->sub_departments && $department->sub_departments->count())
        <div class="card mt-4">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">الأقسام الفرعية</h4>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>اسم القسم الفرعي</th>
                            <th>العمليات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($department->sub_departments as $sub)
                        <tr>
                            <td>{{ $sub->name_ar }} <span class="text-muted">({{ $sub->name_en }})</span></td>
                            <td>
                                <a href="{{ route('admin.department.show', $sub->id) }}" class="btn btn-info btn-sm">عرض</a>
                                <a href="{{ route('admin.orders.create', ['department_id' => $sub->id]) }}" class="btn btn-success btn-sm">إضافة خدمة</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
        <!-- زر طلب خدمة -->
        {{--  <div class="mt-4 text-center">
            <a href="{{ route('admin.orders.create', ['department_id' => $department->id]) }}" class="btn btn-success btn-lg">
                طلب خدمة من هذا القسم
            </a>
        </div>  --}}
    </div>
@endsection

