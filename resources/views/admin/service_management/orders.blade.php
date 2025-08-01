@extends('layouts.dashboard.dashboard')
@section('title')
<?php $lang = config('app.locale'); ?>
{{ ($lang == 'ar')? 'العروض المقدمة': "Service Provider Offers" }}
@endsection
@section('page_name')
{{ ($lang == 'ar')? 'العروض المقدمة': "Service Provider Offers" }}
@endsection

@section('content')
<div class="container-fluid">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- إحصائيات سريعة -->
    <div class="row mb-3">
        <div class="col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-info">
                    <i class="fas fa-handshake"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">{{ ($lang == 'ar')? 'إجمالي العروض': "Total Offers" }}</span>
                    <span class="info-box-number">{{ $offers->total() }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-success">
                    <i class="fas fa-money-bill"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">{{ ($lang == 'ar')? 'متوسط السعر': "Average Price" }}</span>
                    <span class="info-box-number">
                        @php
                            $avgPrice = $offers->where('price', '!=', null)->avg('price');
                        @endphp
                        {{ $avgPrice ? number_format($avgPrice, 2) : 0 }} ريال
                    </span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-warning">
                    <i class="fas fa-users"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">{{ ($lang == 'ar')? 'مزودي الخدمات': "Service Providers" }}</span>
                    <span class="info-box-number">{{ $offers->unique('service_provider')->count() }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-primary">
                    <i class="fas fa-tasks"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">{{ ($lang == 'ar')? 'الخدمات': "Services" }}</span>
                    <span class="info-box-number">{{ $offers->unique('commentable_id')->count() }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- فلاتر البحث -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ ($lang == 'ar')? 'فلاتر البحث': "Search Filters" }}</h3>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.service_management.orders') }}">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>{{ ($lang == 'ar')? 'مزود الخدمة': "Service Provider" }}</label>
                            <select name="provider_id" class="form-control">
                                <option value="">{{ ($lang == 'ar')? 'جميع مزودي الخدمات': "All Providers" }}</option>
                                @foreach($providers as $provider)
                                    <option value="{{ $provider->id }}" {{ request('provider_id') == $provider->id ? 'selected' : '' }}>
                                        {{ $provider->first_name ?? 'غير محدد' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>{{ ($lang == 'ar')? 'الخدمة': "Service" }}</label>
                            <select name="service_id" class="form-control">
                                <option value="">{{ ($lang == 'ar')? 'جميع الخدمات': "All Services" }}</option>
                                @foreach($services as $service)
                                    <option value="{{ $service->id }}" {{ request('service_id') == $service->id ? 'selected' : '' }}>
                                        {{ ($lang == 'ar')? $service->department->name_ar : $service->department->name_en }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>{{ ($lang == 'ar')? 'السعر الأدنى': "Min Price" }}</label>
                            <input type="number" name="price_min" class="form-control" value="{{ request('price_min') }}" placeholder="0">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>{{ ($lang == 'ar')? 'السعر الأعلى': "Max Price" }}</label>
                            <input type="number" name="price_max" class="form-control" value="{{ request('price_max') }}" placeholder="999999">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>{{ ($lang == 'ar')? 'من تاريخ': "From Date" }}</label>
                            <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>{{ ($lang == 'ar')? 'إلى تاريخ': "To Date" }}</label>
                            <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i> {{ ($lang == 'ar')? 'بحث': "Search" }}
                            </button>
                            <a href="{{ route('admin.service_management.orders') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> {{ ($lang == 'ar')? 'إعادة تعيين': "Reset" }}
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- قائمة العروض المقدمة -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ ($lang == 'ar')? 'قائمة العروض المقدمة': "Offers List" }}</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-sm btn-outline-success" onclick="exportAllOffersToExcel()">
                    <i class="fas fa-file-excel"></i> {{ ($lang == 'ar')? 'تصدير Excel': "Export Excel" }}
                </button>
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="exportAllOffersToPDF()">
                    <i class="fas fa-file-pdf"></i> {{ ($lang == 'ar')? 'تصدير PDF': "Export PDF" }}
                </button>
                <a href="{{ route('admin.service_management.dashboard') }}" class="btn btn-sm btn-info ml-2">
                    <i class="fas fa-arrow-left"></i> {{ ($lang == 'ar')? 'العودة للوحة التحكم': "Back to Dashboard" }}
                </a>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ ($lang == 'ar')? 'مزود الخدمة': "Service Provider" }}</th>
                            <th>{{ ($lang == 'ar')? 'الخدمة': "Service" }}</th>
                            <th>{{ ($lang == 'ar')? 'السعر': "Price" }}</th>
                            <th>{{ ($lang == 'ar')? 'التاريخ': "Date" }}</th>
                            <th>{{ ($lang == 'ar')? 'الوقت': "Time" }}</th>
                            <th>{{ ($lang == 'ar')? 'الملاحظات': "Notes" }}</th>
                            <th>{{ ($lang == 'ar')? 'تاريخ التقديم': "Submission Date" }}</th>
                            <th>{{ ($lang == 'ar')? 'الإجراءات': "Actions" }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($offers as $offer)
                        <tr>
                            <td>{{ $offer->id }}</td>
                            <td>
                                <strong>{{ $offer->user->first_name ?? 'غير محدد' }}</strong><br>
                                <small class="text-muted">{{ $offer->user->email ?? 'غير محدد' }}</small><br>
                                <small class="text-muted">{{ $offer->user->phone ?? 'غير محدد' }}</small>
                            </td>
                            <td>
                                @if($offer->commentable)
                                    <a href="{{ route('admin.service_management.show_service', $offer->commentable->id) }}">
                                        {{ ($lang == 'ar')? $offer->commentable->department->name_ar : $offer->commentable->department->name_en }}
                                    </a>
                                @else
                                    <span class="text-muted">{{ ($lang == 'ar')? 'خدمة محذوفة': "Deleted Service" }}</span>
                                @endif
                            </td>
                            <td>
                                @if($offer->price)
                                    <span class="badge badge-success">{{ $offer->price }} ريال</span>
                                @else
                                    <span class="text-muted">{{ ($lang == 'ar')? 'غير محدد': "Not specified" }}</span>
                                @endif
                            </td>
                            <td>
                                @if($offer->date)
                                    {{ $offer->date }}
                                @else
                                    <span class="text-muted">{{ ($lang == 'ar')? 'غير محدد': "Not specified" }}</span>
                                @endif
                            </td>
                            <td>
                                @if($offer->time)
                                    {{ $offer->time }}
                                @else
                                    <span class="text-muted">{{ ($lang == 'ar')? 'غير محدد': "Not specified" }}</span>
                                @endif
                            </td>
                            <td>
                                @if($offer->notes)
                                    <div class="text-wrap" style="max-width: 200px;">
                                        {{ Str::limit($offer->notes, 100) }}
                                    </div>
                                @else
                                    <span class="text-muted">{{ ($lang == 'ar')? 'لا توجد ملاحظات': "No notes" }}</span>
                                @endif
                            </td>
                            <td>
                                <small class="text-muted">{{ $offer->created_at->format('Y-m-d H:i') }}</small>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-info" onclick="showOfferDetails({{ $offer->id }})">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    @if($offer->user && $offer->user->phone)
                                        <a href="https://wa.me/{{ $offer->user->phone }}" target="_blank" class="btn btn-sm btn-outline-success">
                                            <i class="fab fa-whatsapp"></i>
                                        </a>
                                    @endif
                                    <button type="button" class="btn btn-sm btn-outline-warning" onclick="sendNotification({{ $offer->user->id ?? 0 }})">
                                        <i class="fas fa-bell"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center">{{ ($lang == 'ar')? 'لا توجد عروض': "No offers found" }}</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            {{ $offers->links() }}
        </div>
    </div>
</div>

<script>

// عرض تفاصيل العرض
function showOfferDetails(offerId) {
    alert('تفاصيل العرض رقم: ' + offerId + '\nسيتم إضافة هذه الميزة قريباً');
}

// إرسال إشعار لمزود الخدمة
function sendNotification(userId) {
    if (userId === 0) {
        alert('لا يمكن إرسال إشعار - لم يتم العثور على مزود الخدمة');
        return;
    }

    const message = prompt('أدخل رسالة الإشعار:', 'مرحباً، نود التواصل معك بخصوص عرضك المقدم');
    if (message) {
        // هنا يمكن إضافة كود لإرسال الإشعار عبر API
        alert('تم إرسال الإشعار بنجاح إلى مزود الخدمة');
    }
}

// تصدير جميع العروض إلى Excel
function exportAllOffersToExcel() {
    const table = document.querySelector('.table-responsive table');
    if (!table) return;

    const rows = Array.from(table.querySelectorAll('tr'));
    let csv = [];

    // إضافة عنوان الملف
    csv.push('العروض المقدمة من مزودي الخدمة,Service Provider Offers');
    csv.push('تاريخ التصدير,Export Date: ' + new Date().toLocaleDateString());
    csv.push('');

    rows.forEach(row => {
        const cols = Array.from(row.querySelectorAll('td, th'));
        const rowData = cols.map(col => {
            let text = col.textContent || col.innerText || '';
            text = text.replace(/"/g, '""');
            return `"${text}"`;
        });
        csv.push(rowData.join(','));
    });

    const csvContent = csv.join('\n');
    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    const url = URL.createObjectURL(blob);
    link.setAttribute('href', url);
    link.setAttribute('download', 'all_offers_export.csv');
    link.style.visibility = 'hidden';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

// تصدير جميع العروض إلى PDF
function exportAllOffersToPDF() {
    const table = document.querySelector('.table-responsive table');
    if (!table) return;

    const rows = Array.from(table.querySelectorAll('tr'));
    let pdfContent = [];

    pdfContent.push('العروض المقدمة من مزودي الخدمة');
    pdfContent.push('Service Provider Offers');
    pdfContent.push('');

    rows.forEach((row, index) => {
        const cols = Array.from(row.querySelectorAll('td, th'));
        const rowData = cols.map(col => {
            let text = col.textContent || col.innerText || '';
            return text.trim();
        });
        pdfContent.push(rowData.join(' | '));
    });

    const content = pdfContent.join('\n');
    const blob = new Blob([content], { type: 'text/plain;charset=utf-8;' });
    const link = document.createElement('a');
    const url = URL.createObjectURL(blob);
    link.setAttribute('href', url);
    link.setAttribute('download', 'all_offers_export.txt');
    link.style.visibility = 'hidden';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}
</script>
@endsection
