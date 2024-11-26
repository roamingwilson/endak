<?php

namespace App\Http\Controllers\departments;

use App\Models\GeneralImage;
use Illuminate\Http\Request;
use App\Models\CounterInsects;
use App\Http\Controllers\Controller;
use App\Models\CounterInsectsService;
use Illuminate\Support\Facades\Storage;

class CounterInsectsController extends Controller
{
    
    public function index(){
        $counter_insects = CounterInsects::first();
        return view('admin.main_department.counter_insects.index' , compact('counter_insects'));
    }
    public function edit($id){
        $main = CounterInsects::find($id);
        return view('admin.main_department.counter_insects.edit' , compact('main'));
    }

    public function update(Request $request , $id){
        $main = CounterInsects::find($id);
        $old_image = $main->image;

        $path = uploadImage( $request , 'counter_insects' , 'image');
        $main->update([
            'name_ar'               => $request->name_ar,
            'name_en'               => $request->name_en,
            'image'                 => $path,
        ]);
        if ($old_image && $path) {
            Storage::disk('public')->delete($old_image);
        }
        return redirect()->route('admin.counter_insects')->with('success' , 'تم التحديث بنجاح');
    }

    public function show(){
        $user = auth()->user();
        $main = CounterInsects::first();
        $services = CounterInsectsService::paginate();
        if(isset($user) && $user->role_id == 1){
            return view('admin.main_department.counter_insects.show' , compact(  'main' , 'services'));
        }elseif(isset($user) && $user->role_id == 3){
            $main = CounterInsects::first();
            $services = CounterInsectsService::paginate();
            return view('admin.main_department.counter_insects.show' , compact(  'main' , 'services'));
        }else{
            return view('admin.main_department.counter_insects.show' , compact( 'main' , 'services'));

        }
    }

    public function store_service(Request $request)
    {
        $data = $request->except('_token', 'images');
    
        $is_created = CounterInsectsService::create($data);
    
        if ($is_created) {
            if ($request->hasFile('images')) {
                $files = $request->file('images');
    
                if (!is_array($files)) {
                    $files = [$files];
                }
    
                foreach ($files as $file) {
                    $path = $file->store('counter_insects', [
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
        $service = CounterInsectsService::find($id);
        $main = CounterInsects::first();
        return view('admin.main_department.counter_insects.show_myservice' , compact( 'main' , 'service'));
    }
}
