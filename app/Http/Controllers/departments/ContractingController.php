<?php

namespace App\Http\Controllers\departments;

use App\Models\Contracting;
use App\Models\GeneralImage;
use Illuminate\Http\Request;
use App\Models\ContractingService;
use App\Http\Controllers\Controller;
use App\Notifications\CommentNotification;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class ContractingController extends Controller
{
    public function index(){
        $contracting = Contracting::where('contracting_id',0)->first();
        return view('admin.main_department.contracting.index' , compact('contracting'));
    }
    public function edit($id){
        $main = Contracting::find($id);
        return view('admin.main_department.contracting.edit' , compact('main'));
    }

    public function update(Request $request , $id){
        $main = Contracting::find($id);
        $old_image = $main->image;

        $path = uploadImage( $request , 'contracting' , 'image');
        $main->update([
            'name_ar'               => $request->name_ar,
            'name_en'               => $request->name_en,
            'image'                 => $path,
        ]);
        if ($old_image && $path) {
            Storage::disk('public')->delete($old_image);
        }
        return redirect()->route('admin.contracting')->with('success' , 'تم التحديث بنجاح');
    }
    public function add_sub_department(){
        $contracting = Contracting::where('contracting_id' , 0)->first();
        return view('admin.main_department.contracting.create');
    }
    public function store_sub_department(Request $request  ){
        $contracting = Contracting::where('contracting_id' , 0)->first();

        $path = uploadImage( $request , 'contracting' , 'image');
        Contracting::create([
            'name_ar'               => $request->name_ar,
            'name_en'               => $request->name_en,
            'image'                 => $path,
            'contracting_id'        => $contracting->id,
        ]);

        return redirect()->route('admin.contracting')->with('success' , 'تم الاضافة بنجاح');
    }
    public function show_sub_departments_list(){
        $departments = Contracting::where('contracting_id', '!=' , 0)->paginate();

        return view('admin.main_department.contracting.departments_list' , compact('departments'));
    }
    public function show_sub_department($id){
        $contracting = Contracting::find($id);
        return view('admin.main_department.contracting.index' , compact('contracting'));
    }
    public function delete($id)
    {
        $contracting = Contracting::where('contracting_id', '!=', 0)->find($id);

        if ($contracting) {
            $contracting->delete();
            return to_route('admin.contracting.show_sub_departments_list')->with('success', 'Contracting deleted successfully.');
        }

        return to_route('admin.contracting.show_sub_departments_list')->with('error', 'Contracting not found.');
    }

    public function show(){
        $user = auth()->user();
        $main = Contracting::where('contracting_id',0)->first();
        $contractingss = Cache::remember('contracting', 60, function () {
            return Contracting::where('contracting_id', '!=',0)->paginate();
        });
        return view('admin.main_department.contracting.front_show' , compact( 'main' , 'contractingss'));
    }

    public function contracting_sub_show($id){
        $user = auth()->user();
        $main = Contracting::find($id);
        $services = ContractingService::where('contracting_id' ,$id)->paginate();

        return view('admin.main_department.contracting.show_sub_contracting' , compact( 'main','services'  ));
    }

    public function store_service(Request $request )
    {
        $data = $request->except('_token', 'images');

        $data = $request->validate([

            'contracting_id' => 'required|exists:contracting,id',
            'neighborhood'       => 'required|string|max:255',
            'city'       => 'required|string|max:255',


            'user_id'        => 'required|exists:users,id',
            'notes'          => 'required|string',
        ]);
        $is_created = ContractingService::create($data);

        if ($is_created) {
            if ($request->hasFile('images')) {
                $files = $request->file('images');

                if (!is_array($files)) {
                    $files = [$files];
                }

                foreach ($files as $file) {
                    $path = $file->store('contracting', [
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
        $service = ContractingService::find($id);
        $main = Contracting::where('id',$service->contracting_id)->first();
        // dd($main);
        return view('admin.main_department.contracting.show_myservice' , compact( 'main' , 'service'));
    }
    public function edit_service($id){

        $service=ContractingService::findOrFail($id);
        $mains = Contracting::get();
        if (auth()->id() !== $service->user_id) {
            abort(403, 'Unauthorized action.');
        }
        $user = auth()->user();


        return view('admin.main_department.contracting.edit_service',compact('service','user','mains'));
    }
    public function update_service(Request $request,$id){
        $data = $request->validate([

            'contracting_id' => 'required|exists:contractings,id',
            'neighborhood'       => 'required|string|max:255',
            'city'       => 'required|string|max:255',


            'user_id'        => 'required|exists:users,id',
            'notes'          => 'required|string',
        ]);

        try {
            $service = ContractingService::findOrFail($id);
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
                    $path = $file->store('contracting/' . $service->id, 'public');

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
        $service = ContractingService::findOrFail($id);

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
