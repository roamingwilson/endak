<?php

namespace App\Http\Controllers;


use App\Models\Department;

use Illuminate\Http\Request;

use App\Models\UserDepartment;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Storage;

use App\Models\User;
use App\Models\GeneralOrder;

class ProfileController extends Controller
{
    public function show($id)
    {

        $user = User::findOrFail($id);
        $order = GeneralOrder::where('service_provider_id', $id);

        return view('front_office.profile.show', compact('user','order'));
    }
    public function edit($id)
    {
        $user = \App\Models\User::findOrFail($id);
        $main_departments = Department::with('sub_departments')->where('department_id', 0)->get();
        return view('front_office.profile.edit', compact('user', 'main_departments'));
    }
    public function users()
    {

        $users = User::where('role_id', 3)->paginate(6);

        return view('front_office.user.all_user', compact('users'));
    }
    public function updateProfile(Request $request)
                {    $user = auth()->user();
                $data = $request->except('image');

                if ($request->hasFile('image')) {
                    $new_image = uploadImage($request, "users", "image");
                    if ($new_image) {
                        $data['image'] = $new_image;
                    }
                }

              $user_update =  User::where('id', $user->id)->first();
        $user_update->update($data);

    if ($request->departments) {
        UserDepartment::where('user_id', $user->id)->delete();
        foreach ($request->departments as $item) {
            [$type, $id] = explode('-', $item);
            $commentable_type = $type === 'main'
                ? \App\Models\Department::class
                : \App\Models\SubDepartment::class;

            $main_department = new UserDepartment([
                'user_id'         => $user->id,
                'commentable_id'  => $id,
                'commentable_type'=> $commentable_type,
            ]);
            $main_department->save();
        }
    }

    return redirect()->route('web.profile', auth()->id())->with('success', 'تم التحديث بنجاح');
}
public function user_note(){
        return view('front_office.profile.userNote');
}
public function privcy(){
        return view('front_office.profile.Privcy');
}
public function FAQ(){
        return view('front_office.profile.FAQ');
}
public function terms(){
        return view('front_office.profile.Terms');
}

}
