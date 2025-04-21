@extends('layouts.home')
@section('title')
<?php
$lang = config('app.locale');
?>
{{ ($lang == 'ar')? ' اشعارات' : "notification" }}
@endsection

@section('content')

    <div class="main-content app-content">
        <section>
            <div class="section banner-4 banner-section">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-md-12 text-center">
                            <div class="">
                                <p class="mb-3 content-1 h5 fs-1">    {{ ($lang == 'ar')? ' اشعارات' : "notification" }}

                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <ul>
        @foreach($notifications as $notification)
            <li>
                <strong>{{ $notification->data['title'] }}</strong>
                <p>{{ $notification->data['body'] }}</p>
                <a href="{{ $notification->data['url'] }}">مزيد من التفاصيل</a>
            </li>
        @endforeach
    </ul>
    @endsection
@section('script')
    <script src="https://cdn.jsdelivr.net/npm/resumablejs@1.1.0/resumable.min.js"></script>
    <script>
        document.querySelectorAll('input[type=checkbox][name="selected_products[]"]').forEach((checkbox) => {
            checkbox.addEventListener('change', function() {
                const quantityInput = document.getElementById(`quantity-${this.value}`);
                if (this.checked) {
                    quantityInput.style.display = 'block';
                } else {
                    quantityInput.style.display = 'none';
                    quantityInput.value = '';
                }
            });
        });
    </script>
@endsection
