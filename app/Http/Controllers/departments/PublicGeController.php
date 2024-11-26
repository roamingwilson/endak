<?php

namespace App\Http\Controllers\departments;

use App\Models\PublicGe;
use App\Models\GeneralImage;
use Illuminate\Http\Request;
use App\Models\PublicGeService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class PublicGeController extends Controller
{
    public function index(){
        $public_ge = PublicGe::first();
        return view('admin.main_department.public_ge.index' , compact('public_ge'));
    }
    public function edit($id){
        $main = PublicGe::find($id);
        return view('admin.main_department.public_ge.edit' , compact('main'));
    }

    public function update(Request $request , $id){
        $main = PublicGe::find($id);
        $old_image = $main->image;

        $path = uploadImage( $request , 'public_ge' , 'image');
        $main->update([
            'name_ar'               => $request->name_ar,
            'name_en'               => $request->name_en,
            'image'                 => $path,
        ]);
        if ($old_image && $path) {
            Storage::disk('public')->delete($old_image);
        }
        return redirect()->route('admin.public_ge')->with('success' , 'تم التحديث بنجاح');
    }

    public function show(){
        $user = auth()->user();
        $main = PublicGe::first();
        $services = PublicGeService::paginate();
        if(isset($user) && $user->role_id == 1){
            return view('admin.main_department.public_ge.show' , compact(  'main' , 'services'));
        }elseif(isset($user) && $user->role_id == 3){
            $main = PublicGe::first();
            $services = PublicGeService::paginate();
            return view('admin.main_department.public_ge.show' , compact(  'main' , 'services'));
        }else{
            return view('admin.main_department.public_ge.show' , compact( 'main' , 'services'));

        }
    }

    public function store_service(Request $request)
    {
        $data = $request->except('_token', 'images');
    
        $is_created = PublicGeService::create($data);
    
        if ($is_created) {
            if ($request->hasFile('images')) {
                $files = $request->file('images');
    
                if (!is_array($files)) {
                    $files = [$files];
                }
    
                foreach ($files as $file) {
                    $path = $file->store('public_ge', [
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
        $service = PublicGeService::find($id);
        $main = PublicGe::first();
        return view('admin.main_department.public_ge.show_myservice' , compact( 'main' , 'service'));
    }
}
