@extends('layouts.home')
@section('title')
<?php
$lang = config('app.locale');
?>
{{ ($lang == 'ar')? $main->name_ar : $main->name_en }}
@endsection
@section('style')
<style>
    .stars-card {
        min-height: 20px;
    }

    .stars-card svg {
        margin-right: 3px;
        color: #818894;
    }

    .stars-card svg.active {
        color: #ffc600;
        fill: #ffc600;
    }

    .stars-card i.active svg {
        color: #ffc600;
        fill: #ffc600;
    }

    .myform {
        max-width: 500px;
        margin: 0 auto;
    }

    .input-group {
        width: 100%;
    }

    .form-control {
        height: 60px;
        font-size: 1.2rem;
        padding: 15px;
        border-radius: 5px 0 0 5px;
        border: 1px solid #ced4da;
    }

    .btn-primary {
        height: 60px;
        font-size: 1.2rem;
        border-radius: 0 5px 5px 0;
    }

    .card-custom {
        width: 100%;
        height: 200px;
        border-radius: 15px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        background-color: rgba(169, 169, 169, 0.3);
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    .card-custom img {
        width: 80%;
        height: auto;
        object-fit: contain;
        max-height: 150px;
    }

    .card-custom .card-body {
        text-align: center;
        padding: 10px;
    }

    .cards-container {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
    }

    @media (max-width: 768px) {
        .cards-container {
            grid-template-columns: repeat(2, 1fr);
        }
    }
</style>
@endsection
@section('content')
 

<section class="section overflow-hidden">
    {{-- <img src="../assets/images/patterns/2.png" alt="img" class="patterns-6 op-1 z-index-0 top-14p">
    <img src="../assets/images/patterns/7.png" alt="img" class="patterns-5 left-0 transform-rotate-180 z-index-0"> --}}
    <div class="container">
        <div class="row">
            <div class="heading-section">
                <div class="heading-subtitle">
                    <span class="tx-primary tx-16 fw-semibold"
                        style="color: black;">{{ __('department.departments') }}</span>
                </div>
            </div>

            <div class="cards-container">

                    @forelse ($sub_departments as $department)
    
                    <div class="card card-custom">
                        @if ($department->image)
                            <a href="{{ route('departments.show', $department->id) }}">
                                <img src="{{ $department->image_url }}" class="card-img-top" alt="{{ $department->name_ar }}">
                            </a>
                        @endif
                        <div class="card-body">
                            <a href="{{ route('departments.show', $department->id) }}">
                                <p class="card-text">{{ $lang == 'ar' ? $department->name_ar : $department->name_en }}</p>
                            </a>
                        </div>
                    </div>
                    @empty
                @endforelse
    
    
                </div>
    
    
            </div>
    
    
        </section>

    
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
