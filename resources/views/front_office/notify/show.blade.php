@extends('layouts.home')

@section('title')
    <?php
    $lang = config('app.locale');
    ?>
    {{ ($lang == 'ar') ? 'اشعارات' : "Notifications" }}
@endsection

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h2 class="fw-bold mb-0" style="color:#ff9800;"><i class="fas fa-bell me-2"></i>{{ $lang == 'ar' ? 'الإشعارات' : 'Notifications' }}</h2>
                <span class="badge bg-primary fs-6">{{ $notifications->count() }} {{ $lang == 'ar' ? 'إشعار' : 'Notifications' }}</span>
                <button id="markAllReadBtn" class="btn btn-sm btn-warning ms-2" style="min-width:150px;">
                    <i class="fas fa-check-double"></i> {{ $lang == 'ar' ? 'تعليم الكل كمقروء' : 'Mark all as read' }}
                </button>
            </div>
            <div class="notifications-wrapper">
                @if($notifications->count())
                    @foreach($notifications as $notification)
                        <div class="notification-card position-relative {{ $notification->read_at ? 'read' : 'unread' }}" data-id="{{ $notification->id }}">
                            <div class="notification-header d-flex align-items-center justify-content-between mb-2">
                                <h5 class="notification-title mb-0"><i class="fas fa-info-circle me-2"></i>{{ $notification->data['title'] }}</h5>
                                <span class="notification-time small"><i class="far fa-clock me-1"></i>{{ $notification->created_at->diffForHumans() }}</span>
                            </div>
                            <div class="notification-body mb-3">
                                <p class="mb-0">{{ $notification->data['body'] }}</p>
                            </div>
                            <div class="notification-footer d-flex gap-2 justify-content-end">
                                @php $url = $notification->data['url'] ?? null; @endphp
                                @if($url)
                                    <a href="{{ $url }}" class="btn btn-details">
                                        <i class="fas fa-eye"></i> {{ $lang == 'ar' ? 'مزيد من التفاصيل' : 'View Details' }}
                                    </a>
                                @endif
                                <a href="javascript:void(0)" class="btn btn-mark-read mark-read">
                                    <i class="fas fa-check"></i> {{ $lang == 'ar' ? 'تم قراءة الإشعار' : 'Mark as Read' }}
                                </a>
                            </div>
                            @if(!$notification->read_at)
                                <span class="position-absolute top-0 end-0 translate-middle badge rounded-pill bg-danger" style="font-size:10px;">{{ $lang == 'ar' ? 'جديد' : 'New' }}</span>
                            @endif
                        </div>
                    @endforeach
                @else
                    <div class="alert alert-info text-center py-4 mb-0">
                        <i class="fas fa-info-circle fa-2x mb-2"></i><br>
                        {{ $lang == 'ar' ? 'لا توجد إشعارات' : 'No Notifications' }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
    <script>
        // تنفيذ AJAX لتحديث حالة النوتيفيكاشن
        document.querySelectorAll('.mark-read').forEach((btn) => {
            btn.addEventListener('click', function() {
                const notificationCard = this.closest('.notification-card');
                const notificationId = notificationCard.getAttribute('data-id');

                // إرسال طلب AJAX لتحديث الحالة
                fetch(`/notifications/${notificationId}/mark-as-read`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        notificationCard.classList.add('read'); // إضافة Class للإشارة إلى أن النوتيفيكاشن مقروء
                    }
                })
                .catch(error => console.error('Error:', error));
            });
        });

        // زر تعليم الكل كمقروء
        document.getElementById('markAllReadBtn').addEventListener('click', function() {
            fetch("{{ route('notifications.markAllAsRead') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content'),
                    'Accept': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                if(data.success){
                    location.reload();
                }
            })
            .catch(error => console.error('Error:', error));
        });
    </script>
@endsection

@section('style')
    <style>
        .notifications-wrapper {
            display: flex;
            flex-direction: column;
            gap: 20px;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 10px;
        }

        .notification-card {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .notification-card.read {
            background-color: #e6f7ff; /* تغيير اللون عند القراءة */
        }

        .notification-card:hover {
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
            transform: translateY(-5px);
        }

        .notification-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
        }

        .notification-title {
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }

        .notification-time {
            font-size: 12px;
            color: #999;
        }

        .notification-body {
            font-size: 14px;
            margin-bottom: 20px;
            color: #555;
        }

        .notification-footer {
            display: flex;
            gap: 15px;
            justify-content: flex-end;
        }

        .btn {
            padding: 10px 20px;
            font-size: 14px;
            text-transform: uppercase;
            border-radius: 25px;
            text-align: center;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .btn-details {
            background-color: #007bff;
            color: white;
        }

        .btn-details:hover {
            background-color: #0056b3;
        }

        .btn-mark-read {
            background-color: #28a745;
            color: white;
        }

        .btn-mark-read:hover {
            background-color: #218838;
        }

    </style>
@endsection
