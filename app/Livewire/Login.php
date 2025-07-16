<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Login extends Component
{
    public $email;
    public $password;

    protected $rules = [
        'email' => 'required',
        'password' => 'required|min:6',
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function login()
    {
        $this->validate();

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password]) || Auth::attempt(['phone' => $this->email, 'password' => $this->password])) {
            session()->flash('message', 'Logged in successfully.');
            $user = Auth::user();
            // إرسال رسالة واتساب عند تسجيل الدخول
            // if ($user && $user->phone) {
            //     $sender = \App\Models\WhatsappSender::first();
            //     if ($sender) {
            //         sendWhatsAppMessage($user->phone, 'تم تسجيل الدخول بنجاح إلى حسابك في Endak.', $sender->number, $sender->token, $sender->instance_id);
            //     }
            // }
            if ($user->role_name == 'admin') {
                return redirect()->intended('admin/dashboard');
            } else {
                return redirect()->intended('/');
            }
        } else {
            session()->flash('error', 'Invalid credentials.');
        }
    }

    public function render()
    {
        return view('livewire.login');
    }
}
