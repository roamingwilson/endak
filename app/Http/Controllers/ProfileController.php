<?php

namespace App\Http\Controllers;

use App\Models\Ads;
use App\Models\User;
use App\Models\Water;
use App\Models\BigCar;
use App\Models\Family;
use App\Models\Garden;
use App\Models\Worker;
use App\Models\Teacher;
use App\Models\CarWater;
use App\Models\Cleaning;
use App\Models\PublicGe;
use App\Models\Department;
use App\Models\Contracting;
use App\Models\FollowCamera;
use Illuminate\Http\Request;
use App\Models\CounterInsects;
use App\Models\UserDepartment;
use App\Models\PartyPreparation;
use App\Http\Controllers\Controller;
use App\Models\AirCondition;
use App\Models\FurnitureTransportation;
use App\Models\HeavyEquipment;
use App\Models\Maintenance;
use App\Models\SpareParts;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show($id)
    {

        $user = User::findOrFail($id);

        return view('front_office.profile.show', compact('user'));
    }
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $otherOrder1 = FurnitureTransportation::first();
        $other2 = FollowCamera::first();
        $other3 = PartyPreparation::first();
        $other4 = Garden::first();
        $other5 = CounterInsects::first();
        $other6 = Cleaning::first();
        $other7 = Teacher::first();
        $other8 = Family::first();
        $other9 = Worker::first();
        $other10 = PublicGe::first();
        $other11 = Ads::first();
        $other12 = Water::first();
        $other13 = CarWater::first();
        $other14 = BigCar::first();
        $other15 = Contracting::first();
        $other16 = Maintenance::first();
        $other17 = HeavyEquipment::first();
        $other18 = SpareParts::first();
        $other19 = AirCondition::first();

        $all_departments = Department::get();


        $merged_departments = $all_departments;

        $others = [
            $otherOrder1,
            $other2,
            $other3,
            $other4,
            $other5,
            $other6,
            $other7,
            $other8,
            $other9,
            $other10,
            $other11,
            $other12,
            $other13,
            $other14,
            $other15,
            $other16,
            $other17,
            $other18,
            $other19,
        ];

        foreach ($others as $item) {
            if ($item) {
                $merged_departments = $merged_departments->concat(collect([$item]));
            }
        }
        return view('front_office.profile.edit', compact('user', 'merged_departments'));
    }
    public function users()
    {

        $users = User::where('role_id', 3)->paginate(6);

        return view('front_office.user.all_user', compact('users'));
    }
    public function update(Request $request)
    {

        $user = auth()->user();
        $data = $request->except('image');
        $new_image = uploadImage($request, "users", "image");
        $old_image = $user->image;
        if ($new_image) {
            $data['image'] = $new_image;
        }
        $user_update = User::where('id', $user->id)->first();

        if ($request->departments) {
            UserDepartment::where('user_id', $user->id)->delete();
            foreach ($request->departments as $item) {
                [$name, $id] = explode('-', $item);
                $modelParts = explode(' ', $name);
                $modelName = implode('', array_map('ucfirst', $modelParts));

                $modelClass = "App\\Models\\$modelName";

                $main_department = new UserDepartment([
                    'user_id'         => $user->id,
                    'commentable_id'  => $id,
                ]);

                if (class_exists($modelClass)) {
                    $modelInstance = $modelClass::find($id);
                    if ($modelInstance && method_exists($modelInstance, 'departments')) {
                        $modelInstance->departments()->save($main_department);
                    }
                } else {
                    $department = Department::find($id);
                    if ($department && method_exists($department, 'departments')) {
                        $department->departments()->save($main_department);
                    }
                }
            }
        }
        return redirect()->back()->with('success', 'تم التحديث بنجاح');
    }
}
