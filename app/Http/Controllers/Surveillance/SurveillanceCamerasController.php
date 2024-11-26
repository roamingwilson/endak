<?php

namespace App\Http\Controllers\Surveillance;

use App\Models\FollowCamera;
use App\Models\FollowCameraService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class SurveillanceCamerasController extends Controller
{
    public function index(){
        $main = FollowCamera::first();
        return view('admin.surveillance_cameras.index' , compact('main'));
    }
    public function edit($id){
        $main = FollowCamera::find($id);
        return view('admin.surveillance_cameras.edit' , compact('main'));
    }

    public function update(Request $request , $id){
        $main = FollowCamera::find($id);
        $old_image = $main->image;

        $path = uploadImage( $request , 'surveillance' , 'image');
        $main->update([
            'name_ar'               => $request->name_ar,
            'name_en'               => $request->name_en,
            'image'                 => $path,
        ]);
        if ($old_image && $path) {
            Storage::disk('public')->delete($old_image);
        }
        return redirect()->route('admin.surveillance')->with('success' , 'تم التحديث بنجاح');
    }

    public function show(){
        $user = auth()->user();
        $main = FollowCamera::first();
        $services = FollowCameraService::paginate();
        if(isset($user) && $user->role_id == 1){
            return view('admin.surveillance_cameras.show' , compact(  'main' , 'services'));
        }elseif(isset($user) && $user->role_id == 3){
            $main = FollowCamera::first();
            $services = FollowCameraService::paginate();
            return view('admin.surveillance_cameras.show' , compact(  'main' , 'services'));
        }else{
            return view('admin.surveillance_cameras.show' , compact( 'main' , 'services'));

        }
    }
    public function store_service(Request $request){
        $data = $request->except('_token');

        $is_created = FollowCameraService::create($data);

        return redirect()->route('home')->with('success' , 'تم اضافة الطلب بنجاح');

    }

    public function show_my_service($id){
        $service = FollowCameraService::find($id);
        $main = FollowCamera::first();
        return view('admin.surveillance_cameras.show_myservice' , compact( 'main' , 'service'));
    }
}
