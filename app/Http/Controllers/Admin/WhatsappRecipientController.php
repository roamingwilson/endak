<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\WhatsappRecipients;
use Illuminate\Http\Request;
use App\Jobs\SendWhatsappMessageJob;
use App\Models\MessageTemplate;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\WhatsappRecipientsImport;

class WhatsappRecipientController extends Controller
{
    public function create(Request $request)
    {
        $departments = \App\Models\Department::all();
        $selectedDepartment = $request->get('department_id');
        $recipients = \App\Models\WhatsappRecipients::with('department')
            ->when($selectedDepartment, function($query) use ($selectedDepartment) {
                $query->where('department_id', $selectedDepartment);
            })
            ->paginate(20); // عرض 20 رقم فقط في كل صفحة
        return view('admin.whatsapp.recipients', compact('departments', 'recipients', 'selectedDepartment'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'number' => 'required',
            'department_id' => 'required|exists:departments,id',
        ]);

        WhatsappRecipients::create($request->only('number', 'department_id'));

        return redirect()->back()->with('success', 'تم إضافة رقم الاستقبال بنجاح');
    }

    public function edit($id)
    {
        $recipient = \App\Models\WhatsappRecipients::findOrFail($id);
        $departments = \App\Models\Department::all();
        return view('admin.whatsapp.recipient_edit', compact('recipient', 'departments'));
    }

    public function update(Request $request, $id)
    {
        $recipient = \App\Models\WhatsappRecipients::findOrFail($id);
        $recipient->update($request->only('number', 'department_id'));
        return redirect()->route('admin.whatsapp_recipients.create')->with('success', 'تم تحديث رقم الاستقبال بنجاح');
    }

    public function destroy($id)
    {
        $recipient = \App\Models\WhatsappRecipients::findOrFail($id);
        $recipient->delete();
        return redirect()->back()->with('success', 'تم حذف رقم الاستقبال بنجاح');
    }

    public function sendMessages(Request $request)
    {
        set_time_limit(0);
        $request->validate([
            'department_id' => 'required|exists:departments,id',
        ]);

        $department = \App\Models\Department::find($request->department_id);
        $departmentName = $department ? $department->name_ar : '';
        $cityName = 'مكة';
        // استخدم الرسالة المخصصة إذا تم إدخالها، وإلا استخدم القالب الافتراضي
        if ($request->filled('custom_message')) {
            $template = $request->custom_message;
        } else {
            $settings = \App\Models\Settings::first();
            $template = $settings->whatsapp_offer_template ?? 'مرحبا يوجد عميل يحتاج خدمة خاصة بقسم {department} علي موقع endak.net في مدينة {city} , قدم عرض الان';
        }
        $message = str_replace(
            ['{department}', '{city}'],
            [$departmentName, $cityName],
            $template
        );

        // جلب أرقام الإرسال المرتبطة بالقسم عبر الجدول الوسيط
        $senders = \App\Models\WhatsappSender::whereHas('departments', function($q) use ($request) {
            $q->where('departments.id', $request->department_id);
        })->get();
        $recipients = \App\Models\WhatsappRecipients::where('department_id', $request->department_id)->pluck('number')->toArray();
        $senderCount = $senders->count();
        $i = 0;
        foreach ($recipients as $number) {
            if ($senderCount > 0) {
                $sender = $senders[$i % $senderCount];
                SendWhatsappMessageJob::dispatch($number, $message, $sender->number, $sender->token, $sender->instance_id)
                    ->delay(now()->addSeconds(rand(1, 10)));
                $i++;
            }
            // dd($sender);
        }

        return redirect()->back()->with('success', 'تم إرسال الرسائل بنجاح');
    }

    public function import(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls'
        ]);

        Excel::import(new \App\Imports\WhatsappRecipientsImport, $request->file('excel_file'));

        return redirect()->back()->with('success', 'تم استيراد الأرقام بنجاح');
    }
}
