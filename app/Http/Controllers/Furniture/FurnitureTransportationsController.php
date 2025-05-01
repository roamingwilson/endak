<?php

namespace App\Http\Controllers\Furniture;

use App\Models\FurnitureTransportationProduct;
use App\Models\FurnitureTransportationService;
use App\Models\FurnitureTransportationServiceProducts;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\FurnitureTransportation;
use App\Notifications\CommentNotification;
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
    public function edit_service($id){

        $service=FurnitureTransportationService::findOrFail($id);
        $mains = FurnitureTransportation::where('id',!0)->get();
        $products = FurnitureTransportationProduct::get();
        if (auth()->id() !== $service->user_id) {
            abort(403, 'Unauthorized action.');
        }
        $user = auth()->user();



        return view('front_office.furniture_transportations.edit_service',compact('service','user','mains','products'));
    }
    public function update_service(Request $request,$id)
    {

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

            $service = FurnitureTransportationService::findOrFail($id);

            $service->update([
                'from_city'             => $request->from_city,
                'from_neighborhood'     => $request->from_neighborhood,
                'from_home'             => $request->from_home,
                'to_city'               => $request->to_city,
                'to_neighborhood'       => $request->to_neighborhood,
                'to_home'               => $request->to_home,
                'notes'                 => $request->notes,
                'user_id'               => auth()->id(),
            ]);


            if ($service) {
                // Loop through the selected products
                foreach ($request->selected_products as $key => $value) {
                    // Check if the quantity is provided
                    if (isset($quantities[$value]) && !is_null($quantities[$value])) {
                        // Update existing product in the service or create new if not exists
                        $service->products()->updateOrCreate(
                            ['product_id' => $value], // Check by product ID
                            [
                                'quantity'   => $quantities[$value]?? 0,
                                'installation' => $installation[$value] ?? 0,
                                'disassembly' => $disassembly[$value] ?? 0,
                            ]
                        );
                    }
                }
            }
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
            return redirect()->route('home')->with('success', 'تم تحديث الطلب بنجاح');

    }
    public function destroy_service($id)
    {

    try {
        $service = FurnitureTransportationService::findOrFail($id);

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
