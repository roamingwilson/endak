<?php

namespace App\Http\Controllers\departments;

use App\Models\Ads;
use App\Models\AdsService;
use App\Models\GeneralImage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class AdsController extends Controller
{
    public function index(){
        $ads = Ads::first();
        return view('admin.main_department.ads.index' , compact('ads'));
    }
    public function edit($id){
        $main = Ads::find($id);
        return view('admin.main_department.ads.edit' , compact('main'));
    }

    public function update(Request $request , $id){
        $main = Ads::find($id);
        $old_image = $main->image;

        $path = uploadImage( $request , 'ads' , 'image');
        $main->update([
            'name_ar'               => $request->name_ar,
            'name_en'               => $request->name_en,
            'image'                 => $path,
        ]);
        if ($old_image && $path) {
            Storage::disk('public')->delete($old_image);
        }
        return redirect()->route('admin.ads')->with('success' , 'تم التحديث بنجاح');
    }

    public function show(){
        $user = auth()->user();
        $main = Ads::first();
        $services = AdsService::paginate();
        if(isset($user) && $user->role_id == 1){
            return view('admin.main_department.ads.show' , compact(  'main' , 'services'));
        }elseif(isset($user) && $user->role_id == 3){
            $main = Ads::first();
            $services = AdsService::paginate();
            return view('admin.main_department.ads.show' , compact(  'main' , 'services'));
        }else{
            return view('admin.main_department.ads.show' , compact( 'main' , 'services'));

        }
    }

    public function store_service(Request $request)
    {
        $data = $request->except('_token', 'images');
    
        $is_created = AdsService::create($data);
    
        if ($is_created) {
            if ($request->hasFile('images')) {
                $files = $request->file('images');
    
                if (!is_array($files)) {
                    $files = [$files];
                }
    
                foreach ($files as $file) {
                    $path = $file->store('ads', [
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
        $service = AdsService::find($id);
        $main = Ads::first();
        return view('admin.main_department.ads.show_myservice' , compact( 'main' , 'service'));
    }
}
