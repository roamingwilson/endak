<?php

namespace App\Http\Controllers\departments\Aircondition;

use App\Http\Controllers\Controller;
use App\Models\AirCondition;
use App\Models\AirConditionService;
use App\Models\GeneralImage;
use App\Notifications\CommentNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AirconController extends Controller

    {
        public function index(){
            $air_con = AirCondition::first();
            return view('admin.main_department.air_con.index' , compact('air_con'));
        }
        public function edit($id){
            $main = AirCondition::find($id);
            return view('admin.main_department.air_con.edit' , compact('main'));
        }

        public function update(Request $request , $id){
            $main = AirCondition::find($id);
            $old_image = $main->image;

            $path = uploadImage( $request , 'air_con' , 'image');
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
            $main = AirCondition::first();
            $services = AirConditionService::paginate();
            if(isset($user) && $user->role_id == 1){
                return view('admin.main_department.air_con.show' , compact(  'main' , 'services'));
            }elseif(isset($user) && $user->role_id == 3){
                $main = AirCondition::first();
                $services = AirConditionService::paginate();
                return view('admin.main_department.air_con.show' , compact(  'main' , 'services'));
            }else{
                return view('admin.main_department.air_con.show' , compact( 'main' , 'services'));

            }
        }

        public function store_service(Request $request)
        {
            // $data = $request->except('_token', 'images');

        $data = $request->validate([
            'user_id'       => 'required|exists:users,id',
            'split'         => 'nullable|boolean',
            'window'        => 'nullable|boolean',
            'clean'         => 'nullable|boolean',
            'feryoun'       => 'nullable|boolean',
            'maintance'     => 'nullable|boolean',
            'model'         => 'required|string|max:255',
            'quantity'      => 'required|integer|min:1',
            'city'          => 'required|string|max:255',
            'neighborhood'  => 'required|string|max:255',
            'time'          => 'nullable|date_format:H:i',
            'date'          => 'nullable|date',
            'notes'         => 'nullable|string',

        ]);
        // dd($data);
            $is_created = AirConditionService::create($data);


            if ($is_created) {
                if ($request->hasFile('images')) {
                    $files = $request->file('images');

                    if (!is_array($files)) {
                        $files = [$files];
                    }

                    foreach ($files as $file) {
                        $path = $file->store('air_con', [
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
            $user = auth()->user();
            $service = AirConditionService::find($id);

            if (!$service) {
                return redirect()->back()->with('error', 'الخدمة غير موجودة');
            }
            $main = AirCondition::first();
            return view('admin.main_department.air_con.show_myservice' , compact( 'main' , 'service'));
        }
        public function edit_service($id){

            $service=AirConditionService::findOrFail($id);
            $cars= AirCondition::where('id',!0)->get();
            if (auth()->id() !== $service->user_id) {
                abort(403, 'Unauthorized action.');
            }
            $user = auth()->user();


            return view('admin.main_department.air_con.edit_service',compact('service','user','cars'));
        }
        public function update_service(Request $request,$id){
            $data = $request->validate([

               'user_id'       => 'required|exists:users,id',
            'split'         => 'nullable|boolean',
            'window'        => 'nullable|boolean',
            'clean'         => 'nullable|boolean',
            'feryoun'       => 'nullable|boolean',
            'maintance'     => 'nullable|boolean',
            'model'         => 'required|string|max:255',
            'quantity'      => 'required|integer|min:1',
            'city'          => 'required|string|max:255',
            'neighborhood'  => 'required|string|max:255',
            'time'          => 'nullable|date_format:H:i',
            'date'          => 'nullable|date',
            'notes'         => 'nullable|string',
            ]);

            try {
                $service = AirConditionService::findOrFail($id);
                // dd($service);

                $service->update($data);
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

                // تحديث الصور (اختياري)
                if ($request->hasFile('images')) {
                    // احذف الصور القديمة لو تحب
                    $service->images()->delete();

                    foreach ((array) $request->file('images') as $file) {
                        $path = $file->store('air_con/' . $service->id, 'public');

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
            $service = AirConditionService::findOrFail($id);

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
