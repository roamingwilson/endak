@extends('layouts.dashboard.dashboard')
@section('title')
<?php $lang = config('app.locale'); ?>
{{ ($lang == 'ar')? 'إدارة الدول': "Countries Management" }}
@endsection
@section('page_name')
{{ ($lang == 'ar')? 'إدارة الدول': "Countries Management" }}
@endsection

@section('content')
<div class="container">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row mb-3">
        <div class="col-md-6">
            <h3>{{ ($lang == 'ar')? 'قائمة الدول': "Countries List" }}</h3>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('add_country') }}" class="btn btn-primary">
                {{ ($lang == 'ar')? 'إضافة دولة جديدة': "Add New Country" }}
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>{{ ($lang == 'ar')? 'الاسم بالعربية': "Name (Arabic)" }}</th>
                            <th>{{ ($lang == 'ar')? 'الاسم بالإنجليزية': "Name (English)" }}</th>
                            <th>{{ ($lang == 'ar')? 'عدد المحافظات': "Governorates Count" }}</th>
                            <th>{{ ($lang == 'ar')? 'الإجراءات': "Actions" }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($countries as $country)
                        <tr>
                            <td>{{ $country->name_ar }}</td>
                            <td>{{ $country->name_en }}</td>
                            <td>{{ $country->governements->count() }}</td>
                            <td>
                                <a href="{{ route('countries.edit', $country->id) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i> {{ ($lang == 'ar')? 'تعديل': "Edit" }}
                                </a>
                                <form action="{{ route('countries.destroy', $country->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('{{ ($lang == 'ar')? 'هل أنت متأكد من حذف هذه الدولة؟': 'Are you sure you want to delete this country?' }}')">
                                        <i class="fas fa-trash"></i> {{ ($lang == 'ar')? 'حذف': "Delete" }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
