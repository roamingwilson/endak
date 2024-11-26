<?php

namespace App\Http\Controllers\departments;

use App\Models\Teacher;
use App\Models\GeneralImage;
use Illuminate\Http\Request;
use App\Models\TeacherService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class TeacherController extends Controller
{
    public function index(){
        $teacher = Teacher::first();
        return view('admin.main_department.teacher.index' , compact('teacher'));
    }
    public function edit($id){
        $main = Teacher::find($id);
        return view('admin.main_department.teacher.edit' , compact('main'));
    }

    public function update(Request $request , $id){
        $main = Teacher::find($id);
        $old_image = $main->image;

        $path = uploadImage( $request , 'teacher' , 'image');
        $main->update([
            'name_ar'               => $request->name_ar,
            'name_en'               => $request->name_en,
            'image'                 => $path,
        ]);
        if ($old_image && $path) {
            Storage::disk('public')->delete($old_image);
        }
        return redirect()->route('admin.teacher')->with('success' , 'تم التحديث بنجاح');
    }

    public function show(){
        $user = auth()->user();
        $main = Teacher::first();
        $services = TeacherService::paginate();
        if(isset($user) && $user->role_id == 1){
            return view('admin.main_department.teacher.show' , compact(  'main' , 'services'));
        }elseif(isset($user) && $user->role_id == 3){
            $main = Teacher::first();
            $services = TeacherService::paginate();
            return view('admin.main_department.teacher.show' , compact(  'main' , 'services'));
        }else{
            return view('admin.main_department.teacher.show' , compact( 'main' , 'services'));

        }
    }

    public function store_service(Request $request)
    {
        $data = $request->except('_token', 'images');
    
        $is_created = TeacherService::create($data);
    
        if ($is_created) {
            if ($request->hasFile('images')) {
                $files = $request->file('images');
    
                if (!is_array($files)) {
                    $files = [$files];
                }
    
                foreach ($files as $file) {
                    $path = $file->store('teacher', [
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
        $service = TeacherService::find($id);
        $main = Teacher::first();
        return view('admin.main_department.teacher.show_myservice' , compact( 'main' , 'service'));
    }
}
