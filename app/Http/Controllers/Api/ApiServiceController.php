<?php

namespace App\Http\Controllers\APi;

use App\Http\Controllers\Controller;
use App\Models\Contracting;
use App\Models\Department;
use App\Models\FurnitureTransportationProduct;
use App\Models\FurnitureTransportationServiceProducts;
use App\Models\GeneralImage;
use App\Models\HeavyEquipment;
use App\Models\Maintenance;
use App\Models\Services;
use App\Models\SpareParts;
use App\Models\VanTruck;
use App\Notifications\CommentNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ApiServiceController extends Controller

{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userId = auth()->id();

        $Services = Services::where('user_id', $userId)->latest()->paginate(6);
        return response()->json($Services, 200, );


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


            // رفع الصور إن وجدت
            if ($service && $request->hasFile('images')) {
                foreach ($request->file('images') as $file) {
                    $path = $file->store('services', 'public');

                    $service->images()->create([
                        'path' => $path,
                    ]);
                }
            }
        }


        // return redirect()->route('home')->with('success' , 'تم اضافة الطلب بنجاح') ;
        return response()->apiSuccess( 'success', 200);
    }



    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

        $departments = Department::find($id);


        if (!$departments) {
            abort(404); // لو القسم مش موجود
        }

        switch (strtolower($departments->name_en)) { // نحول كل حاجة small letter عشان مفيش مشاكل كابتل وسمول

            case 'furniture transport':
                $products = FurnitureTransportationProduct::get();
                return response()->apiSuccess($departments,$products,200);

            case 'maintenance':
                $user = auth()->user();

                $maintenancess = Cache::remember('maintenance', 60, function () {
                    return Maintenance::where('maintenance_id', '!=',0)->paginate();
                });

                return response()->apiSuccess($departments,$maintenancess,200);
            case 'spare parts':
                $user = auth()->user();

                $spare_parts = Cache::remember('spare_part', 60, function () {
                    return SpareParts::where('spare_part_id', '!=',0)->paginate();
                });
                return response()->apiSuccess($departments,$spare_parts,200);


            case 'truks':
                $user = auth()->user();

                $van_truck = Cache::remember('van_truck', 60, function () {
                    return VanTruck::where('vantruck_id', '!=', 0)->paginate();
                });
                return response()->apiSuccess($departments,$van_truck,200);


            case 'big car':

                return response()->apiSuccess($departments,200);
            case 'air condition':
                return response()->apiSuccess($departments,200);
            case 'car water':
                return response()->apiSuccess($departments,200);

            case 'family':
                return response()->apiSuccess($departments,200);

            case 'cleaning':
                return response()->apiSuccess($departments,200);

            case 'teacher':

                return response()->apiSuccess($departments,200);
            case 'security camera': //
                return response()->apiSuccess($departments,200);
            case 'party':
                return response()->apiSuccess($departments,200);
            case 'garden':
                return response()->apiSuccess($departments,200);
            case 'contracting'://
                $user = auth()->user();

                $contractingss = Cache::remember('contracting', 60, function () {
                    return Contracting::where('contracting_id', '!=',0)->paginate();
                });
                return response()->apiSuccess($departments,$contractingss,200);
            case 'workers':
                return response()->apiSuccess($departments,200);
            case 'public ge':
                return response()->apiSuccess($departments,200);
             case 'insect'://

                return response()->apiSuccess($departments,200);
            case 'plastic':
                return response()->apiSuccess($departments,200);
            case 'ads':

                return response()->apiSuccess($departments,200);
            case 'water':
                return response()->apiSuccess($departments,200);

            case 'heavy equipment':
                $user = auth()->user();

                $heavy_equips = Cache::remember('heavy_equip', 60, function () {
                    return HeavyEquipment::where('heavy_equip_id', '!=',0)->paginate();
                });
                // dd($heavy_equips);
                return response()->apiSuccess($departments,$heavy_equips,200);

            default:
                abort(404); // لو الاسم مش من ضمن اللي فوق
        }



    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {

        $service = Services::where('id',$id)->latest()->first();
        if (auth()->id() !== $service->user_id) {
            abort(403, 'Unauthorized action.');
        }
        $user = auth()->user();

        if (!$service) {
            abort(404); // لو القسم مش موجود
        }

        switch (strtolower($service->type)) { // نحول كل حاجة small letter عشان مفيش مشاكل كابتل وسمول

            case 'furniture transport':
                $products = FurnitureTransportationServiceProducts::get();
                return response()->apiSuccess($service,$products,200);

            case 'maintenance':
                $user = auth()->user();
                $departments = Department::where('type', 'maintenance');
                $maintenancess = Cache::remember('maintenance', 60, function () {
                    return Maintenance::where('maintenance_id', '!=',0)->paginate();
                });
                return response()->apiSuccess($service,$maintenancess,$departments,200);

            case 'spare parts':
                $user = auth()->user();

                $spare_parts = Cache::remember('spare_part', 60, function () {
                    return SpareParts::where('spare_part_id', '!=',0)->paginate();
                });
                return response()->apiSuccess($service,$spare_parts,200);

            case 'truks':
                $user = auth()->user();

                $van_truck = Cache::remember('van_truck', 60, function () {
                    return VanTruck::where('vantruck_id', '!=', 0)->paginate();
                });

                return response()->apiSuccess($service,$van_truck,200);

            case 'big car':
                return response()->apiSuccess($service,200);
            case 'air condition':
                return response()->apiSuccess($service,200);
            case 'car water':
                return response()->apiSuccess($service,200);
            case 'family':
                return response()->apiSuccess($service,200);
            case 'cleaning':
                return response()->apiSuccess($service,200);
            case 'teacher':
                return response()->apiSuccess($service,200);
            case 'security camera': //
                return response()->apiSuccess($service,200);
            case 'party':
                return response()->apiSuccess($service,200);
            case 'garden':
                return response()->apiSuccess($service,200);
            case 'contracting'://
                $user = auth()->user();

                $contractingss = Cache::remember('contracting', 60, function () {
                    return Contracting::where('contracting_id', '!=',0)->paginate();
                });
                return response()->apiSuccess($service,$contractingss ,200);
            case 'workers':
                return response()->apiSuccess($service,200);
            case 'public ge':
                return response()->apiSuccess($service,200);
            case 'insect'://
                return response()->apiSuccess($service,200);
            case 'plastic':
                return response()->apiSuccess($service,200);
            case 'ads':

                return response()->apiSuccess($service,200);
            case 'water':
                return response()->apiSuccess($service,200);

            case 'heavy equipment':
                $user = auth()->user();

                $heavy_equips = Cache::remember('heavy_equip', 60, function () {
                    return HeavyEquipment::where('heavy_equip_id', '!=',0)->paginate();
                });
                return response()->apiSuccess($service, $heavy_equips ,200);

            default:
                abort(404); // لو الاسم مش من ضمن اللي فوق
        }

    }

    public function show_services($id){
        // dd($id);
        $service = Services::where('id',$id)->latest()->first();

        if (!$service) {
            abort(404); // لو القسم مش موجود
        }

        switch (strtolower($service->type)) { // نحول كل حاجة small letter عشان مفيش مشاكل كابتل وسمول

            case 'furniture transport':
                $products = FurnitureTransportationServiceProducts::get();
                return response()->apiSuccess($service,$products,200);

            case 'maintenance':
                $user = auth()->user();
                $departments = Department::where('type', 'maintenance');
                $maintenancess = Cache::remember('maintenance', 60, function () {
                    return Maintenance::where('maintenance_id', '!=',0)->paginate();
                });
                return response()->apiSuccess($service,$maintenancess,$departments,200);

            case 'spare parts':
                $user = auth()->user();

                $spare_parts = Cache::remember('spare_part', 60, function () {
                    return SpareParts::where('spare_part_id', '!=',0)->paginate();
                });
                return response()->apiSuccess($service,$spare_parts,200);

            case 'truks':
                $user = auth()->user();

                $van_truck = Cache::remember('van_truck', 60, function () {
                    return VanTruck::where('vantruck_id', '!=', 0)->paginate();
                });

                return response()->apiSuccess($service,$van_truck,200);

            case 'big car':
                return response()->apiSuccess($service,200);
            case 'air condition':
                return response()->apiSuccess($service,200);
            case 'car water':
                return response()->apiSuccess($service,200);
            case 'family':
                return response()->apiSuccess($service,200);
            case 'cleaning':
                return response()->apiSuccess($service,200);
            case 'teacher':
                return response()->apiSuccess($service,200);
            case 'security camera': //
                return response()->apiSuccess($service,200);
            case 'party':
                return response()->apiSuccess($service,200);
            case 'garden':
                return response()->apiSuccess($service,200);
            case 'contracting'://
                $user = auth()->user();

                $contractingss = Cache::remember('contracting', 60, function () {
                    return Contracting::where('contracting_id', '!=',0)->paginate();
                });
                return response()->apiSuccess($service,$contractingss ,200);
            case 'workers':
                return response()->apiSuccess($service,200);
            case 'public ge':
                return response()->apiSuccess($service,200);
            case 'insect'://
                return response()->apiSuccess($service,200);
            case 'plastic':
                return response()->apiSuccess($service,200);
            case 'ads':

                return response()->apiSuccess($service,200);
            case 'water':
                return response()->apiSuccess($service,200);

            case 'heavy equipment':
                $user = auth()->user();

                $heavy_equips = Cache::remember('heavy_equip', 60, function () {
                    return HeavyEquipment::where('heavy_equip_id', '!=',0)->paginate();
                });
                return response()->apiSuccess($service, $heavy_equips ,200);

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

        return response()->apiSuccess($service, 'success' ,200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $service = Services::findOrFail($id);
        $service->delete();


        return response()->apiSuccess($service, 'success' ,200);
    }
}
