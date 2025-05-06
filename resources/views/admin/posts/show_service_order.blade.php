@extends('layouts.dashboard.dashboard')
@php

$lang = config('app.locale');
@endphp
@section('title')
{{ $lang == 'ar' ? 'طلبات الخدمات' : 'service order' }}
@endsection

@section('page_name')
{{ $lang == 'ar' ? 'طلبات الخدمات' : 'service order' }}
@endsection
@section('content')
<?php $lang = config('app.locale'); ?>

        <div class="col-sm-12">
            @if ($orders->count())
                <div class="cls_table table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th><input class="bulk_check_all" type="checkbox" /></th>
                                <th>{{ $lang == 'ar' ? 'القسم' : 'deparment' }}</th>
                                <th>{{ $lang == 'ar' ? 'المزود' : 'Server provider' }}</th>
                                <th>{{ $lang == 'ar' ? 'العميل' : 'Client' }}</th>
                                <th>{{ $lang == 'ar' ? 'الوقت' : 'time' }}</th>


                            </tr>
                        </thead>

                        @foreach ($orders as $post)
                            <tr>
                                <td>
                                    <label>
                                        <input class="check_bulk_item" name="bulk_ids[]" type="checkbox"
                                            value="{{ $post->id }}" />
                                        <span class="text-muted">#{{ $post->id }}</span>
                                    </label>
                                </td>
                                <td>{{$post->service->type}} </td>
                                <td>{{ $post->user->fullname }} </td>
                                <td>{{ $post->customer->first_name }} </td>
                                <td>{{ $post->created_at->shortAbsoluteDiffForHumans() }}</td>

                            </tr>
                        @endforeach
                    </table>
                </div>
            @else
                {!! no_data() !!}
            @endif

            {!! $orders->links() !!}

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

