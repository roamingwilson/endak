<?php

namespace App\Http\Controllers\departments;

use App\Models\Water;
use App\Models\GeneralImage;
use App\Models\WaterService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Notifications\CommentNotification;
use Illuminate\Support\Facades\Storage;

class WaterController extends Controller
{
    public function index(){
        $water = Water::first();
        return view('admin.main_department.water.index' , compact('water'));
    }
    public function edit($id){
        $main = Water::find($id);
        return view('admin.main_department.water.edit' , compact('main'));
    }

    public function update(Request $request , $id){
        $main = Water::find($id);
        $old_image = $main->image;

        $path = uploadImage( $request , 'water' , 'image');
        $main->update([
            'name_ar'               => $request->name_ar,
            'name_en'               => $request->name_en,
            'image'                 => $path,
        ]);
        if ($old_image && $path) {
            Storage::disk('public')->delete($old_image);
        }
        return redirect()->route('admin.water')->with('success' , 'تم التحديث بنجاح');
    }

    public function show(){
        $user = auth()->user();
        $main = Water::first();
        $services = WaterService::paginate();
        if(isset($user) && $user->role_id == 1){
            return view('admin.main_department.water.show' , compact(  'main' , 'services'));
        }elseif(isset($user) && $user->role_id == 3){
            $main = Water::first();
            $services = WaterService::paginate();
            return view('admin.main_department.water.show' , compact(  'main' , 'services'));
        }else{
            return view('admin.main_department.water.show' , compact( 'main' , 'services'));

        }
    }

    public function store_service(Request $request)
    {
        $data = $request->except('_token', 'images');
        $data = $request->validate([

            'user_id'       => 'required|exists:users,id',


            'notes'         => 'nullable|string|max:1000',
            'city'          => 'required|string|max:100',
            'neighborhood'  => 'required|string|max:100',
            ]);

        $is_created = WaterService::create($data);

        if ($is_created) {
            if ($request->hasFile('images')) {
                $files = $request->file('images');

                if (!is_array($files)) {
                    $files = [$files];
                }

                foreach ($files as $file) {
                    $path = $file->store('water', [
                        'disk' => 'public',
                    ]);
                        $image = new GeneralImage([
                        'path' => $path,
                    ]);
                    $is_created->images()->save($image);
                }
            }
        }


        return redirect()->route('home')->with('success' , 'تم تقديم الخدمة بنجاح');

    }
    public function show_my_service($id){
        $service = WaterService::find($id);
        $main = Water::first();
        return view('admin.main_department.water.show_myservice' , compact( 'main' , 'service'));
    }
    public function edit_service($id)
    {

        $service = WaterService::findOrFail($id);
        $cars = Water::where('id', !0)->get();
        if (auth()->id() !== $service->user_id) {
            abort(403, 'Unauthorized action.');
        }
        $user = auth()->user();


        return view('admin.main_department.water.edit_service', compact('service', 'user', 'cars'));
    }
    public function update_service(Request $request, $id)
    {
        $data = $request->validate([

        'user_id'       => 'required|exists:users,id',


        'notes'         => 'nullable|string|max:1000',
        'city'          => 'required|string|max:100',
        'neighborhood'  => 'required|string|max:100',
        ]);


        try {
            $service = WaterService::findOrFail($id);
            // dd($service);


            // تحديث الحقول الأساسية
            $service->notes = $request->notes;

            $service->city = $request->city;

            $service->neighborhood = $request->neighborhood;

            $service->user_id = $request->user_id;

            $service->update();
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

            // $service->update($data);

            // تحديث الصور (اختياري)
            if ($request->hasFile('images')) {
                // احذف الصور القديمة لو تحب
                $service->images()->delete();

                foreach ((array) $request->file('images') as $file) {
                    $path = $file->store('water/' . $service->id, 'public');

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
            $service = WaterService::findOrFail($id);

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



