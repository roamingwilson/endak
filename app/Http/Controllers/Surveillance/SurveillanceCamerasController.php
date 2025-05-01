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
        $data= $request->validate([
            'finger'          => 'nullable|boolean',
            'camera'          => 'nullable|boolean',
            'smart'           => 'nullable|boolean',
            'fire_system'     => 'nullable|boolean',
            'security_system' => 'nullable|boolean',
            'network'        => 'nullable|boolean',
            'notes'           => 'nullable|string',
            'user_id'         => 'required|exists:users,id', // Ensure user exists
        ]);

        $is_created = FollowCameraService::create($data);

        return redirect()->route('home')->with('success' , 'تم اضافة الطلب بنجاح');

    }

    public function show_my_service($id){
        $service = FollowCameraService::find($id);
        $main = FollowCamera::first();
        return view('admin.surveillance_cameras.show_myservice' , compact( 'main' , 'service'));
    }
    public function edit_service($id){

        $service=FollowCameraService::findOrFail($id);
        $cars= FollowCamera::where('id',!0)->get();
        if (auth()->id() !== $service->user_id) {
            abort(403, 'Unauthorized action.');
        }
        $user = auth()->user();


        return view('admin.surveillance_cameras.edit_service',compact('service','user','cars'));
    }
    public function update_service(Request $request,$id){
        $data = $request->validate([


                'finger'          => 'nullable|boolean',
                'camera'          => 'nullable|boolean',
                'smart'           => 'nullable|boolean',
                'fire_system'     => 'nullable|boolean',
                'security_system' => 'nullable|boolean',
                'network'        => 'nullable|boolean',
                'notes'           => 'nullable|string',
                'user_id'         => 'required|exists:users,id', // Ensure user exists
            ]);


        try {
            $service = FollowCameraService::findOrFail($id);
            // dd($service);
            $service->update([
                'finger'          => $request->has('finger') ? 1 : 0,
                'camera'          => $request->has('camera') ? 1 : 0,
                'smart'           => $request->has('smart') ? 1 : 0,
                'fire_system'     => $request->has('fire_system') ? 1 : 0,
                'security_system' => $request->has('security_system') ? 1 : 0,
                'network'        => $request->has('network') ? 1 : 0,
                'notes'           => $request->notes, // No need to sanitize, Laravel does it by default
                'user_id'         => $request->user_id,
            ]);



            return redirect()->route('home')->with('success', 'تم تحديث الطلب بنجاح');

        } catch (\Exception $e) {
            return back()->with('error', 'حدث خطأ أثناء التحديث: ' . $e->getMessage());
        }
    }
    public function destroy_service($id)
    {

    try {
        $service = FollowCameraService::findOrFail($id);

        if (auth()->id() !== $service->user_id) {
            abort(403, 'Unauthorized action.');
        }

        $service->delete();

        return redirect()->route('home')->with('success', 'تم حذف الطلب بنجاح');
    } catch (\Exception $e) {
        return redirect()->route('home')->with('error', 'حدث خطأ أثناء الحذف: ' . $e->getMessage());
    }
    }
}
