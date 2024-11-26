<?php

namespace App\Http\Controllers;

use App\Models\Cleaning;
use App\Models\GeneralImage;
use Illuminate\Http\Request;
use App\Models\CleaningService;
use Illuminate\Support\Facades\Storage;

class CleaningController extends Controller
{
    public function index(){
        $cleaning = Cleaning::first();
        return view('admin.main_department.cleaning.index' , compact('cleaning'));
    }
    public function edit($id){
        $main = Cleaning::find($id);
        return view('admin.main_department.cleaning.edit' , compact('main'));
    }

    public function update(Request $request , $id){
        $main = Cleaning::find($id);
        $old_image = $main->image;

        $path = uploadImage( $request , 'cleaning' , 'image');
        $main->update([
            'name_ar'               => $request->name_ar,
            'name_en'               => $request->name_en,
            'image'                 => $path,
        ]);
        if ($old_image && $path) {
            Storage::disk('public')->delete($old_image);
        }
        return redirect()->route('admin.cleaning')->with('success' , 'تم التحديث بنجاح');
    }

    public function show(){
        $user = auth()->user();
        $main = Cleaning::first();
        $services = CleaningService::paginate();
        if(isset($user) && $user->role_id == 1){
            return view('admin.main_department.cleaning.show' , compact(  'main' , 'services'));
        }elseif(isset($user) && $user->role_id == 3){
            $main = Cleaning::first();
            $services = CleaningService::paginate();
            return view('admin.main_department.cleaning.show' , compact(  'main' , 'services'));
        }else{
            return view('admin.main_department.cleaning.show' , compact( 'main' , 'services'));

        }
    }

    public function store_service(Request $request)
    {
        $data = $request->except('_token', 'images');
    
        $is_created = CleaningService::create($data);
    
        if ($is_created) {
            if ($request->hasFile('images')) {
                $files = $request->file('images');
    
                if (!is_array($files)) {
                    $files = [$files];
                }
    
                foreach ($files as $file) {
                    $path = $file->store('cleaning', [
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
        $service = CleaningService::find($id);
        $main = Cleaning::first();
        return view('admin.main_department.cleaning.show_myservice' , compact( 'main' , 'service'));
    }
}
