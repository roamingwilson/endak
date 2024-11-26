<?php

namespace App\Http\Controllers\departments;

use App\Models\Water;
use App\Models\GeneralImage;
use App\Models\WaterService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class WaterController extends Controller
{
    public function index(){
        $water = Water::first();
        return view('admin.main_department.water.index' , compact('water'));
    }
    public function edit($id){
        $main = Water::find($id);
        return view('admin.main_department.water.edit' , compact('main'));
    }

    public function update(Request $request , $id){
        $main = Water::find($id);
        $old_image = $main->image;

        $path = uploadImage( $request , 'water' , 'image');
        $main->update([
            'name_ar'               => $request->name_ar,
            'name_en'               => $request->name_en,
            'image'                 => $path,
        ]);
        if ($old_image && $path) {
            Storage::disk('public')->delete($old_image);
        }
        return redirect()->route('admin.water')->with('success' , 'تم التحديث بنجاح');
    }

    public function show(){
        $user = auth()->user();
        $main = Water::first();
        $services = WaterService::paginate();
        if(isset($user) && $user->role_id == 1){
            return view('admin.main_department.water.show' , compact(  'main' , 'services'));
        }elseif(isset($user) && $user->role_id == 3){
            $main = Water::first();
            $services = WaterService::paginate();
            return view('admin.main_department.water.show' , compact(  'main' , 'services'));
        }else{
            return view('admin.main_department.water.show' , compact( 'main' , 'services'));

        }
    }

    public function store_service(Request $request)
    {
        $data = $request->except('_token', 'images');
    
        $is_created = WaterService::create($data);
    
        if ($is_created) {
            if ($request->hasFile('images')) {
                $files = $request->file('images');
    
                if (!is_array($files)) {
                    $files = [$files];
                }
    
                foreach ($files as $file) {
                    $path = $file->store('water', [
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
        $service = WaterService::find($id);
        $main = Water::first();
        return view('admin.main_department.water.show_myservice' , compact( 'main' , 'service'));
    }
}
