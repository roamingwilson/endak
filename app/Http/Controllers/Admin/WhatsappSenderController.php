<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WhatsappSender;
use Illuminate\Http\Request;
use App\Models\Department;

class WhatsappSenderController extends Controller
{
    public function create()
    {
        $departments = Department::all();
        $senders = WhatsappSender::with('departments')->get();
        return view('admin.whatsapp.sender', compact('departments', 'senders'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'number' => 'required|unique:whatsapp_senders,number',
            'token' => 'required',
            'instance_id' => 'required',
            'departments' => 'required|array',
            'departments.*' => 'exists:departments,id',
        ]);

        $sender = WhatsappSender::create($request->only('number', 'token', 'instance_id'));
        $sender->departments()->sync($request->departments);

        return redirect()->back()->with('success', 'تم إضافة رقم الإرسال بنجاح');
    }

    public function edit($id)
    {
        $sender = \App\Models\WhatsappSender::findOrFail($id);
        $departments = \App\Models\Department::all();
        return view('admin.whatsapp.sender_edit', compact('sender', 'departments'));
    }

    public function update(Request $request, $id)
    {
        $sender = WhatsappSender::findOrFail($id);
        $request->validate([
            'number' => 'required|unique:whatsapp_senders,number,' . $id,
            'token' => 'required',
            'instance_id' => 'required',
            'departments' => 'required|array',
            'departments.*' => 'exists:departments,id',
        ]);
        $sender->update($request->only('number', 'token', 'instance_id'));
        $sender->departments()->sync($request->departments);
        return redirect()->route('admin.whatsapp_senders.create')->with('success', 'تم تحديث رقم الإرسال بنجاح');
    }

    public function destroy($id)
    {
        $sender = \App\Models\WhatsappSender::findOrFail($id);
        $sender->delete();
        return redirect()->back()->with('success', 'تم حذف رقم الإرسال بنجاح');
    }
}
