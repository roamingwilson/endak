<?php

namespace App\Http\Controllers\departments;

use App\Models\CarWater;
use App\Models\GeneralImage;
use Illuminate\Http\Request;
use App\Models\CarWaterService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class CarWaterController extends Controller
{
    
    public function index(){
        $car_water = CarWater::first();
        return view('admin.main_department.car_water.index' , compact('car_water'));
    }
    public function edit($id){
        $main = CarWater::find($id);
        return view('admin.main_department.car_water.edit' , compact('main'));
    }

    public function update(Request $request , $id){
        $main = CarWater::find($id);
        $old_image = $main->image;

        $path = uploadImage( $request , 'car_water' , 'image');
        $main->update([
            'name_ar'               => $request->name_ar,
            'name_en'               => $request->name_en,
            'image'                 => $path,
        ]);
        if ($old_image && $path) {
            Storage::disk('public')->delete($old_image);
        }
        return redirect()->route('admin.car_water')->with('success' , 'تم التحديث بنجاح');
    }

    public function show(){
        $user = auth()->user();
        $main = CarWater::first();
        $services = CarWaterService::paginate();
        if(isset($user) && $user->role_id == 1){
            return view('admin.main_department.car_water.show' , compact(  'main' , 'services'));
        }elseif(isset($user) && $user->role_id == 3){
            $main = CarWater::first();
            $services = CarWaterService::paginate();
            return view('admin.main_department.car_water.show' , compact(  'main' , 'services'));
        }else{
            return view('admin.main_department.car_water.show' , compact( 'main' , 'services'));

        }
    }

    public function store_service(Request $request)
    {
        $data = $request->except('_token', 'images');
    
        $is_created = CarWaterService::create($data);
    
        if ($is_created) {
            if ($request->hasFile('images')) {
                $files = $request->file('images');
    
                if (!is_array($files)) {
                    $files = [$files];
                }
    
                foreach ($files as $file) {
                    $path = $file->store('car_water', [
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
        $service = CarWaterService::find($id);
        $main = CarWater::first();
        return view('admin.main_department.car_water.show_myservice' , compact( 'main' , 'service'));
    }
}
