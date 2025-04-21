<?php

namespace App\Http\Controllers\departments;

use App\Http\Controllers\Controller;
use App\Models\GeneralImage;
use App\Models\HeavyEquipment;
use App\Models\HeavyEquipmentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class HeavyEquipmentController extends Controller
{
      public function index(){
    $heavy_equip = HeavyEquipment::where('heavy_equip_id',0)->first();
    return view('admin.main_department.heavy_equip.index' , compact('heavy_equip'));
}
public function edit($id){
    $main = HeavyEquipment::find($id);
    return view('admin.main_department.heavy_equip.edit' , compact('main'));
}

public function update(Request $request , $id){
    $main = HeavyEquipment::find($id);
    $old_image = $main->image;

    $path = uploadImage( $request , 'heavy_equip' , 'image');
    $main->update([
        'name_ar'               => $request->name_ar,
        'name_en'               => $request->name_en,
        'image'                 => $path,
    ]);
    if ($old_image && $path) {
        Storage::disk('public')->delete($old_image);
    }
    return redirect()->route('admin.heavy_equip')->with('success' , 'تم التحديث بنجاح');
}
public function add_sub_department(){
    $heavy_equip = HeavyEquipment::where('heavy_equip_id' , 0)->first();
    return view('admin.main_department.heavy_equip.create');
}
public function store_sub_department(Request $request  ){
    $heavy_equip = HeavyEquipment::where('heavy_equip_id' , 0)->first();
        // dd($heavy_equip);

    $path = uploadImage( $request , 'heavy_equip' , 'image');
   $data= HeavyEquipment::create([
        'name_ar'               => $request->name_ar,
        'name_en'               => $request->name_en,
        'image'                 => $path,
        'heavy_equip_id'        => $heavy_equip->id,
    ]);
        // dd($data);

    return redirect()->route('admin.heavy_equip')->with('success' , 'تم الاضافة بنجاح');
}
public function show_sub_departments_list(){
    $departments = HeavyEquipment::where('heavy_equip_id', '!=' , 0)->paginate();

    return view('admin.main_department.heavy_equip.departments_list' , compact('departments'));
}
public function show_sub_department($id){
    $heavy_equip = HeavyEquipment::find($id);
    return view('admin.main_department.heavy_equip.index' , compact('heavy_equip'));
}
public function delete($id)
{
    $heavy_equip = HeavyEquipment::where('heavy_equip_id', '!=', 0)->find($id);

    if ($heavy_equip) {
        $heavy_equip->delete();
        return to_route('admin.heavy_equip.show_sub_departments_list')->with('success', 'heavy_equip deleted successfully.');
    }

    return to_route('admin.heavy_equip.show_sub_departments_list')->with('error', 'heavy_equip not found.');
}

public function show(){
    $user = auth()->user();
    $main = HeavyEquipment::where('heavy_equip_id',0)->first();
    $heavy_equips = Cache::remember('heavy_equip', 60, function () {
        return HeavyEquipment::where('heavy_equip_id', '!=',0)->paginate();
    });
    return view('admin.main_department.heavy_equip.front_show' , compact( 'main' , 'heavy_equips'));
}

public function heavy_equip_sub_show($id){
    $user = auth()->user();
    $main = HeavyEquipment::find($id);
    $services = HeavyEquipmentService::where('heavy_equip_id' ,$id)->paginate();

    return view('admin.main_department.heavy_equip.show_sub_heavyequip' , compact( 'main','services'  ));
}

public function store_service(Request $request )
{

    $data = $request->validate([
        'heavy_equip_id' => 'required|exists:heavy_equipment,id',
        'location'       => 'required|string|max:255',
        'equip_type'     => 'nullable|string|max:255',
        'time'           => 'nullable|date_format:H:i',
        'user_id'        => 'required|exists:users,id',
        'notes'          => 'required|string',
    ]);
        // dd($data);

    // إنشاء الخدمة
    $is_created = HeavyEquipmentService::create($data);

    // رفع الصور وربطها بالخدمة إذا تم إنشاؤها
    if ($is_created && $request->hasFile('images')) {
        $files = $request->file('images');

        // التأكد من أن الصور في مصفوفة
        if (!is_array($files)) {
            $files = [$files];
        }

        foreach ($files as $file) {
            $path = $file->store('heavy_equip', [
                'disk' => 'public',
            ]);

            $image = new GeneralImage([
                'path' => $path,
            ]);

            // ربط الصورة بالخدمة
            $is_created->images()->save($image);
        }
    }

    return redirect()->route('home')->with('success', 'تم اضافة الطلب بنجاح');
}
public function show_my_service($id){
    $service = HeavyEquipmentService::find($id);
    $main = HeavyEquipment::where('id',$service->heavy_equip_id)->first();
    // dd($main);
    return view('admin.main_department.heavy_equip.show_myservice' , compact( 'main' , 'service'));
}
}
