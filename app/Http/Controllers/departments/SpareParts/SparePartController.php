<?php

namespace App\Http\Controllers\departments\SpareParts;

use App\Http\Controllers\Controller;
use App\Models\GeneralImage;
use App\Models\SpareParts;
use App\Models\SparePartServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class SparePartController extends Controller
{
    public function index(){
        $spare_part = SpareParts::where('spare_part_id',0)->first();
        return view('admin.main_department.spare_part.index' , compact('spare_part'));
    }
    public function edit($id){
        $main = SpareParts::find($id);
        return view('admin.main_department.spare_part.edit' , compact('main'));
    }

    public function update(Request $request , $id){
        $main = SpareParts::find($id);
        $old_image = $main->image;

        $path = uploadImage( $request , 'spare_part' , 'image');
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
    public function add_sub_department(){
        $spare_part = SpareParts::where('spare_part_id' , 0)->first();
        return view('admin.main_department.spare_part.create');
    }
    public function store_sub_department(Request $request  ){
        $spare_part = SpareParts::where('spare_part_id' , 0)->first();
            // dd($spare_part);

        $path = uploadImage( $request , 'spare_part' , 'image');
       $data= SpareParts::create([
            'name_ar'               => $request->name_ar,
            'name_en'               => $request->name_en,
            'image'                 => $path,
            'spare_part_id'        => $spare_part->id,
        ]);
            // dd($data);

        return redirect()->route('admin.spare_part')->with('success' , 'تم الاضافة بنجاح');
    }
    public function show_sub_departments_list(){
        $departments = SpareParts::where('spare_part_id', '!=' , 0)->paginate();

        return view('admin.main_department.spare_part.departments_list' , compact('departments'));
    }
    public function show_sub_department($id){
        $spare_part = SpareParts::find($id);
        return view('admin.main_department.spare_part.index' , compact('spare_part'));
    }
    public function delete($id)
    {
        $spare_part = SpareParts::where('spare_part_id', '!=', 0)->find($id);

        if ($spare_part) {
            $spare_part->delete();
            return to_route('admin.spare_part.show_sub_departments_list')->with('success', 'spare_part deleted successfully.');
        }

        return to_route('admin.spare_part.show_sub_departments_list')->with('error', 'spare_part not found.');
    }

    public function show(){
        $user = auth()->user();
        $main = SpareParts::where('spare_part_id',0)->first();
        $spare_parts = Cache::remember('spare_part', 60, function () {
            return SpareParts::where('spare_part_id', '!=',0)->paginate();
        });
        return view('admin.main_department.spare_part.front_show' , compact( 'main' , 'spare_parts'));
    }

    public function spare_part_sub_show($id){
        $user = auth()->user();
        $main = SpareParts::find($id);
        $services = SparePartServices::where('spare_part_id' ,$id)->paginate();

        return view('admin.main_department.spare_part.show_sub_sparepart' , compact( 'main','services'  ));
    }

    public function store_service(Request $request )
    {
        $data = $request->except('_token', 'images');
        // dd($data);
        $is_created = SparePartServices::create($data);

        if ($is_created) {
            if ($request->hasFile('images')) {
                $files = $request->file('images');

                if (!is_array($files)) {
                    $files = [$files];
                }

                foreach ($files as $file) {
                    $path = $file->store('spare_part', [
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
        $service = SparePartServices::find($id);
        $main = SpareParts::where('id',$service->spare_part_id)->first();
        // dd($main);
        return view('admin.main_department.spare_part.show_myservice' , compact( 'main' , 'service'));
    }
}
