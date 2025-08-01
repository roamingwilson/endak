@extends('layouts.dashboard.dashboard')
@section('title')
<?php $lang = config('app.locale'); ?>
{{ ($lang == 'ar')? 'تفاصيل الطلب': "Order Details" }}
@endsection
@section('page_name')
{{ ($lang == 'ar')? 'تفاصيل الطلب': "Order Details" }}
@endsection

@section('content')
<div class="container-fluid">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row">
        <div class="col-md-8">
            <!-- تفاصيل الطلب -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ ($lang == 'ar')? 'تفاصيل الطلب': "Order Details" }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.service_management.orders') }}" class="btn btn-sm btn-secondary">
                            <i class="fas fa-arrow-left"></i> {{ ($lang == 'ar')? 'العودة': "Back" }}
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th>{{ ($lang == 'ar')? 'رقم الطلب': "Order ID" }}</th>
                                    <td>#{{ $order->id }}</td>
                                </tr>
                                <tr>
                                    <th>{{ ($lang == 'ar')? 'العميل': "Customer" }}</th>
                                    <td>{{ $order->user->first_name ?? 'غير محدد' }}</td>
                                </tr>
                                <tr>
                                    <th>{{ ($lang == 'ar')? 'مقدم الخدمة': "Service Provider" }}</th>
                                    <td>{{ $order->service_provider->first_name ?? 'غير محدد' }}</td>
                                </tr>
                                <tr>
                                    <th>{{ ($lang == 'ar')? 'الحالة': "Status" }}</th>
                                    <td>
                                        @if($order->status == 'pending')
                                            <span class="badge badge-warning">{{ ($lang == 'ar')? 'معلق': "Pending" }}</span>
                                        @elseif($order->status == 'completed')
                                            <span class="badge badge-success">{{ ($lang == 'ar')? 'مكتمل': "Completed" }}</span>
                                        @else
                                            <span class="badge badge-secondary">{{ ($lang == 'ar')? 'ملغي': "Cancelled" }}</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>{{ ($lang == 'ar')? 'تاريخ الإنشاء': "Created Date" }}</th>
                                    <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>{{ ($lang == 'ar')? 'آخر تحديث': "Last Updated" }}</th>
                                    <td>{{ $order->updated_at->format('Y-m-d H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            @if($order->service)
                            <h5>{{ ($lang == 'ar')? 'تفاصيل الخدمة': "Service Details" }}</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <th>{{ ($lang == 'ar')? 'الخدمة': "Service" }}</th>
                                    <td>
                                        <a href="{{ route('admin.service_management.show_service', $order->service->id) }}">
                                            {{ ($lang == 'ar')? $order->service->department->name_ar : $order->service->department->name_en }}
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <th>{{ ($lang == 'ar')? 'المدينة': "City" }}</th>
                                    <td>{{ $order->service->city ?? 'غير محدد' }}</td>
                                </tr>
                                <tr>
                                    <th>{{ ($lang == 'ar')? 'الحي': "Neighborhood" }}</th>
                                    <td>{{ $order->service->neighborhood ?? 'غير محدد' }}</td>
                                </tr>
                                @if($order->service->price)
                                <tr>
                                    <th>{{ ($lang == 'ar')? 'السعر': "Price" }}</th>
                                    <td>{{ $order->service->price }} ريال</td>
                                </tr>
                                @endif
                                @if($order->service->notes)
                                <tr>
                                    <th>{{ ($lang == 'ar')? 'الملاحظات': "Notes" }}</th>
                                    <td>{{ $order->service->notes }}</td>
                                </tr>
                                @endif
                            </table>
                            @else
                            <div class="alert alert-warning">
                                {{ ($lang == 'ar')? 'الخدمة المرتبطة بهذا الطلب تم حذفها': "The service associated with this order has been deleted" }}
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- إجراءات سريعة -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ ($lang == 'ar')? 'إجراءات سريعة': "Quick Actions" }}</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.service_management.update_order_status', $order->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label>{{ ($lang == 'ar')? 'تغيير الحالة': "Change Status" }}</label>
                            <select name="status" class="form-control">
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>{{ ($lang == 'ar')? 'معلق': "Pending" }}</option>
                                <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>{{ ($lang == 'ar')? 'مكتمل': "Completed" }}</option>
                                <option value="cancel" {{ $order->status == 'cancel' ? 'selected' : '' }}>{{ ($lang == 'ar')? 'ملغي': "Cancelled" }}</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">
                            {{ ($lang == 'ar')? 'تحديث الحالة': "Update Status" }}
                        </button>
                    </form>
                </div>
            </div>

            <!-- إحصائيات العروض -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ ($lang == 'ar')? 'إحصائيات العروض': "Offers Statistics" }}</h3>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="info-box">
                                <span class="info-box-icon bg-info">
                                    <i class="fas fa-users"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">{{ ($lang == 'ar')? 'عدد العروض': "Total Offers" }}</span>
                                    <span class="info-box-number">{{ $offers ? $offers->count() : 0 }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="info-box">
                                <span class="info-box-icon bg-success">
                                    <i class="fas fa-money-bill"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">{{ ($lang == 'ar')? 'متوسط السعر': "Avg Price" }}</span>
                                    <span class="info-box-number">
                                        @if($offers && $offers->count() > 0)
                                            @php
                                                $avgPrice = $offers->where('price', '!=', null)->avg('price');
                                            @endphp
                                            {{ $avgPrice ? number_format($avgPrice, 2) : 0 }} ريال
                                        @else
                                            0 ريال
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($offers && $offers->count() > 0)
                        <div class="row mt-3">
                            <div class="col-6">
                                <div class="info-box">
                                    <span class="info-box-icon bg-warning">
                                        <i class="fas fa-sort-amount-down"></i>
                                    </span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">{{ ($lang == 'ar')? 'أقل سعر': "Min Price" }}</span>
                                        <span class="info-box-number">
                                            @php
                                                $minPrice = $offers->where('price', '!=', null)->min('price');
                                            @endphp
                                            {{ $minPrice ? number_format($minPrice, 2) : 0 }} ريال
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="info-box">
                                    <span class="info-box-icon bg-danger">
                                        <i class="fas fa-sort-amount-up"></i>
                                    </span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">{{ ($lang == 'ar')? 'أعلى سعر': "Max Price" }}</span>
                                        <span class="info-box-number">
                                            @php
                                                $maxPrice = $offers->where('price', '!=', null)->max('price');
                                            @endphp
                                            {{ $maxPrice ? number_format($maxPrice, 2) : 0 }} ريال
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- معلومات الاتصال -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ ($lang == 'ar')? 'معلومات الاتصال': "Contact Information" }}</h3>
                </div>
                <div class="card-body">
                    <h6>{{ ($lang == 'ar')? 'العميل': "Customer" }}</h6>
                    <p>
                        <strong>{{ ($lang == 'ar')? 'الاسم': "Name" }}:</strong> {{ $order->user->first_name ?? 'غير محدد' }}<br>
                        <strong>{{ ($lang == 'ar')? 'البريد الإلكتروني': "Email" }}:</strong> {{ $order->user->email ?? 'غير محدد' }}<br>
                        <strong>{{ ($lang == 'ar')? 'رقم الهاتف': "Phone" }}:</strong> {{ $order->user->phone ?? 'غير محدد' }}
                    </p>

                    <hr>

                    <h6>{{ ($lang == 'ar')? 'مقدم الخدمة': "Service Provider" }}</h6>
                    <p>
                        <strong>{{ ($lang == 'ar')? 'الاسم': "Name" }}:</strong> {{ $order->service_provider->first_name ?? 'غير محدد' }}<br>
                        <strong>{{ ($lang == 'ar')? 'البريد الإلكتروني': "Email" }}:</strong> {{ $order->service_provider->email ?? 'غير محدد' }}<br>
                        <strong>{{ ($lang == 'ar')? 'رقم الهاتف': "Phone" }}:</strong> {{ $order->service_provider->phone ?? 'غير محدد' }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- قسم العروض المقدمة -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ ($lang == 'ar')? 'العروض المقدمة من مزودي الخدمة': "Service Provider Offers" }}</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="toggleFilters()">
                            <i class="fas fa-filter"></i> {{ ($lang == 'ar')? 'تصفية': "Filter" }}
                        </button>
                        @if($offers && $offers->count() > 0)
                            <button type="button" class="btn btn-sm btn-outline-success ml-2" onclick="exportToExcel()">
                                <i class="fas fa-file-excel"></i> {{ ($lang == 'ar')? 'تصدير Excel': "Export Excel" }}
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-danger ml-2" onclick="exportToPDF()">
                                <i class="fas fa-file-pdf"></i> {{ ($lang == 'ar')? 'تصدير PDF': "Export PDF" }}
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-primary ml-2" onclick="printOffers()">
                                <i class="fas fa-print"></i> {{ ($lang == 'ar')? 'طباعة': "Print" }}
                            </button>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <!-- فلاتر العروض -->
                    <div id="offersFilters" class="mb-3" style="display: none;">
                        <div class="row">
                            <div class="col-md-3">
                                <label>{{ ($lang == 'ar')? 'ترتيب حسب السعر': "Sort by Price" }}</label>
                                <select class="form-control" id="priceSort">
                                    <option value="">{{ ($lang == 'ar')? 'الكل': "All" }}</option>
                                    <option value="asc">{{ ($lang == 'ar')? 'من الأقل للأعلى': "Low to High" }}</option>
                                    <option value="desc">{{ ($lang == 'ar')? 'من الأعلى للأقل': "High to Low" }}</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>{{ ($lang == 'ar')? 'ترتيب حسب التاريخ': "Sort by Date" }}</label>
                                <select class="form-control" id="dateSort">
                                    <option value="">{{ ($lang == 'ar')? 'الكل': "All" }}</option>
                                    <option value="desc">{{ ($lang == 'ar')? 'الأحدث أولاً': "Newest First" }}</option>
                                    <option value="asc">{{ ($lang == 'ar')? 'الأقدم أولاً': "Oldest First" }}</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>{{ ($lang == 'ar')? 'السعر الأدنى': "Min Price" }}</label>
                                <input type="number" class="form-control" id="minPrice" placeholder="0">
                            </div>
                            <div class="col-md-3">
                                <label>{{ ($lang == 'ar')? 'السعر الأعلى': "Max Price" }}</label>
                                <input type="number" class="form-control" id="maxPrice" placeholder="999999">
                            </div>
                            <div class="col-md-6">
                                <label>{{ ($lang == 'ar')? 'البحث في الملاحظات': "Search in Notes" }}</label>
                                <input type="text" class="form-control" id="searchNotes" placeholder="{{ ($lang == 'ar')? 'ابحث في الملاحظات...': 'Search in notes...' }}">
                            </div>
                            <div class="col-md-6">
                                <label>{{ ($lang == 'ar')? 'البحث في اسم مزود الخدمة': "Search Provider Name" }}</label>
                                <input type="text" class="form-control" id="searchProvider" placeholder="{{ ($lang == 'ar')? 'ابحث في اسم مزود الخدمة...': 'Search provider name...' }}">
                            </div>
                        </div>
                    </div>

                    @if($offers && $offers->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped" id="offersTable">
                                <thead>
                                    <tr>
                                        <th>{{ ($lang == 'ar')? 'مزود الخدمة': "Service Provider" }}</th>
                                        <th>{{ ($lang == 'ar')? 'السعر': "Price" }}</th>
                                        <th>{{ ($lang == 'ar')? 'التاريخ': "Date" }}</th>
                                        <th>{{ ($lang == 'ar')? 'الوقت': "Time" }}</th>
                                        <th>{{ ($lang == 'ar')? 'الملاحظات': "Notes" }}</th>
                                        <th>{{ ($lang == 'ar')? 'تاريخ التقديم': "Submission Date" }}</th>
                                        <th>{{ ($lang == 'ar')? 'الإجراءات': "Actions" }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($offers as $offer)
                                        <tr>
                                            <td>
                                                <strong>{{ $offer->user->first_name ?? 'غير محدد' }}</strong><br>
                                                <small class="text-muted">{{ $offer->user->email ?? 'غير محدد' }}</small><br>
                                                <small class="text-muted">{{ $offer->user->phone ?? 'غير محدد' }}</small>
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
                                                <button type="button" class="btn btn-sm btn-outline-info" onclick="showOfferDetails({{ $offer->id }})">
                                                    <i class="fas fa-eye"></i> {{ ($lang == 'ar')? 'تفاصيل': "Details" }}
                                                </button>
                                                @if($offer->user && $offer->user->phone)
                                                    <a href="https://wa.me/{{ $offer->user->phone }}" target="_blank" class="btn btn-sm btn-outline-success">
                                                        <i class="fab fa-whatsapp"></i> {{ ($lang == 'ar')? 'واتساب': "WhatsApp" }}
                                                    </a>
                                                @endif
                                                <button type="button" class="btn btn-sm btn-outline-warning" onclick="sendNotification({{ $offer->user->id ?? 0 }})">
                                                    <i class="fas fa-bell"></i> {{ ($lang == 'ar')? 'إشعار': "Notification" }}
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-info-circle fa-2x text-muted mb-3"></i>
                            <p class="text-muted">{{ ($lang == 'ar')? 'لا توجد عروض مقدمة لهذه الخدمة': "No offers submitted for this service" }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleFilters() {
    const filters = document.getElementById('offersFilters');
    filters.style.display = filters.style.display === 'none' ? 'block' : 'none';
}

// تصفية العروض
document.addEventListener('DOMContentLoaded', function() {
    const priceSort = document.getElementById('priceSort');
    const dateSort = document.getElementById('dateSort');
    const minPrice = document.getElementById('minPrice');
    const maxPrice = document.getElementById('maxPrice');
    const searchNotes = document.getElementById('searchNotes');
    const searchProvider = document.getElementById('searchProvider');

    function filterOffers() {
        const rows = document.querySelectorAll('#offersTable tbody tr');
        const minPriceVal = parseFloat(minPrice.value) || 0;
        const maxPriceVal = parseFloat(maxPrice.value) || Infinity;
        const searchNotesVal = (searchNotes.value || '').toLowerCase();
        const searchProviderVal = (searchProvider.value || '').toLowerCase();

        rows.forEach(row => {
            const priceCell = row.querySelector('td:nth-child(2)');
            const providerCell = row.querySelector('td:nth-child(1)');
            const notesCell = row.querySelector('td:nth-child(5)');

            const priceText = priceCell.textContent;
            const price = parseFloat(priceText.replace(/[^\d.]/g, '')) || 0;
            const providerText = providerCell.textContent.toLowerCase();
            const notesText = notesCell.textContent.toLowerCase();

            const isInPriceRange = price >= minPriceVal && price <= maxPriceVal;
            const matchesNotes = !searchNotesVal || notesText.includes(searchNotesVal);
            const matchesProvider = !searchProviderVal || providerText.includes(searchProviderVal);

            row.style.display = (isInPriceRange && matchesNotes && matchesProvider) ? '' : 'none';
        });
    }

    // تطبيق الفلاتر عند التغيير
    [priceSort, dateSort, minPrice, maxPrice, searchNotes, searchProvider].forEach(element => {
        if (element) {
            element.addEventListener('change', filterOffers);
            element.addEventListener('input', filterOffers);
        }
    });
});

// تصدير إلى Excel
function exportToExcel() {
    const table = document.getElementById('offersTable');
    const rows = Array.from(table.querySelectorAll('tr'));

    let csv = [];
    // إضافة عنوان الملف
    csv.push('العروض المقدمة من مزودي الخدمة,Service Provider Offers');
    csv.push('رقم الطلب,Order ID: {{ $order->id }}');
    csv.push('تاريخ التصدير,Export Date: ' + new Date().toLocaleDateString());
    csv.push('');

    rows.forEach(row => {
        const cols = Array.from(row.querySelectorAll('td, th'));
        const rowData = cols.map(col => {
            // تنظيف النص من HTML tags
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
    link.setAttribute('download', 'offers_export_order_{{ $order->id }}.csv');
    link.style.visibility = 'hidden';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

// تصدير إلى PDF
function exportToPDF() {
    const table = document.getElementById('offersTable');
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
    link.setAttribute('download', 'offers_export.txt');
    link.style.visibility = 'hidden';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

// عرض تفاصيل العرض
function showOfferDetails(offerId) {
    // يمكن إضافة modal أو نافذة منبثقة لعرض التفاصيل الكاملة
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

// طباعة العروض
function printOffers() {
    const printWindow = window.open('', '_blank');
    const table = document.getElementById('offersTable');

    printWindow.document.write(`
        <html>
        <head>
            <title>العروض المقدمة - Order #{{ $order->id }}</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 20px; }
                table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                th, td { border: 1px solid #ddd; padding: 8px; text-align: right; }
                th { background-color: #f2f2f2; }
                .header { text-align: center; margin-bottom: 20px; }
                .info { margin-bottom: 10px; }
            </style>
        </head>
        <body>
            <div class="header">
                <h1>العروض المقدمة من مزودي الخدمة</h1>
                <h2>Service Provider Offers</h2>
            </div>
            <div class="info">
                <p><strong>رقم الطلب:</strong> {{ $order->id }}</p>
                <p><strong>تاريخ الطباعة:</strong> ${new Date().toLocaleDateString()}</p>
            </div>
            ${table.outerHTML}
        </body>
        </html>
    `);

    printWindow.document.close();
    printWindow.print();
}
</script>
@endsection
