
@extends('layouts.dashboard.dashboard')
<?php $lang = config('app.locale'); ?>
@section('title')
{{ $lang == 'ar' ? 'الطلبات' : 'Orders' }}
@endsection

@section('page_name')
{{ $lang == 'ar' ? 'الطلبات' : 'Orders' }}
@endsection

@section('content')


<form action="{{ route('admin.orders.bulk_action') }}" method="post">
    @csrf
    <div class="col-md-12">
        <div class="input-group mb-3 d-flex justify-content-end">
            {{-- تصفية حسب الحالة --}}
            <div class="remv_control mr-2">
                <select name="status" class="mr-3 mt-3 form-control remv_focus">
                    <option value="">{{ $lang == 'ar' ? 'الحالة' : 'Status' }}</option>
                    <option value="pending" {{ selected('pending', request('status')) }}>{{ $lang == 'ar' ? 'معلق' : 'Pending' }}</option>
                    <option value="completed" {{ selected('completed', request('status')) }}>{{ $lang == 'ar' ? 'مكتمل' : 'Completed' }}</option>
                    <option value="cancelled" {{ selected('cancelled', request('status')) }}>{{ $lang == 'ar' ? 'ملغي' : 'Cancelled' }}</option>
                </select>
            </div>

            {{-- زر التحديث الجماعي --}}
            <button type="submit" name="bulk_action_btn" value="update_status" class="btn btn-primary mt-3 mr-2">
                <i class="la la-refresh"></i> {{ __('general.update') }}
            </button>

            {{-- زر الحذف الجماعي --}}
            <button type="submit" name="bulk_action_btn" value="delete" class="btn btn-danger delete_confirm mt-3 mr-2">
                <i class="la la-trash"></i> {{ __('general.delete') }}
            </button>
        </div>
    </div>

    <div class="col-sm-12">
        @if ($orders->count())
            <div class="cls_table table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th><input class="bulk_check_all" type="checkbox" /></th>

                            <th>{{ $lang == 'ar' ? 'اسم العميل' : 'Customer' }}</th>
                            <th>{{ $lang == 'ar' ? 'رقم الجوال' : 'Phone' }}</th>
                            <th>{{ $lang == 'ar' ? 'الحالة' : 'Status' }}</th>
                            <th>{{ $lang == 'ar' ? 'العدد' : 'َQuantity' }}</th>
                            <th>{{ $lang == 'ar' ? 'العدد' : 'َQuantity' }}</th>
                            <th>{{ $lang == 'ar' ? 'الوقت' : 'Created At' }}</th>
                            <th>{{ $lang == 'ar' ? 'الاجراءات' : 'Actions' }}</th>
                        </tr>
                    </thead>
                    @foreach ($orders as $order)
                    <tbody>
                            <tr>
                                <td>
                                    <label>
                                        <input class="check_bulk_item" name="bulk_ids[]" type="checkbox" value="{{ $order->id }}" />
                                        <span class="text-muted">#{{ $order->id }}</span>
                                    </label>
                                </td>

                                <td>{{ $order->user->first_name . ' ' . $order->user->last_name }}</td>
                                <td>{{ $order->user->phone }}</td>
                                @if($order->status == 'pending')
                                <td>{{ $lang == 'ar' ? 'معلق' : 'Pending' }}</td>
                                @elseif ($order->status == 'completed')
                                <td>{{ $lang == 'ar' ? 'مكتمل' : 'Completed' }}</td>
                                @else
                                <td>{{ $lang == 'ar' ? 'ملغي' : 'Cancelled' }}</td>
                                @endif
                                <td>{{ $order->items->count() }}</td>
                                <td>{{ $order->total }} {{ $lang == 'ar' ? 'ريال' : 'SAR' }}</td>
                                <td>{{ $order->created_at->shortAbsoluteDiffForHumans() }}</td>
                                <td>
                                    <a href="{{ route('admin.pro_orders.show', $order->id) }}" class="btn btn-purple" title="('general.show')">
                                        <i class="la la-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                        @endforeach
                </table>
            </div>
        @else
            {!! no_data() !!}
        @endif

        {!! $orders->links() !!}
    </div>
</form>

@if (Session::has('success'))
    <script>
        swal("{{ __('general.message') }}", "{{ Session::get('success') }}", 'success', {
            button: true,
            timer: 3000,
        });
    </script>
@endif

@if (Session::has('info'))
    <script>
        swal("{{ __('general.message') }}", "{{ Session::get('info') }}", 'info', {
            button: true,
            timer: 3000,
        });
    </script>
@endif
@endsection
