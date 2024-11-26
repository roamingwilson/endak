<?php

namespace App\Http\Controllers\departments;

use App\Models\GeneralImage;
use Illuminate\Http\Request;
use App\Models\PartyPreparation;
use App\Http\Controllers\Controller;
use App\Models\PartyPreparationService;
use Illuminate\Support\Facades\Storage;

class PartyPreparationController extends Controller
{
    public function index(){
        $party_preparation = PartyPreparation::first();
        return view('admin.main_department.party_preparation.index' , compact('party_preparation'));
    }
    public function edit($id){
        $main = PartyPreparation::find($id);
        return view('admin.main_department.party_preparation.edit' , compact('main'));
    }

    public function update(Request $request , $id){
        $main = PartyPreparation::find($id);
        $old_image = $main->image;

        $path = uploadImage( $request , 'party_preparation' , 'image');
        $main->update([
            'name_ar'               => $request->name_ar,
            'name_en'               => $request->name_en,
            'image'                 => $path,
        ]);
        if ($old_image && $path) {
            Storage::disk('public')->delete($old_image);
        }
        return redirect()->route('admin.party_preparation')->with('success' , 'تم التحديث بنجاح');
    }

    public function show(){
        $user = auth()->user();
        $main = PartyPreparation::first();
        $services = PartyPreparationService::paginate();
        if(isset($user) && $user->role_id == 1){
            return view('admin.main_department.party_preparation.show' , compact(  'main' , 'services'));
        }elseif(isset($user) && $user->role_id == 3){
            $main = PartyPreparation::first();
            $services = PartyPreparationService::paginate();
            return view('admin.main_department.party_preparation.show' , compact(  'main' , 'services'));
        }else{
            return view('admin.main_department.party_preparation.show' , compact( 'main' , 'services'));

        }
    }

    public function store_service(Request $request)
    {
        $data = $request->except('_token', 'images');
    
        $is_created = PartyPreparationService::create($data);
    
        if ($is_created) {
            if ($request->hasFile('images')) {
                $files = $request->file('images');
    
                if (!is_array($files)) {
                    $files = [$files];
                }
    
                foreach ($files as $file) {
                    $path = $file->store('party_preparation', [
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
        $service = PartyPreparationService::find($id);
        $main = PartyPreparation::first();
        return view('admin.main_department.party_preparation.show_myservice' , compact( 'main' , 'service'));
    }
}
