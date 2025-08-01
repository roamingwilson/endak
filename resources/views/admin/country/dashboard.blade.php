@extends('layouts.dashboard.dashboard')
@section('title')
<?php $lang = config('app.locale'); ?>
{{ ($lang == 'ar')? 'إدارة الدول والمحافظات': "Countries & Governorates Management" }}
@endsection
@section('page_name')
{{ ($lang == 'ar')? 'إدارة الدول والمحافظات': "Countries & Governorates Management" }}
@endsection

@section('content')
<div class="container">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row">
        <!-- Countries Management -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4>{{ ($lang == 'ar')? 'إدارة الدول': "Countries Management" }}</h4>
                </div>
                <div class="card-body">
                    <p>{{ ($lang == 'ar')? 'يمكنك إدارة الدول وإضافة دول جديدة أو تعديل أو حذف الدول الموجودة': "You can manage countries, add new countries, edit or delete existing countries" }}</p>
                    <div class="d-grid gap-2">
                        <a href="{{ route('countries.index') }}" class="btn btn-primary">
                            <i class="fas fa-list"></i> {{ ($lang == 'ar')? 'عرض جميع الدول': "View All Countries" }}
                        </a>
                        <a href="{{ route('add_country') }}" class="btn btn-success">
                            <i class="fas fa-plus"></i> {{ ($lang == 'ar')? 'إضافة دولة جديدة': "Add New Country" }}
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Governorates Management -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4>{{ ($lang == 'ar')? 'إدارة المحافظات': "Governorates Management" }}</h4>
                </div>
                <div class="card-body">
                    <p>{{ ($lang == 'ar')? 'يمكنك إدارة المحافظات وإضافة محافظات جديدة أو تعديل أو حذف المحافظات الموجودة': "You can manage governorates, add new governorates, edit or delete existing governorates" }}</p>
                    <div class="d-grid gap-2">
                        <a href="{{ route('governorates.index') }}" class="btn btn-primary">
                            <i class="fas fa-list"></i> {{ ($lang == 'ar')? 'عرض جميع المحافظات': "View All Governorates" }}
                        </a>
                        <a href="{{ route('add_gover') }}" class="btn btn-success">
                            <i class="fas fa-plus"></i> {{ ($lang == 'ar')? 'إضافة محافظة جديدة': "Add New Governorate" }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5>{{ ($lang == 'ar')? 'إحصائيات سريعة': "Quick Statistics" }}</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="alert alert-info">
                                <h6>{{ ($lang == 'ar')? 'عدد الدول': "Countries Count" }}</h6>
                                <h3>{{ \App\Models\Country::count() }}</h3>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="alert alert-warning">
                                <h6>{{ ($lang == 'ar')? 'عدد المحافظات': "Governorates Count" }}</h6>
                                <h3>{{ \App\Models\Governements::count() }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
