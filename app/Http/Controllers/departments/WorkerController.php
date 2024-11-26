<?php

namespace App\Http\Controllers\departments;

use App\Models\Worker;
use App\Models\GeneralImage;
use Illuminate\Http\Request;
use App\Models\WorkerService;
use App\Http\Controllers\Controller;
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
    

        return redirect()->route('home')->with('success' , 'تم اضافة الطلب بنجاح');

    }
    public function show_my_service($id){
        $service = WorkerService::find($id);
        $main = Worker::first();
        return view('admin.main_department.worker.show_myservice' , compact( 'main' , 'service'));
    }
}
