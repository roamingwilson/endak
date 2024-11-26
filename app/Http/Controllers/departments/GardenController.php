<?php

namespace App\Http\Controllers\departments;

use App\Models\Garden;
use App\Models\GeneralImage;
use Illuminate\Http\Request;
use App\Models\GardenService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class GardenController extends Controller
{
    public function index(){
        $garden = Garden::first();
        return view('admin.main_department.garden.index' , compact('garden'));
    }
    public function edit($id){
        $main = Garden::find($id);
        return view('admin.main_department.garden.edit' , compact('main'));
    }

    public function update(Request $request , $id){
        $main = Garden::find($id);
        $old_image = $main->image;

        $path = uploadImage( $request , 'garden' , 'image');
        $main->update([
            'name_ar'               => $request->name_ar,
            'name_en'               => $request->name_en,
            'image'                 => $path,
        ]);
        if ($old_image && $path) {
            Storage::disk('public')->delete($old_image);
        }
        return redirect()->route('admin.garden')->with('success' , 'تم التحديث بنجاح');
    }

    public function show(){
        $user = auth()->user();
        $main = Garden::first();
        $services = GardenService::paginate();
        if(isset($user) && $user->role_id == 1){
            return view('admin.main_department.garden.show' , compact(  'main' , 'services'));
        }elseif(isset($user) && $user->role_id == 3){
            $main = Garden::first();
            $services = GardenService::paginate();
            return view('admin.main_department.garden.show' , compact(  'main' , 'services'));
        }else{
            return view('admin.main_department.garden.show' , compact( 'main' , 'services'));

        }
    }

    public function store_service(Request $request)
    {
        $data = $request->except('_token', 'images');
    
        $is_created = GardenService::create($data);
    
        if ($is_created) {
            if ($request->hasFile('images')) {
                $files = $request->file('images');
    
                if (!is_array($files)) {
                    $files = [$files];
                }
    
                foreach ($files as $file) {
                    $path = $file->store('garden', [
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
        $service = GardenService::find($id);
        $main = Garden::first();
        return view('admin.main_department.garden.show_myservice' , compact( 'main' , 'service'));
    }
}
