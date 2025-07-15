<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessServiceImage;
use App\Jobs\SendCommentNotification;
use App\Jobs\SendWhatsappMessageJob;
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
use Illuminate\Support\Facades\Storage;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userId = auth()->id();

        $services = Services::where('user_id', auth()->id())->latest()->paginate(6);


        return view('front_office.posts.index', compact('services'));
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
            'notes_voice' => 'nullable|file|mimes:audio/wav,audio/mpeg,audio/ogg|max:5120',
            'status' => 'nullable|in:open,close,pending,confirm',
        ]);

        // Enhanced note_voice handling
        if ($request->filled('voice_note_data')) {
            // Handle base64-encoded audio (from JS recorder)
            $voiceData = $request->input('voice_note_data');
            $voiceData = preg_replace('/^data:audio\/(wav|mpeg|ogg);base64,/', '', $voiceData);
            $voiceData = base64_decode($voiceData);
            $fileName = 'services/notes_voice/' . uniqid() . '.wav';
            Storage::disk('public')->put($fileName, $voiceData);
            $data['notes_voice'] = $fileName;
        } elseif ($request->hasFile('notes_voice')) {
            // Handle traditional file upload
            $file = $request->file('notes_voice');
            $fileName = $file->store('services/notes_voice', 'public');
            $data['notes_voice'] = $fileName;
        }


        $service = Services::create($data);

        // إرسال رسالة واتساب تلقائياً لكل أرقام الاستقبال الخاصة بنفس القسم
        if ($service) {
            $department = Department::find($service->department_id);
            $departmentName = $department ? $department->name_ar : '';
            // جلب اسم المدينة
            $cityName = 'مكة'; // افتراضي
            if ($service->city) {
                $cityName = $service->city;
            } elseif ($service->from_city) {
                $city = \App\Models\Governements::find($service->from_city);
                $cityName = $city ? $city->name_ar : 'مكة';
            }
            $message = "مرحبا يوجد عميل يحتاج خدمة خاصة بقسم ($departmentName) علي موقع endak.net في مدينة $cityName , قدم عرض الان";
            // جلب أرقام الإرسال المرتبطة بالقسم عبر الجدول الوسيط
            $senders = \App\Models\WhatsappSender::whereHas('departments', function($q) use ($service) {
                $q->where('departments.id', $service->department_id);
            })->get();
            $recipients = \App\Models\WhatsappRecipients::where('department_id', $service->department_id)->pluck('number')->toArray();
            $senderCount = $senders->count();
            $i = 0;
            foreach ($recipients as $number) {
                if ($senderCount > 0) {
                    $sender = $senders[$i % $senderCount];
                    SendWhatsappMessageJob::dispatch($number, $message, $sender->number, $sender->token, $sender->instance_id)
                        ->delay(now()->addSeconds(rand(1, 10)));
                    $i++;
                }
            }
        }

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
        //custom services id


        if ($service && $request->hasFile('images')) {
            $files = $request->file('images');

            if (!is_array($files)) {
                $files = [$files];
            }

            foreach ($files as $file) {
                // Store the file temporarily and dispatch a job to process it.
                $tempPath = $file->store('temp', 'public');
                ProcessServiceImage::dispatch($service, $tempPath);
            }
        }

        return redirect()->route('home')->with('success', 'تم اضافة الطلب بنجاح');
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
        } else {
            $cities = Governements::where('country_id', 178)->get();
        }


        if (!$departments) {
            abort(404); // لو القسم مش موجود
        }

        switch (strtolower($departments->name_en)) { // نحول كل حاجة small letter عشان مفيش مشاكل كابتل وسمول

            case 'furniture transport':
                $products = FurnitureTransportationProduct::get();
                return view('front_office.furniture_transportations.show', compact('departments', 'cities', 'products'));

            case 'maintenance':
                $user = auth()->user();

                $page = request()->get('page', 1);
                $maintenancess = Cache::remember('maintenance.page.' . $page, 3600, function () {
                    return Maintenance::where('maintenance_id', '!=', 0)->paginate();
                });
                return view('admin.main_department.maintenance.front_show', compact('departments', 'cities', 'maintenancess'));

            case 'spare parts':
                $user = auth()->user();

                $page = request()->get('page', 1);
                $spare_parts = Cache::remember('spare_part.page.' . $page, 3600, function () {
                    return SpareParts::where('spare_part_id', '!=', 0)->paginate();
                });
                return view('admin.main_department.spare_part.front_show', compact('departments', 'cities', 'spare_parts'));

            case 'truks':
                $user = auth()->user();

                $page = request()->get('page', 1);
                $van_truck = Cache::remember('van_truck.page.' . $page, 3600, function () {
                    return VanTruck::where('vantruck_id', '!=', 0)->paginate();
                });

                return view('admin.main_department.van_truck.front_show', compact('departments', 'cities', 'user', 'van_truck'));

            case 'big car':
                return view('admin.main_department.big_car.show', compact('departments', 'cities'));
            case 'air condition':
                return view('admin.main_department.Air_con.show', compact('departments', 'cities'));
            case 'car water':
                return view('admin.main_department.car_water.show', compact('departments', 'cities'));
            case 'family':
                return view('admin.main_department.family.show', compact('departments', 'cities'));
            case 'cleaning':
                return view('admin.main_department.cleaning.show', compact('departments', 'cities'));
            case 'teacher':
                return view('admin.main_department.teacher.show', compact('departments', 'cities'));
            case 'security camera': //
                return view('admin.surveillance_cameras.show', compact('departments', 'cities'));
            case 'party':
                return view('admin.main_department.party_preparation.show', compact('departments', 'cities'));
            case 'garden':
                return view('admin.main_department.garden.show', compact('departments', 'cities'));
            case 'contracting': //
                $user = auth()->user();

                $page = request()->get('page', 1);
                $contractingss = Cache::remember('contracting.page.' . $page, 3600, function () {
                    return Contracting::where('contracting_id', '!=', 0)->paginate();
                });
                return view('admin.main_department.contracting.front_show', compact('departments', 'cities', 'contractingss'));
            case 'workers':
                return view('admin.main_department.worker.show', compact('departments', 'cities'));
            case 'public ge':
                return view('admin.main_department.public_ge.show', compact('departments', 'cities'));
            case 'insect': //
                return view('admin.main_department.counter_insects.show', compact('departments', 'cities'));
            case 'plastic':
                return view('admin.main_department.industry.product.index', compact('departments', 'cities'));
            case 'ads':

                return view('admin.main_department.ads.show', compact('departments', 'cities'));
            case 'water':
                return view('admin.main_department.water.show', compact('departments', 'cities'));

            case 'heavy equipment':
                $user = auth()->user();

                $page = request()->get('page', 1);
                $heavy_equips = Cache::remember('heavy_equip.page.' . $page, 3600, function () {
                    return HeavyEquipment::where('heavy_equip_id', '!=', 0)->paginate();
                });
                // dd($heavy_equips);
                return view('admin.main_department.heavy_equip.front_show', compact('departments', 'cities', 'heavy_equips'));

            default:
                abort(404); // لو الاسم مش من ضمن اللي فوق
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        if (auth()->check()) {
            $user = auth()->user();
            $cities = Governements::where('country_id', $user->country)->get();
            // dd($user->country);
        } else {
            $cities = Governements::where('country_id', 178)->get();
        }

        $service = Services::findOrFail($id);

        $date = [
            'service' => $service,
            'cities' => $cities
        ];
        if (auth()->id() !== $service->user_id) {
            abort(403, 'Unauthorized action.');
        }
        $user = auth()->user();


        switch (strtolower($service->type)) { // نحول كل حاجة small letter عشان مفيش مشاكل كابتل وسمول

            case 'furniture transport':
                $products = FurnitureTransportationProduct::get();

                return view('front_office.furniture_transportations.edit_service', compact('service', 'cities', 'products'));

            case 'maintenance':
                $user = auth()->user();
                $departments = Department::where('type', 'maintenance');
                $page = request()->get('page', 1);
                $maintenancess = Cache::remember('maintenance.page.' . $page, 3600, function () {
                    return Maintenance::where('maintenance_id', '!=', 0)->paginate();
                });
                return view('admin.main_department.maintenance.edit_service', compact('service', 'cities', 'departments', 'maintenancess'));

            case 'spare parts':
                $user = auth()->user();

                $page = request()->get('page', 1);
                $spare_parts = Cache::remember('spare_part.page.' . $page, 3600, function () {
                    return SpareParts::where('spare_part_id', '!=', 0)->paginate();
                });
                return view('admin.main_department.spare_part.edit_service', compact('service', 'cities', 'spare_parts'));

            case 'truks':
                $user = auth()->user();

                $page = request()->get('page', 1);
                $van_truck = Cache::remember('van_truck.page.' . $page, 3600, function () {
                    return VanTruck::where('vantruck_id', '!=', 0)->paginate();
                });

                return view('admin.main_department.van_truck.edit_service', compact('service', 'cities', 'user', 'van_truck'));

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
            case 'contracting': //
                $user = auth()->user();

                $page = request()->get('page', 1);
                $contractingss = Cache::remember('contracting.page.' . $page, 3600, function () {
                    return Contracting::where('contracting_id', '!=', 0)->paginate();
                });
                return view('admin.main_department.contracting.edit_service', compact('service', 'cities', 'contractingss'));
            case 'workers':
                return view('admin.main_department.worker.edit_service', $date);
            case 'public ge':
                return view('admin.main_department.public_ge.edit_service', $date);
            case 'insect': //
                return view('admin.main_department.counter_insects.edit_service', $date);
            case 'plastic':
                return view('admin.main_department.plastic.edit_service', $date);
            case 'ads':

                return view('admin.main_department.ads.edit_service', $date);
            case 'water':
                return view('admin.main_department.water.edit_service', $date);

            case 'heavy equipment':
                $user = auth()->user();

                $page = request()->get('page', 1);
                $heavy_equips = Cache::remember('heavy_equip.page.' . $page, 3600, function () {
                    return HeavyEquipment::where('heavy_equip_id', '!=', 0)->paginate();
                });
                return view('admin.main_department.heavy_equip.edit_service', compact('service', 'cities', 'heavy_equips'));

            default:
                abort(404); // لو الاسم مش من ضمن اللي فوق
        }
    }

    public function show_services($id)
    {



        $user = auth()->user();

        if (auth()->user()) {

            $service = Services::findOrFail($id);
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



        switch (strtolower($service->type)) { // نحول كل حاجة small letter عشان مفيش مشاكل كابتل وسمول

            case 'furniture transport':
                $products = $service->products()->get();
                // dd($products);

                return view('front_office.furniture_transportations.show_myservice', compact('service', 'form_city', 'to_city', 'products'));

            case 'maintenance':
                $user = auth()->user();
                $departments = Department::where('type', 'maintenance');
                $page = request()->get('page', 1);
                $maintenancess = Cache::remember('maintenance.page.' . $page, 3600, function () {
                    return Maintenance::where('maintenance_id', '!=', 0)->paginate();
                });
                return view('admin.main_department.maintenance.show_myservice', compact('service', 'form_city', 'to_city', 'departments', 'maintenancess'));

            case 'spare parts':
                $user = auth()->user();

                $page = request()->get('page', 1);
                $spare_parts = Cache::remember('spare_part.page.' . $page, 3600, function () {
                    return SpareParts::where('spare_part_id', '!=', 0)->paginate();
                });

                return view('admin.main_department.spare_part.show_myservice', compact('service', 'form_city', 'to_city', 'spare_parts'));

            case 'truks':
                $user = auth()->user();

                $page = request()->get('page', 1);
                $van_truck = Cache::remember('van_truck.page.' . $page, 3600, function () {
                    return VanTruck::where('vantruck_id', '!=', 0)->paginate();
                });

                return view('admin.main_department.van_truck.show_myservice', compact('service', 'form_city', 'to_city', 'user', 'van_truck'));

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
            case 'contracting': //
                $user = auth()->user();

                $page = request()->get('page', 1);
                $contractingss = Cache::remember('contracting.page.' . $page, 3600, function () {
                    return Contracting::where('contracting_id', '!=', 0)->paginate();
                });
                return view('admin.main_department.contracting.show_myservice', compact('service', 'form_city', 'to_city', 'contractingss'));
            case 'workers':
                return view('admin.main_department.worker.show_myservice', $date);
            case 'public ge':
                return view('admin.main_department.public_ge.show_myservice', $date);
            case 'insect': //
                return view('admin.main_department.counter_insects.show_myservice', $date);
            case 'plastic':
                return view('admin.main_department.plastic.show_myservice', $date);
            case 'ads':

                return view('admin.main_department.ads.show_myservice', $date);
            case 'water':
                return view('admin.main_department.water.show_myservice', $date);

            case 'heavy equipment':
                $user = auth()->user();

                $page = request()->get('page', 1);
                $heavy_equips = Cache::remember('heavy_equip.page.' . $page, 3600, function () {
                    return HeavyEquipment::where('heavy_equip_id', '!=', 0)->paginate();
                });

                return view('admin.main_department.heavy_equip.show_myservice', compact('service', 'form_city', 'to_city', 'heavy_equips'));

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
            'notes_voice' => 'nullable|file|mimes:audio/wav,audio/mpeg,audio/ogg|max:5120',
            'status' => 'nullable|in:open,close,pending,confirm',
        ]);

        $data['clean'] = $request->has('clean') ?? false;
        $data['feryoun'] = $request->has('feryoun') ?? false;
        $data['maintance'] = $request->has('maintance') ?? false;
        $data['finger'] = $request->has('finger') ?? false;
        $data['camera'] = $request->has('camera') ?? false;
        $data['smart'] = $request->has('smart') ?? false;
        $data['fire_system'] = $request->has('fire_system') ?? false;
        $data['security_system'] = $request->has('security_system') ?? false;
        $data['network'] = $request->has('network') ?? false;

        if ($request->filled('voice_note_data')) {
            // Handle base64-encoded audio (from JS recorder)
            $voiceData = $request->input('voice_note_data');
            $voiceData = preg_replace('/^data:audio\/(wav|mpeg|ogg);base64,/', '', $voiceData);
            $voiceData = base64_decode($voiceData);
            $fileName = 'services/notes_voice/' . uniqid() . '.wav';
            Storage::disk('public')->put($fileName, $voiceData);
            $data['notes_voice'] = $fileName;
        } elseif ($request->hasFile('notes_voice')) {
            // Handle traditional file upload
            $file = $request->file('notes_voice');
            $fileName = $file->store('services/notes_voice', 'public');
            $data['notes_voice'] = $fileName;
        }


        $service = Services::findOrFail($id);

        // update furniture transportation products


        $service->update($data);


        if (
            strtolower($service->type) === 'furniture transport' &&
            $request->has('selected_products')
        ) {
            $quantities = $request->quantities ?? [];
            $installation = $request->installation ?? [];
            $disassembly = $request->disassembly ?? [];
            $selectedProducts = $request->selected_products ?? [];

            // حذف المنتجات غير المختارة حاليًا
            FurnitureTransportationServiceProducts::where('service_id', $service->id)
                ->whereNotIn('product_id', $selectedProducts)
                ->delete();

            // تحديث أو إنشاء المنتجات المختارة
            foreach ($selectedProducts as $productId) {
                if (isset($quantities[$productId]) && !is_null($quantities[$productId])) {
                    FurnitureTransportationServiceProducts::updateOrCreate(
                        [
                            'service_id' => $service->id,
                            'product_id' => $productId,
                        ],
                        [
                            'quantity' => $quantities[$productId],
                            'installation' => $installation[$productId] ?? 0,
                            'disassembly' => $disassembly[$productId] ?? 0,
                        ]
                    );
                }
            }
        }

        // Fix N+1 query issue by eager loading relationships.
        // Also, delete comments in a more efficient way.
        if ($service->comments()->exists()) {
            $comments = $service->comments()->with('user', 'customer')->get();

            foreach ($comments as $comment) {
                $provider = $comment->user;
                $customer = $comment->customer;

                if ($provider && $customer) {
                    $notificationData = [
                        'id' => $comment->id,
                        'title' => "قام $customer->fullname بتعديل أو حذف الخدمة",
                        'body' => "قدم عرض جديد",
                        'url' => route('notifications.index'),
                    ];
                    SendCommentNotification::dispatch($provider, $notificationData);
                }
            }

            // Delete all comments for the service in a single query after notifying.
            $service->comments()->delete();
        }

        if ($service && $request->hasFile('images')) {
            $files = $request->file('images');

            if (!is_array($files)) {
                $files = [$files];
            }

            foreach ($files as $file) {
                // Store the file temporarily and dispatch a job to process it.
                $tempPath = $file->store('temp', 'public');
                ProcessServiceImage::dispatch($service, $tempPath);
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
