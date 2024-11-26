<?php

namespace App\Http\Controllers\departments;

use App\Models\BigCar;
use App\Models\GeneralImage;
use Illuminate\Http\Request;
use App\Models\BigCarService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class BigCarController extends Controller
{
    public function index(){
        $big_car = BigCar::first();
        return view('admin.main_department.big_car.index' , compact('big_car'));
    }
    public function edit($id){
        $main = BigCar::find($id);
        return view('admin.main_department.big_car.edit' , compact('main'));
    }

    public function update(Request $request , $id){
        $main = BigCar::find($id);
        $old_image = $main->image;

        $path = uploadImage( $request , 'big_car' , 'image');
        $main->update([
            'name_ar'               => $request->name_ar,
            'name_en'               => $request->name_en,
            'image'                 => $path,
        ]);
        if ($old_image && $path) {
            Storage::disk('public')->delete($old_image);
        }
        return redirect()->route('admin.big_car')->with('success' , 'تم التحديث بنجاح');
    }

    public function show(){
        $user = auth()->user();
        $main = BigCar::first();
        $services = BigCarService::paginate();
        if(isset($user) && $user->role_id == 1){
            return view('admin.main_department.big_car.show' , compact(  'main' , 'services'));
        }elseif(isset($user) && $user->role_id == 3){
            $main = BigCar::first();
            $services = BigCarService::paginate();
            return view('admin.main_department.big_car.show' , compact(  'main' , 'services'));
        }else{
            return view('admin.main_department.big_car.show' , compact( 'main' , 'services'));

        }
    }

    public function store_service(Request $request)
    {
        $data = $request->except('_token', 'images');
    
        $is_created = BigCarService::create($data);
    
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
        $service = BigCarService::find($id);
        $main = BigCar::first();
        return view('admin.main_department.big_car.show_myservice' , compact( 'main' , 'service'));
    }
}
