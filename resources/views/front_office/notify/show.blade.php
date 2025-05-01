@extends('layouts.home')

@section('title')
    <?php
    $lang = config('app.locale');
    ?>
    {{ ($lang == 'ar') ? 'اشعارات' : "Notifications" }}
@endsection

@section('content')

    <div class="main-content app-content">
        <section>
    {{ ($lang == 'ar') ? 'اشعارات' : "Notifications" }}
        </section>

        <div class="notifications-wrapper">
            @if($notifications->count())
                @foreach($notifications as $notification)
                    <div class="notification-card" data-id="{{ $notification->id }}">
                        <div class="notification-header">
                            <h5 class="notification-title">{{ $notification->data['title'] }}</h5>
                            <span class="notification-time">{{ $notification->created_at->diffForHumans() }}</span>
                        </div>
                        <div class="notification-body">
                            <p>{{ $notification->data['body'] }}</p>
                        </div>
                        <div class="notification-footer">
                            <a href="{{route('general_comments.show',auth()->id())}}" class="btn btn-details">
                                {{ ($lang == 'ar') ? 'مزيد من التفاصيل' : 'View Details' }}
                            </a>
                            <a href="javascript:void(0)" class="btn btn-mark-read mark-read">
                                {{ ($lang == 'ar') ? 'تم قراءة الإشعار' : 'Mark as Read' }}
                            </a>
                        </div>
                    </div>
                @endforeach
            @else
                <p>{{ ($lang == 'ar') ? 'لا توجد إشعارات' : 'No Notifications' }}</p>
            @endif
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
