<?php

namespace App\Http\Controllers\departments;

use App\Http\Controllers\Controller;
use App\Models\GeneralImage;
use App\Models\HeavyEquipment;
use App\Models\HeavyEquipmentservice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HeavyEquipmentController extends Controller
{
    public function index(){
        $heavy_Equip = HeavyEquipment::first();
        return view('admin.main_department.heavy_equipment.index' , compact('heavy_Equip'));
    }
    public function edit($id){
        $main = HeavyEquipment::find($id);
        return view('admin.main_department.heavy_equipment.edit' , compact('main'));
    }

    public function update(Request $request , $id){
        $main = HeavyEquipment::find($id);
        $old_image = $main->image;

        $path = uploadImage( $request , 'heavy_Equip' , 'image');
        $main->update([
            'name_ar'               => $request->name_ar,
            'name_en'               => $request->name_en,
            'image'                 => $path,
        ]);
        if ($old_image && $path) {
            Storage::disk('public')->delete($old_image);
        }
        return redirect()->route('admin.heavy_equip')->with('success' , 'تم التحديث بنجاح');
    }

    public function show(){
        $user = auth()->user();
        $main = HeavyEquipment::first();
        $services = HeavyEquipmentservice::paginate();
        if(isset($user) && $user->role_id == 1){
            return view('admin.main_department.heavy_equipment.show' , compact(  'main' , 'services'));
        }elseif(isset($user) && $user->role_id == 3){
            $main = HeavyEquipment::first();
            $services = HeavyEquipmentservice::paginate();
            return view('admin.main_department.heavy_equipment.show' , compact(  'main' , 'services'));
        }else{
            return view('admin.main_department.heavy_equipment.show' , compact( 'main' , 'services'));

        }
    }

    public function store_service(Request $request)
    {
        $data = $request->except('_token', 'images');

        $is_created = HeavyEquipmentservice::create($data);

        if ($is_created) {
            if ($request->hasFile('images')) {
                $files = $request->file('images');

                if (!is_array($files)) {
                    $files = [$files];
                }

                foreach ($files as $file) {
                    $path = $file->store('big_car', [
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
        $service = HeavyEquipmentservice::find($id);
        $main = HeavyEquipment::first();
        return view('admin.main_department.heavy_equipment.show_myservice' , compact( 'main' , 'service'));
    }
}
