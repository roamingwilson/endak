<?php

namespace App\Http\Controllers\departments;

use App\Models\Maintenance;
use App\Models\GeneralImage;
use Illuminate\Http\Request;
use App\Models\MaintenanceService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class MaintenanceController extends Controller
{
    public function index(){
        $maintenance = Maintenance::where('maintenance_id',0)->first();
        return view('admin.main_department.maintenance.index' , compact('maintenance'));
    }
    public function edit($id){
        $main = Maintenance::find($id);
        return view('admin.main_department.maintenance.edit' , compact('main'));
    }

    public function update(Request $request , $id){
        $main = Maintenance::find($id);
        $old_image = $main->image;

        $path = uploadImage( $request , 'maintenance' , 'image');
        $main->update([
            'name_ar'               => $request->name_ar,
            'name_en'               => $request->name_en,
            'image'                 => $path,
        ]);
        if ($old_image && $path) {
            Storage::disk('public')->delete($old_image);
        }
        return redirect()->route('admin.maintenance')->with('success' , 'تم التحديث بنجاح');
    }
    public function add_sub_department(){
        $maintenance = Maintenance::where('maintenance_id' , 0)->first();
        return view('admin.main_department.maintenance.create');
    }
    public function store_sub_department(Request $request  ){
        $maintenance = Maintenance::where('maintenance_id' , 0)->first();

        $path = uploadImage( $request , 'maintenance' , 'image');
        Maintenance::create([
            'name_ar'               => $request->name_ar,
            'name_en'               => $request->name_en,
            'image'                 => $path,
            'maintenance_id'        => $maintenance->id,
        ]);
 
        return redirect()->route('admin.maintenance')->with('success' , 'تم الاضافة بنجاح');
    }
    public function show_sub_departments_list(){
        $departments = Maintenance::where('maintenance_id', '!=' , 0)->paginate();

        return view('admin.main_department.maintenance.departments_list' , compact('departments')); 
    }
    public function show_sub_department($id){
        $maintenance = Maintenance::find($id);
        return view('admin.main_department.maintenance.index' , compact('maintenance'));
    }
    public function delete($id)
    {
        $maintenance = Maintenance::where('maintenance_id', '!=', 0)->find($id);
    
        if ($maintenance) {
            $maintenance->delete();
            return to_route('admin.maintenance.show_sub_departments_list')->with('success', 'Maintenance deleted successfully.');
        }
    
        return to_route('admin.maintenance.show_sub_departments_list')->with('error', 'Maintenance not found.');
    }
    
    public function show(){
        $user = auth()->user();
        $main = Maintenance::where('maintenance_id',0)->first();
        $maintenancess = Cache::remember('maintenance', 60, function () {
            return Maintenance::where('maintenance_id', '!=',0)->paginate();
        });
        return view('admin.main_department.maintenance.front_show' , compact( 'main' , 'maintenancess'));
    }
    
    public function maintenance_sub_show($id){
        $user = auth()->user();
        $main = Maintenance::find($id);
        $services = MaintenanceService::where('maintenance_id' ,$id)->paginate();
         
        return view('admin.main_department.maintenance.show_sub_maintenance' , compact( 'main','services'  ));
    }

    public function store_service(Request $request )
    {
        $data = $request->except('_token', 'images');
        $is_created = MaintenanceService::create($data);
    
        if ($is_created) {
            if ($request->hasFile('images')) {
                $files = $request->file('images');
    
                if (!is_array($files)) {
                    $files = [$files];
                }
    
                foreach ($files as $file) {
                    $path = $file->store('maintenance', [
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
        $service = MaintenanceService::find($id);
        $main = Maintenance::where('id',$service->maintenance_id)->first();
        // dd($main);
        return view('admin.main_department.maintenance.show_myservice' , compact( 'main' , 'service'));
    }

}
