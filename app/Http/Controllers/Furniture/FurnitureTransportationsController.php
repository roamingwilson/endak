<?php

namespace App\Http\Controllers\Furniture;

use App\Models\FurnitureTransportationProduct;
use App\Models\FurnitureTransportationService;
use App\Models\FurnitureTransportationServiceProducts;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\FurnitureTransportation;
use Illuminate\Support\Facades\Storage;

class FurnitureTransportationsController extends Controller
{

    public function index(){
        $main = FurnitureTransportation::first();
        return view('admin.furniture_transportations.index' , compact('main'));
    }
    public function edit($id){
        $main = FurnitureTransportation::find($id);
        return view('admin.furniture_transportations.edit' , compact('main'));
    }
    public function update(Request $request , $id){
        $main = FurnitureTransportation::find($id);
        $old_image = $main->image;

        $path = uploadImage( $request , 'furniture_transportations' , 'image');
        $main->update([
            'name_ar'               => $request->name_ar,
            'name_en'               => $request->name_en,
            'image'                 => $path,
        ]);
        if ($old_image && $path) {
            Storage::disk('public')->delete($old_image);
        }
        return redirect()->route('main_furniture_transportations')->with('success' , 'تم التحديث بنجاح');
    }
    public function show(){
        $user = auth()->user();
        $products = FurnitureTransportationProduct::get();
        $main = FurnitureTransportation::first();
        $services = FurnitureTransportationService::where('status' , 'open')->paginate();
        if(isset($user) && $user->role_id == 1){
            return view('front_office.furniture_transportations.show' , compact('products' , 'main' , 'services'));
        }elseif(isset($user) && $user->role_id == 3){
            $products = FurnitureTransportationServiceProducts::get();
            $main = FurnitureTransportation::first();
            $services = FurnitureTransportationService::where('status' , 'open')->paginate();
            return view('front_office.furniture_transportations.show' , compact('products' , 'main' , 'services'));
        }else{
            return view('front_office.furniture_transportations.show' , compact('products' , 'main' , 'services'));

        }
    }
    public function store_service(Request $request){
        $quantities = $request->quantities;
        $disassembly = $request->disassembly;
        $installation = $request->installation;
        $request->validate([
            'selected_products'  => 'required',
            'quantities'  => 'required',
            'from_city'  => 'required',
            'from_neighborhood'  => 'required',
            'from_home'  => 'required',
            'to_city'  => 'required',
            'to_neighborhood'  => 'required',
            'to_home'  => 'required',
        ]);

        $is_created = FurnitureTransportationService::create([
            'from_city'                     => $request->from_city,
            'from_neighborhood'             => $request->from_neighborhood,
            'from_home'                     => $request->from_home,
            'to_city'                       => $request->to_city,
            'to_neighborhood'               => $request->to_neighborhood,
            'to_home'                       => $request->to_home,
            'notes'                         => $request->notes, 
            'user_id'                       => auth()->id(), 
        ]);
        if($is_created){
            foreach($request->selected_products as $key => $value){
                if (isset($quantities[$value]) && !is_null($quantities[$value]) ) {
                    FurnitureTransportationServiceProducts::create([
                        'service_id' => $is_created->id,
                        'product_id' => $value,
                        'quantity' => $quantities[$value],
                        'installation'=> $installation[$value] ?? 0,
                        'disassembly'=>  $disassembly[$value] ?? 0,
                    ]);
                }
            }
        }
        return redirect()->route('home')->with('success' , 'تم اضافة الطلب بنجاح');

    }

    public function show_my_service($id){
        $service = FurnitureTransportationService::find($id);
        $products = FurnitureTransportationServiceProducts::where('service_id' , $id)->get();
        $main = FurnitureTransportation::first();
        return view('front_office.furniture_transportations.show_myservice' , compact('products' , 'main' , 'service'));
    }
    public function service_provider(){
        
    }
}
