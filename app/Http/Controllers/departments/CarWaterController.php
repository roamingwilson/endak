<?php

namespace App\Http\Controllers\departments;

use App\Models\CarWater;
use App\Models\GeneralImage;
use Illuminate\Http\Request;
use App\Models\CarWaterService;
use App\Http\Controllers\Controller;
use App\Notifications\CommentNotification;
use Illuminate\Support\Facades\Storage;

class CarWaterController extends Controller
{

    public function index(){
        $car_water = CarWater::first();
        return view('admin.main_department.car_water.index' , compact('car_water'));
    }
    public function edit($id){
        $main = CarWater::find($id);
        return view('admin.main_department.car_water.edit' , compact('main'));
    }

    public function update(Request $request , $id){
        $main = CarWater::find($id);
        $old_image = $main->image;

        $path = uploadImage( $request , 'car_water' , 'image');
        $main->update([
            'name_ar'               => $request->name_ar,
            'name_en'               => $request->name_en,
            'image'                 => $path,
        ]);
        if ($old_image && $path) {
            Storage::disk('public')->delete($old_image);
        }
        return redirect()->route('admin.car_water')->with('success' , 'تم التحديث بنجاح');
    }

    public function show(){
        $user = auth()->user();
        $main = CarWater::first();
        $services = CarWaterService::paginate();
        if(isset($user) && $user->role_id == 1){
            return view('admin.main_department.car_water.show' , compact(  'main' , 'services'));
        }elseif(isset($user) && $user->role_id == 3){
            $main = CarWater::first();
            $services = CarWaterService::paginate();
            return view('admin.main_department.car_water.show' , compact(  'main' , 'services'));
        }else{
            return view('admin.main_department.car_water.show' , compact( 'main' , 'services'));

        }
    }

    public function store_service(Request $request)
    {
        $data = $request->except('_token', 'images');
        $data = $request->validate([

            'user_id'       => 'required|exists:users,id',
            'drink_width'   => 'nullable|in:18,32',
            'wall_width'    => 'nullable|in:18,32',

            'notes'         => 'nullable|string|max:1000',
            ]);


        $is_created = CarWaterService::create($data);

        if ($is_created) {
            if ($request->hasFile('images')) {
                $files = $request->file('images');

                if (!is_array($files)) {
                    $files = [$files];
                }

                foreach ($files as $file) {
                    $path = $file->store('car_water', [
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
        $service = CarWaterService::find($id);
        $main = CarWater::first();
        return view('admin.main_department.car_water.show_myservice' , compact( 'main' , 'service'));
    }

    public function edit_service($id)
    {

        $service = CarWaterService::findOrFail($id);
        $cars = CarWater::where('id', !0)->get();
        if (auth()->id() !== $service->user_id) {
            abort(403, 'Unauthorized action.');
        }
        $user = auth()->user();


        return view('admin.main_department.car_water.edit_service', compact('service', 'user', 'cars'));
    }
    public function update_service(Request $request, $id)
    {
        $data = $request->validate([

        'user_id'       => 'required|exists:users,id',
        'drink_width'   => 'nullable|in:18,32',
        'wall_width'    => 'nullable|in:18,32',

        'notes'         => 'nullable|string|max:1000',
        ]);


        try {
            $service = CarWaterService::findOrFail($id);
            if($service->comments == true)
            {
            $comments = $service->comments;

            foreach ($comments as $comment) {
                $provider = $comment->user;
                $customer = $comment->customer;

                $provider->notify(new CommentNotification([
                    'id' => $comment->id,
                    'title' => "قام $customer->fullname بتعديل أو حذف الخدمة",
                    'body' => "قدم عرض جديد",
                    'url' => route('notifications.index'),
                ]));

                $comment->delete(); // حذف التعليق هنا أيضاً
            }
        }
            // dd($service);


            // تحديث الحقول الأساسية
            $service->notes = $request->notes;
            $service->wall_width = $request->wall_width ?? 0 ;
            $service->drink_width = $request->drink_width ?? 0;


            $service->user_id = $request->user_id;

            $service->update();

            // $service->update($data);

            // تحديث الصور (اختياري)
            if ($request->hasFile('images')) {
                // احذف الصور القديمة لو تحب
                $service->images()->delete();

                foreach ((array) $request->file('images') as $file) {
                    $path = $file->store('family/' . $service->id, 'public');

                    $image = new GeneralImage([
                        'path' => $path,
                    ]);

                    $service->images()->save($image);
                }
            }

            return redirect()->route('home')->with('success', 'تم تحديث الطلب بنجاح');

        } catch (\Exception $e) {
            return back()->with('error', 'حدث خطأ أثناء التحديث: ' . $e->getMessage());
        }
    }
    public function destroy_service($id)
    {

        try {
            $service = CarWaterService::findOrFail($id);

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


