@extends('layouts.dashboard.dashboard')
@section('content')
<div class="container">
    <h2>إضافة رقم واتساب مستلم</h2>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <form method="GET" action="{{ route('admin.whatsapp_recipients.create') }}" class="form-inline mb-3">
        <label for="department_id" class="mr-2">فلترة حسب القسم:</label>
        <select name="department_id" id="department_id" class="form-control mr-2" onchange="this.form.submit()">
            <option value="">كل الأقسام</option>
            @foreach($departments as $department)
                <option value="{{ $department->id }}" {{ (isset($selectedDepartment) && $selectedDepartment == $department->id) ? 'selected' : '' }}>
                    {{ $department->name_ar }}
                </option>
            @endforeach
        </select>
        @if(isset($selectedDepartment) && $selectedDepartment)
            <button type="button" class="btn btn-success" onclick="openSendModal('{{ $selectedDepartment }}', '{{ $departments->where('id', $selectedDepartment)->first()->name_ar }}')">
                إرسال رسالة جماعية
            </button>
        @endif
    </form>
    <form method="POST" action="{{ route('admin.whatsapp_recipients.store') }}">
        @csrf
        <div class="form-group">
            <label>رقم العميل</label>
            <input type="text" name="number" class="form-control" required>
        </div>
        <div class="form-group">
            <label>القسم</label>
            <select name="department_id" class="form-control" required>
                @foreach($departments as $department)
                    <option value="{{ $department->id }}">{{ $department->name_ar }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">إضافة</button>
    </form>
    <hr>
    <h3>جميع أرقام الاستقبال</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>رقم العميل</th>
                <th>القسم</th>
                <th>إرسال رسالة</th>
                <th>الإجراءات</th>
            </tr>
        </thead>
        <tbody>
            @foreach($recipients as $index => $recipient)
                <tr>
                    <td>{{ $index + 1 + (($recipients->currentPage() - 1) * $recipients->perPage()) }}</td>
                    <td>{{ $recipient->number }}</td>
                    <td>{{ $recipient->department->name_ar ?? '-' }}</td>
                    <td>
                        <button class="btn btn-success btn-sm" onclick="openSendModal({{ $recipient->department_id }}, '{{ $recipient->department->name_ar }}')">إرسال رسالة</button>
                    </td>
                    <td>
                        <a href="{{ route('admin.whatsapp_recipients.edit', $recipient->id) }}" class="btn btn-warning btn-sm">تعديل</a>
                        <form action="{{ route('admin.whatsapp_recipients.delete', $recipient->id) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('هل أنت متأكد من الحذف؟')">حذف</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $recipients->withQueryString()->links() }}

    <!-- Modal لإرسال الرسالة -->
    <div class="modal" id="sendModal" tabindex="-1" role="dialog" style="display:none; background:rgba(0,0,0,0.5); position:fixed; top:0; left:0; width:100vw; height:100vh; z-index:9999;">
      <div class="modal-dialog" role="document">
        <form method="POST" action="{{ route('admin.whatsapp_recipients.send_messages') }}">
          @csrf
          <input type="hidden" name="department_id" id="modal_department_id">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">إرسال رسالة إلى قسم <span id="modal_department_name"></span></h5>
              <button type="button" class="close" onclick="closeSendModal()" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="alert alert-info">
                سيتم إرسال الرسالة التالية تلقائياً لكل الأرقام:
              </div>
              <div class="form-group">
                <label for="custom_message">نص الرسالة</label>
                <textarea name="custom_message" id="custom_message" class="form-control" rows="3">{{ old('custom_message', $settings->whatsapp_offer_template ?? 'مرحبا يوجد عميل يحتاج خدمة خاصة بقسم {department} علي موقع endak.net في مدينة {city} , قدم عرض الان') }}</textarea>
                <small class="form-text text-muted">
                  يمكنك استخدام المتغيرات: <code>{department}</code> لاسم القسم و <code>{city}</code> لاسم المدينة.
                </small>
              </div>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary">إرسال</button>
              <button type="button" class="btn btn-secondary" onclick="closeSendModal()">إغلاق</button>
            </div>
          </div>
        </form>
      </div>
    </div>

    <script>
    function openSendModal(departmentId, departmentName) {
        document.getElementById('modal_department_id').value = departmentId;
        document.getElementById('modal_department_name').innerText = departmentName;
        document.getElementById('modal_department_name_preview').innerText = departmentName;
        document.getElementById('sendModal').style.display = 'block';
    }
    function closeSendModal() {
        document.getElementById('sendModal').style.display = 'none';
    }
    </script>
</div>
@endsection
