<?php

namespace App\Http\Controllers\departments\Aircondition;

use App\Http\Controllers\Controller;
use App\Models\AirCondition;
use App\Models\AirConditionService;
use App\Models\GeneralImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AirconController extends Controller

    {
        public function index(){
            $air_con = AirCondition::first();
            return view('admin.main_department.air_con.index' , compact('air_con'));
        }
        public function edit($id){
            $main = AirCondition::find($id);
            return view('admin.main_department.air_con.edit' , compact('main'));
        }

        public function update(Request $request , $id){
            $main = AirCondition::find($id);
            $old_image = $main->image;

            $path = uploadImage( $request , 'air_con' , 'image');
            $main->update([
                'name_ar'               => $request->name_ar,
                'name_en'               => $request->name_en,
                'image'                 => $path,
            ]);
            if ($old_image && $path) {
                Storage::disk('public')->delete($old_image);
            }
            return redirect()->route('admin.spare_part')->with('success' , 'تم التحديث بنجاح');
        }

        public function show(){
            $user = auth()->user();
            $main = AirCondition::first();
            $services = AirConditionService::paginate();
            if(isset($user) && $user->role_id == 1){
                return view('admin.main_department.air_con.show' , compact(  'main' , 'services'));
            }elseif(isset($user) && $user->role_id == 3){
                $main = AirCondition::first();
                $services = AirConditionService::paginate();
                return view('admin.main_department.air_con.show' , compact(  'main' , 'services'));
            }else{
                return view('admin.main_department.air_con.show' , compact( 'main' , 'services'));

            }
        }

        public function store_service(Request $request)
        {
            // $data = $request->except('_token', 'images');

        $data = $request->validate([
            'user_id'       => 'required|exists:users,id',
            'split'         => 'nullable|boolean',
            'window'        => 'nullable|boolean',
            'clean'         => 'nullable|boolean',
            'feryoun'       => 'nullable|boolean',
            'maintance'     => 'nullable|boolean',
            'model'         => 'required|string|max:255',
            'quantity'      => 'required|integer|min:1',
            'city'          => 'required|string|max:255',
            'neighborhood'  => 'required|string|max:255',
            'time'          => 'nullable|date_format:H:i',
            'date'          => 'nullable|date',
            'notes'         => 'nullable|string',

        ]);
        // dd($data);
            $is_created = AirConditionService::create($data);


            if ($is_created) {
                if ($request->hasFile('images')) {
                    $files = $request->file('images');

                    if (!is_array($files)) {
                        $files = [$files];
                    }

                    foreach ($files as $file) {
                        $path = $file->store('air_con', [
                            'disk' => 'public',
                        ]);
                            $image = new GeneralImage([
                            'path' => $path,
                        ]);
                        $is_created->images()->save($image);
                    }
                }
            }


            return redirect()->route('home')->with('success' , 'تم اضافة الطلب بنجاح');

        }
        public function show_my_service($id){
            $user = auth()->user();
            $service = AirConditionService::find($id);

            if (!$service) {
                return redirect()->back()->with('error', 'الخدمة غير موجودة');
            }
            $main = AirCondition::first();
            return view('admin.main_department.air_con.show_myservice' , compact( 'main' , 'service'));
        }
}
