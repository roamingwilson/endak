<?php

namespace App\Http\Controllers\departments;

use App\Models\Worker;
use App\Models\GeneralImage;
use Illuminate\Http\Request;
use App\Models\WorkerService;
use App\Http\Controllers\Controller;
use App\Notifications\CommentNotification;
use Illuminate\Support\Facades\Storage;

class WorkerController extends Controller
{
    public function index(){
        $worker = Worker::first();
        return view('admin.main_department.worker.index' , compact('worker'));
    }
    public function edit($id){
        $main = Worker::find($id);
        return view('admin.main_department.worker.edit' , compact('main'));
    }

    public function update(Request $request , $id){
        $main = Worker::find($id);
        $old_image = $main->image;

        $path = uploadImage( $request , 'worker' , 'image');
        $main->update([
            'name_ar'               => $request->name_ar,
            'name_en'               => $request->name_en,
            'image'                 => $path,
        ]);
        if ($old_image && $path) {
            Storage::disk('public')->delete($old_image);
        }
        return redirect()->route('admin.worker')->with('success' , 'تم التحديث بنجاح');
    }

    public function show(){
        $user = auth()->user();
        $main = Worker::first();
        $services = WorkerService::paginate();
        if(isset($user) && $user->role_id == 1){
            return view('admin.main_department.worker.show' , compact(  'main' , 'services'));
        }elseif(isset($user) && $user->role_id == 3){
            $main = Worker::first();
            $services = WorkerService::paginate();
            return view('admin.main_department.worker.show' , compact(  'main' , 'services'));
        }else{
            return view('admin.main_department.worker.show' , compact( 'main' , 'services'));

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
        $is_created = WorkerService::create($data);

        if ($is_created) {
            if ($request->hasFile('images')) {
                $files = $request->file('images');

                if (!is_array($files)) {
                    $files = [$files];
                }

                foreach ($files as $file) {
                    $path = $file->store('worker', [
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
        $service = WorkerService::find($id);
        $main = Worker::first();
        return view('admin.main_department.worker.show_myservice' , compact( 'main' , 'service'));
    }
    public function edit_service($id)
    {

        $service = WorkerService::findOrFail($id);
        $cars = Worker::where('id', !0)->get();
        if (auth()->id() !== $service->user_id) {
            abort(403, 'Unauthorized action.');
        }
        $user = auth()->user();


        return view('admin.main_department.worker.edit_service', compact('service', 'user', 'cars'));
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
            $service = WorkerService::findOrFail($id);
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
            $service = WorkerService::findOrFail($id);

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

