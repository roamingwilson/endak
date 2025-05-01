<?php

namespace App\Http\Controllers\departments;

use App\Models\BigCar;
use App\Models\GeneralImage;
use Illuminate\Http\Request;
use App\Models\BigCarService;
use App\Http\Controllers\Controller;
use App\Notifications\CommentNotification;
use Illuminate\Support\Facades\Storage;

class BigCarController extends Controller
{
    public function index(){
        $big_car = BigCar::first();
        return view('admin.main_department.big_car.index' , compact('big_car'));
    }
    public function edit($id){
        $main = BigCar::find($id);
        return view('admin.main_department.big_car.edit' , compact('main'));
    }

    public function update(Request $request , $id){
        $main = BigCar::find($id);
        $old_image = $main->image;

        $path = uploadImage( $request , 'big_car' , 'image');
        $main->update([
            'name_ar'               => $request->name_ar,
            'name_en'               => $request->name_en,
            'image'                 => $path,
        ]);
        if ($old_image && $path) {
            Storage::disk('public')->delete($old_image);
        }
        return redirect()->route('admin.big_car')->with('success' , 'تم التحديث بنجاح');
    }

    public function show(){
        $user = auth()->user();
        $main = BigCar::first();
        $services = BigCarService::paginate();
        if(isset($user) && $user->role_id == 1){
            return view('admin.main_department.big_car.show' , compact(  'main' , 'services'));
        }elseif(isset($user) && $user->role_id == 3){
            $main = BigCar::first();
            $services = BigCarService::paginate();
            return view('admin.main_department.big_car.show' , compact(  'main' , 'services'));
        }else{
            return view('admin.main_department.big_car.show' , compact( 'main' , 'services'));

        }
    }

    public function store_service(Request $request)
    {
        $data = $request->except('_token', 'images');
        $data = $request->validate([
            'location'      => 'required|string|max:255',
            'destination'   => 'required|string|max:255',
            'car_type'      => 'required|string|max:255',
            'notes'         => 'nullable|string|max:1000',

            'user_id'       => 'required|exists:users,id',
        ]);

        $is_created = BigCarService::create($data);

        if ($is_created) {
            if ($request->hasFile('images')) {
                $files = $request->file('images');

                if (!is_array($files)) {
                    $files = [$files];
                }

                foreach ($files as $file) {
                    $path = $file->store('big_car', [
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
        $service = BigCarService::find($id);
        $main = BigCar::first();
        return view('admin.main_department.big_car.show_myservice' , compact( 'main' , 'service'));
    }
    public function edit_service($id)
    {

        $service = BigCarService::findOrFail($id);
        $cars = BigCar::where('id', !0)->get();
        if (auth()->id() !== $service->user_id) {
            abort(403, 'Unauthorized action.');
        }
        $user = auth()->user();


        return view('admin.main_department.big_car.edit_service', compact('service', 'user', 'cars'));
    }
    public function update_service(Request $request, $id)
    {
        $data = $request->validate([
            'location'      => 'required|string|max:255',
            'destination'   => 'required|string|max:255',
            'car_type'      => 'required|string|max:255',
            'notes'         => 'nullable|string|max:1000',

            'user_id'       => 'required|exists:users,id',
        ]);


        try {
            $service = BigCarService::findOrFail($id);
            // dd($service);

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
            // تحديث الحقول الأساسية
            $service->location = $request->location;
            $service->destination = $request->destination;
            $service->car_type = $request->car_type;
            $service->notes = $request->notes;
            $service->user_id = $request->user_id;

            $service->update();

            // $service->update($data);

            // تحديث الصور (اختياري)
            if ($request->hasFile('images')) {
                // احذف الصور القديمة لو تحب
                $service->images()->delete();

                foreach ((array) $request->file('images') as $file) {
                    $path = $file->store('big_car/' . $service->id, 'public');

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
            $service = BigCarService::findOrFail($id);

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



