<?php

namespace App\Http\Controllers\departments;

use App\Http\Controllers\Controller;
use App\Models\GeneralImage;
use App\Models\HeavyEquipment;
use App\Models\HeavyEquipmentService;
use App\Notifications\CommentNotification;
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
    // $services = HeavyEquipmentService::where('heavy_equip_id' ,$id)->paginate(5);

    return view('admin.main_department.heavy_equip.show_sub_heavyequip' , compact( 'main' ));
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
    $user = auth()->user();
        $service = HeavyEquipmentService::find($id);
         $main = HeavyEquipment::find($service->heavy_equip_id);
    // dd($main);
    return view('admin.main_department.heavy_equip.show_myservice' , compact( 'main' , 'user','service'));
}
public function edit_service($id){

    $service=HeavyEquipmentService::findOrFail($id);
    $mains = HeavyEquipment::where('heavy_equip_id',!0)->get();
    if (auth()->id() !== $service->user_id) {
        abort(403, 'Unauthorized action.');
    }
    $user = auth()->user();


    return view('admin.main_department.heavy_equip.edit_service',compact('service','user','mains'));
}
public function update_service(Request $request,$id){
    $data = $request->validate([

        'heavy_equip_id' => 'required|exists:heavy_equipment,id',
        'location'       => 'required|string|max:255',
        'equip_type'     => 'nullable|string|max:255',
        'time'           => 'nullable|date_format:H:i',
        'user_id'        => 'required|exists:users,id',
        'notes'          => 'required|string',
    ]);

    try {
        $service = HeavyEquipmentService::findOrFail($id);
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
                $path = $file->store('heavy_equip/' . $service->id, 'public');

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
    $service = HeavyEquipmentService::findOrFail($id);

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
