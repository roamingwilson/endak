<?php

namespace App\Http\Controllers\departments;

use App\Models\Family;
use Illuminate\Http\Request;
use App\Models\FamilyService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class FamilyController extends Controller
{
    public function index(){
        $family = Family::first();
        return view('admin.main_department.family.index' , compact('family'));
    }
    public function edit($id){
        $main = Family::find($id);
        return view('admin.main_department.family.edit' , compact('main'));
    }

    public function update(Request $request , $id){
        $main = Family::find($id);
        $old_image = $main->image;

        $path = uploadImage( $request , 'family' , 'image');
        $main->update([
            'name_ar'               => $request->name_ar,
            'name_en'               => $request->name_en,
            'image'                 => $path,
        ]);
        if ($old_image && $path) {
            Storage::disk('public')->delete($old_image);
        }
        return redirect()->route('admin.family')->with('success' , 'تم التحديث بنجاح');
    }

    public function show(){
        $user = auth()->user();
        $main = Family::first();
        $services = FamilyService::paginate();
        if(isset($user) && $user->role_id == 1){
            return view('admin.main_department.family.show' , compact(  'main' , 'services'));
        }elseif(isset($user) && $user->role_id == 3){
            $main = Family::first();
            $services = FamilyService::paginate();
            return view('admin.main_department.family.show' , compact(  'main' , 'services'));
        }else{
            return view('admin.main_department.family.show' , compact( 'main' , 'services'));

        }
    }

    public function store_service(Request $request)
    {
        $data = $request->except('_token', 'images');
    
        $is_created = FamilyService::create($data);
    
        if ($is_created) {
            if ($request->hasFile('images')) {
                $files = $request->file('images');
    
                if (!is_array($files)) {
                    $files = [$files];
                }
    
                foreach ($files as $file) {
                    $path = $file->store('family', [
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
        $service = FamilyService::find($id);
        $main = Family::first();
        return view('admin.main_department.family.show_myservice' , compact( 'main' , 'service'));
    }
}
