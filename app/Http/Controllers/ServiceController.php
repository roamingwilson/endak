<?php

namespace App\Http\Controllers;

use App\Models\Contracting;
use App\Models\Department;
use App\Models\FurnitureTransportationProduct;
use App\Models\FurnitureTransportationServiceProducts;
use App\Models\GeneralImage;
use App\Models\Governements;
use App\Models\HeavyEquipment;

use App\Models\Maintenance;
use App\Models\Services;
use App\Models\SpareParts;
use App\Models\VanTruck;
use App\Notifications\CommentNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;


class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userId = auth()->id();

        $Services = Services::where('user_id', auth()->id())->latest()->paginate(6);
        return view('front_office.posts.index', compact('Services'));


    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        // dd($request);
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'department_id' => 'required|exists:departments,id',
            'provider_id' => 'nullable|exists:users,id',
            'type' => 'required|string',


            'city' => 'nullable|string',
            'neighborhood' => 'nullable|string',
            'from_city' => 'nullable|string',
            'from_neighborhood' => 'nullable|string',
            'to_city' => 'nullable|string',
            'to_neighborhood' => 'nullable|string',

            'model' => 'nullable|string',
            'year' => 'nullable|string',
            'brand' => 'nullable|string',
            'part_number' => 'nullable|string',
            'equip_type' => 'nullable|string',
            'car_type' => 'nullable|string',
            'location' => 'nullable|string',
            'gender' => 'nullable|in:male,female',

            'time' => 'nullable',
            'date' => 'nullable|date',
            'day' => 'nullable|string',

            'quantity' => 'nullable|integer',
            'price' => 'nullable|numeric',
            'drink_width' => 'nullable|string',
            'wall_width' => 'nullable|string',
            'split' => 'nullable|boolean',
            'window' => 'nullable|boolean',
            'clean' => 'nullable|boolean',
            'feryoun' => 'nullable|boolean',
            'maintance' => 'nullable|boolean',
            'finger' => 'nullable|boolean',
            'camera' => 'nullable|boolean',
            'smart' => 'nullable|boolean',
            'fire_system' => 'nullable|boolean',
            'security_system' => 'nullable|boolean',
            'network' => 'nullable|boolean',

            'notes' => 'nullable|string',
            'notes_voice' => 'nullable|string',
            'status' => 'nullable|in:open,close,pending,confirm',
        ]);



        $service = Services::create($data);

        if (strtolower($service->type) == 'furniture transport' && $request->has('selected_products')) {
            $quantities = $request->quantities;
            $disassembly = $request->disassembly;
            $installation = $request->installation;

            foreach ($request->selected_products as $value) {
                if (isset($quantities[$value]) && !is_null($quantities[$value])) {
                    FurnitureTransportationServiceProducts::create([
                        'service_id' => $service->id,
                        'product_id' => $value,
                        'quantity' => $quantities[$value],
                        'installation' => $installation[$value] ?? 0,
                        'disassembly' => $disassembly[$value] ?? 0,
                    ]);
                }
            }

        }

            if ($service && $request->hasFile('images')) {
                $files = $request->file('images');

                if (!is_array($files)) {
                    $files = [$files];
                }

                foreach ($files as $file) {
                    $path = $file->store('services', [
                        'disk' => 'public',
                    ]);

                    $image = new GeneralImage([
                        'path' => $path,
                    ]);

                    // ✅ حفظ الصورة داخل الحلقة
                    $service->images()->save($image);
                }
            }

        return redirect()->route('home')->with('success' , 'تم اضافة الطلب بنجاح') ;
    }



    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $departments = Department::find($id);
        if (auth()->check()) {
             $user = auth()->user();
            $cities = Governements::where('country_id', $user->country)->get();
            // dd($user->country);
        }else{
             $cities = Governements::where('country_id', 178)->get();
        }


        if (!$departments) {
            abort(404); // لو القسم مش موجود
        }

        switch (strtolower($departments->name_en)) { // نحول كل حاجة small letter عشان مفيش مشاكل كابتل وسمول

            case 'furniture transport':
                $products = FurnitureTransportationProduct::get();
                return view('front_office.furniture_transportations.show', compact('departments','cities','products'));

            case 'maintenance':
                $user = auth()->user();

                $maintenancess = Cache::remember('maintenance', 60, function () {
                    return Maintenance::where('maintenance_id', '!=',0)->paginate();
                });
                return view('admin.main_department.maintenance.front_show', compact('departments','cities','maintenancess'));

            case 'spare parts':
                $user = auth()->user();

                $spare_parts = Cache::remember('spare_part', 60, function () {
                    return SpareParts::where('spare_part_id', '!=',0)->paginate();
                });
                return view('admin.main_department.spare_part.front_show', compact('departments','cities','spare_parts'));

            case 'truks':
                $user = auth()->user();

                $van_truck = Cache::remember('van_truck', 60, function () {
                    return VanTruck::where('vantruck_id', '!=', 0)->paginate();
                });

                return view('admin.main_department.van_truck.front_show', compact('departments','cities', 'user', 'van_truck'));

            case 'big car':
                return view('admin.main_department.big_car.show', compact('departments','cities'));
            case 'air condition':
                return view('admin.main_department.Air_con.show', compact('departments','cities'));
            case 'car water':
                return view('admin.main_department.car_water.show', compact('departments','cities'));
            case 'family':
                return view('admin.main_department.family.show', compact('departments','cities'));
            case 'cleaning':
                return view('admin.main_department.cleaning.show', compact('departments','cities'));
            case 'teacher':
                return view('admin.main_department.teacher.show', compact('departments','cities'));
            case 'security camera': //
                return view('admin.surveillance_cameras.show', compact('departments','cities'));
            case 'party':
                return view('admin.main_department.party_preparation.show', compact('departments','cities'));
            case 'garden':
                return view('admin.main_department.garden.show', compact('departments','cities'));
            case 'contracting'://
                $user = auth()->user();

                $contractingss = Cache::remember('contracting', 60, function () {
                    return Contracting::where('contracting_id', '!=',0)->paginate();
                });
                return view('admin.main_department.contracting.front_show', compact('departments','cities','contractingss'));
            case 'workers':
                return view('admin.main_department.worker.show', compact('departments','cities'));
            case 'public ge':
                return view('admin.main_department.public_ge.show', compact('departments','cities'));
            case 'insect'://
                return view('admin.main_department.counter_insects.show', compact('departments','cities'));
            case 'plastic':
                    return view('admin.main_department.industry.product.index', compact('departments','cities'));
            case 'ads':

                return view('admin.main_department.ads.show', compact('departments','cities'));
            case 'water':
                return view('admin.main_department.water.show', compact('departments','cities'));

            case 'heavy equipment':
                $user = auth()->user();

                $heavy_equips = Cache::remember('heavy_equip', 60, function () {
                    return HeavyEquipment::where('heavy_equip_id', '!=',0)->paginate();
                });
                // dd($heavy_equips);
                return view('admin.main_department.heavy_equip.front_show', compact('departments','cities','heavy_equips'));

            default:
                abort(404); // لو الاسم مش من ضمن اللي فوق
        }



    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit( $id)
    {


        $service = Services::where('id',$id)->latest()->first();

        $date = ['service' => $service];
        if (auth()->id() !== $service->user_id) {
            abort(403, 'Unauthorized action.');
        }
        $user = auth()->user();

        if (!$service) {
            abort(404); // لو القسم مش موجود
        }

        switch (strtolower($service->type)) { // نحول كل حاجة small letter عشان مفيش مشاكل كابتل وسمول

            case 'furniture transport':
                $products = FurnitureTransportationProduct::get();

                return view('front_office.furniture_transportations.edit_service', compact('service','products'));

            case 'maintenance':
                $user = auth()->user();
                $departments = Department::where('type', 'maintenance');
                $maintenancess = Cache::remember('maintenance', 60, function () {
                    return Maintenance::where('maintenance_id', '!=',0)->paginate();
                });
                return view('admin.main_department.maintenance.edit_service', compact('service','departments','maintenancess'));

            case 'spare parts':
                $user = auth()->user();

                $spare_parts = Cache::remember('spare_part', 60, function () {
                    return SpareParts::where('spare_part_id', '!=',0)->paginate();
                });
                return view('admin.main_department.spare_part.edit_service', compact('service','spare_parts'));

            case 'truks':
                $user = auth()->user();

                $van_truck = Cache::remember('van_truck', 60, function () {
                    return VanTruck::where('vantruck_id', '!=', 0)->paginate();
                });

                return view('admin.main_department.van_truck.edit_service', compact('service', 'user', 'van_truck'));

            case 'big car':
                return view('admin.main_department.big_car.edit_service', $date);
            case 'air condition':
                return view('admin.main_department.Air_con.edit_service', $date);
            case 'car water':
                return view('admin.main_department.car_water.edit_service', $date);
            case 'family':
                return view('admin.main_department.family.edit_service', $date);
            case 'cleaning':
                return view('admin.main_department.cleaning.edit_service', $date);
            case 'teacher':
                return view('admin.main_department.teacher.edit_service', $date);
            case 'security camera': //
                return view('admin.surveillance_cameras.edit_service', $date);
            case 'party':
                return view('admin.main_department.party_preparation.edit_service', $date);
            case 'garden':
                return view('admin.main_department.garden.edit_service', $date);
            case 'contracting'://
                $user = auth()->user();

                $contractingss = Cache::remember('contracting', 60, function () {
                    return Contracting::where('contracting_id', '!=',0)->paginate();
                });
                return view('admin.main_department.contracting.edit_service', compact('service','contractingss'));
            case 'workers':
                return view('admin.main_department.worker.edit_service', $date);
            case 'public ge':
                return view('admin.main_department.public_ge.edit_service', $date);
            case 'insect'://
                return view('admin.main_department.counter_insects.edit_service', $date);
            case 'plastic':
                    return view('admin.main_department.plastic.edit_service', $date);
            case 'ads':

                return view('admin.main_department.ads.edit_service', $date);
            case 'water':
                return view('admin.main_department.water.edit_service', $date);

            case 'heavy equipment':
                $user = auth()->user();

                $heavy_equips = Cache::remember('heavy_equip', 60, function () {
                    return HeavyEquipment::where('heavy_equip_id', '!=',0)->paginate();
                });
                return view('admin.main_department.heavy_equip.edit_service', compact('service','heavy_equips'));

            default:
                abort(404); // لو الاسم مش من ضمن اللي فوق
        }

    }

    public function show_services($id){



        $user = auth()->user();

         if (auth()->user() ){

             $service = Services::where('id',$id)->latest()->first();
            // dd($service);
            $form_city = Governements::where('id', $service->from_city)->first();
            $to_city = Governements::where('id', $service->to_city)->first();
         }
        // dd($city);
         $date = [
            'service' => $service,
            'form_city' => $form_city,
            'to_city' => $to_city,

        ];

        if (!$service) {
            abort(404); // لو القسم مش موجود
        }


        switch (strtolower($service->type)) { // نحول كل حاجة small letter عشان مفيش مشاكل كابتل وسمول

            case 'furniture transport':
                $products = $service->products()->get();
                // dd($products);

                return view('front_office.furniture_transportations.show_myservice', compact('service','from_city','to_city','products'));

            case 'maintenance':
                $user = auth()->user();
                $departments = Department::where('type', 'maintenance');
                $maintenancess = Cache::remember('maintenance', 60, function () {
                    return Maintenance::where('maintenance_id', '!=',0)->paginate();
                });
                return view('admin.main_department.maintenance.show_myservice', compact('service','form_city' , 'to_city','departments','maintenancess'));

            case 'spare parts':
                $user = auth()->user();

                $spare_parts = Cache::remember('spare_part', 60, function () {
                    return SpareParts::where('spare_part_id', '!=',0)->paginate();
                });

                return view('admin.main_department.spare_part.show_myservice', compact('service','form_city','to_city','spare_parts'));

            case 'truks':
                $user = auth()->user();

                $van_truck = Cache::remember('van_truck', 60, function () {
                    return VanTruck::where('vantruck_id', '!=', 0)->paginate();
                });

                return view('admin.main_department.van_truck.show_myservice', compact('service','form_city','to_city', 'user', 'van_truck'));

            case 'big car':
                return view('admin.main_department.big_car.show_myservice', $date);
            case 'air condition':
                return view('admin.main_department.Air_con.show_myservice', $date);
            case 'car water':
                return view('admin.main_department.car_water.show_myservice', $date);
            case 'family':
                return view('admin.main_department.family.show_myservice', $date);
            case 'cleaning':
                return view('admin.main_department.cleaning.show_myservice', $date);
            case 'teacher':
                return view('admin.main_department.teacher.show_myservice', $date);
            case 'security camera': //
                return view('admin.surveillance_cameras.show_myservice', $date);
            case 'party':
                return view('admin.main_department.party_preparation.show_myservice', $date);
            case 'garden':
                return view('admin.main_department.garden.show_myservice', $date);
            case 'contracting'://
                $user = auth()->user();

                $contractingss = Cache::remember('contracting', 60, function () {
                    return Contracting::where('contracting_id', '!=',0)->paginate();
                });
                return view('admin.main_department.contracting.show_myservice', compact('service','form_city','to_city','contractingss'));
            case 'workers':
                return view('admin.main_department.worker.show_myservice', $date);
            case 'public ge':
                return view('admin.main_department.public_ge.show_myservice', $date);
            case 'insect'://
                return view('admin.main_department.counter_insects.show_myservice', $date);
            case 'plastic':
                    return view('admin.main_department.plastic.show_myservice', $date);
            case 'ads':

                return view('admin.main_department.ads.show_myservice', $date);
            case 'water':
                return view('admin.main_department.water.show_myservice', $date);

            case 'heavy equipment':
                $user = auth()->user();

                $heavy_equips = Cache::remember('heavy_equip', 60, function () {
                    return HeavyEquipment::where('heavy_equip_id', '!=',0)->paginate();
                });

                return view('admin.main_department.heavy_equip.show_myservice', compact('service','form_city','to_city','heavy_equips'));

            default:
                abort(404); // لو الاسم مش من ضمن اللي فوق
        }

    }




    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {



        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'department_id' => 'required|exists:departments,id',
            'provider_id' => 'nullable|exists:users,id',
            'type' => 'required|string',



            'city' => 'nullable|string',
            'neighborhood' => 'nullable|string',
            'from_city' => 'required|string',
            'from_neighborhood' => 'nullable|string',
            'to_city' => 'nullable|string',
            'to_neighborhood' => 'nullable|string',

            'model' => 'nullable|string',
            'year' => 'nullable|string',
            'brand' => 'nullable|string',
            'part_number' => 'nullable|string',
            'equip_type' => 'nullable|string',
            'car_type' => 'nullable|string',
            'location' => 'nullable|string',
            'gender' => 'nullable|in:male,female',

            'time' => 'nullable',
            'date' => 'nullable|date',
            'day' => 'nullable|string',

            'quantity' => 'nullable|integer',
            'price' => 'nullable|numeric',
            'drink_width' => 'nullable|string',
            'wall_width' => 'nullable|string',
            'split' => 'nullable|boolean',
            'window' => 'nullable|boolean',
            'clean' => 'nullable|boolean',
            'feryoun' => 'nullable|boolean',
            'maintance' => 'nullable|boolean',
            'finger' => 'nullable|boolean',
            'camera' => 'nullable|boolean',
            'smart' => 'nullable|boolean',
            'fire_system' => 'nullable|boolean',
            'security_system' => 'nullable|boolean',
            'network' => 'nullable|boolean',

            'notes' => 'nullable|string',
            'notes_voice' => 'nullable|string',
            'status' => 'nullable|in:open,close,pending,confirm',
        ]);



            $service = Services::findOrFail($id);
            // dd($service);

            $service->update($data);


        if (strtolower($service->type) == 'furniture transport') {
            $quantities = $request->quantity;
            $installation = $request->installation;
            $disassembly = $request->disassembly;

            foreach ($request->selected_products as $key => $value) {
                if (isset($quantities[$value]) && !is_null($quantities[$value])) {
                    FurnitureTransportationServiceProducts::updateOrCreate([
                        'service_id' => $service->id,
                        'product_id' => $value,
                        'quantity' => $quantities[$value],
                        'installation' => $installation[$value] ?? 0,
                        'disassembly' => $disassembly[$value] ?? 0,
                    ]);
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

        if ($service && $request->hasFile('images')) {
            $files = $request->file('images');

            if (!is_array($files)) {
                $files = [$files];
            }

            foreach ($files as $file) {
                $path = $file->store('services', ['disk' => 'public']);

                $image = new GeneralImage([
                    'path' => $path,
                ]);

                $service->images()->save($image);
            }
        }

        return redirect()->route('home')->with('success', 'تم نحديث الطلب بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $service = Services::findOrFail($id);
        $service->delete();


    return redirect()->route('home')->with('success', 'تم حذف الطلب بنجاح');
    }
}
