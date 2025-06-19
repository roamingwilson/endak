@extends('layouts.dashboard.dashboard')
@section('title')
    {{ __('posts.posts') }}
@endsection

@section('page_name')
    {{ __('posts.posts') }}
@endsection
@section('content')
    <?php $lang = config('app.locale'); ?>



    <div class="col-sm-12">
        @if ($service->count())
            <div class="cls_table table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th><input class="bulk_check_all" type="checkbox" /></th>
                            <th>@lang('posts.title')</th>
                            <th>@lang('posts.description')</th>
                            <th>{{ __('posts.published_time') }}</th>

                        </tr>
                    </thead>

                    @foreach ($service as $post)
                        <tr>
                            <td>
                                <label>
                                    <input class="check_bulk_item" name="bulk_ids[]" type="checkbox"
                                        value="{{ $post->id }}" />
                                    <span class="text-muted">#{{ $post->id }}</span>
                                </label>
                            </td>
                            <td>{{ $lang == 'ar' ? $post->departments->name_ar : $post->departments->name_en }}</td>
                            <td>{{ $post->user->fullname }} </td>
                            <td>{{ $post->created_at->shortAbsoluteDiffForHumans() }}</td>

                        </tr>
                    @endforeach
                </table>
            </div>
        @else
            {!! no_data() !!}
        @endif

        {!! $service->links() !!}

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
