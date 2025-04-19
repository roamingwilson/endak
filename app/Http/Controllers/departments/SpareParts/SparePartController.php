<?php

namespace App\Http\Controllers\departments\SpareParts;

use App\Http\Controllers\Controller;
use App\Models\GeneralImage;
use App\Models\SpareParts;
use App\Models\SparePartServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SparePartController extends Controller
{
    public function index(){
        $spare_part = SpareParts::first();
        return view('admin.main_department.spare_part.index' , compact('spare_part'));
    }
    public function edit($id){
        $main = SpareParts::find($id);
        return view('admin.main_department.spare_part.edit' , compact('main'));
    }

    public function update(Request $request , $id){
        $main = SpareParts::find($id);
        $old_image = $main->image;

        $path = uploadImage( $request , 'spare_part' , 'image');
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
        $main = SpareParts::first();
        $services = SparePartServices::paginate();
        if(isset($user) && $user->role_id == 1){
            return view('admin.main_department.spare_part.show' , compact(  'main' , 'services'));
        }elseif(isset($user) && $user->role_id == 3){
            $main = SpareParts::first();
            $services = SparePartServices::paginate();
            return view('admin.main_department.spare_part.show' , compact(  'main' , 'services'));
        }else{
            return view('admin.main_department.spare_part.show' , compact( 'main' , 'services'));

        }
    }

    public function store_service(Request $request)
    {
        $data = $request->except('_token', 'images');

        $is_created = SparePartServices::create($data);


        if ($is_created) {
            if ($request->hasFile('images')) {
                $files = $request->file('images');

                if (!is_array($files)) {
                    $files = [$files];
                }

                foreach ($files as $file) {
                    $path = $file->store('spare_part', [
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
        $service = SparePartServices::find($id);
        $main = SpareParts::first();
        return view('admin.main_department.spare_part.show_myservice' , compact( 'main' , 'service'));
    }
}
