@forelse ($orders as $order)
    <form action="" method="POST" enctype="multipart/form-data" class="profile-card rounded-lg shadow-xs bg-white p-15 p-md-30 w-100 mb-3">
        <div class="profile-content pt-40">
            <div class="container">
                <div class="row">
                    <div class="col-12 col-xl-4 col-lg-9 col-md-8 col-sm-8 mb-2">
                        <span class="badge {{ $order->status == 'completed' ? 'bg-primary-transparent tx-primary' : 'bg-danger-transparent tx-danger' }} me-1 mb-1 mt-3 mt-sm-0">
                            {{ $order->status == 'completed' ? __('order.complete') : __('order.pending') }}
                        </span>
                        <h6 class="mb-0"><a href="#">
                            @if ($user->role_id == 1)
                                {{ $order->service_provider_name ?? $order->service_provider_furniture_transportation->first_name }}
                            @elseif ($user->role_id == 3)
                                {{ $order->customer_name ?? $order->customer_furniture_transportation->first_name }}
                            @endif
                        </a></h6>
                    </div>
                    <div class="col-12 col-xl-8 col-lg-9 col-md-8 col-sm-8 mb-2">
                        <p>{{ $order->description }}</p>
                        <hr>
                        <a href="{{ route($routeName, $order->id) }}" class="btn btn-primary">{{ __('general.show') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </form>
@empty
    {!! no_data() !!}
@endforelse
