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
use App\Models\Settings;
use App\Models\MessageTemplate;
use App\Notifications\CommentNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
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
        // dd($request->all());
        // تحقق من تسجيل الدخول
        if (!auth()->check()) {
            return redirect()->route('login-page')->with('error', 'يجب تسجيل الدخول أولاً لطلب خدمة.');
        }
        // جلب الحقول الديناميكية للقسم
        $departmentFields = [];
        if ($request->has('department_id')) {
            $departmentFields = \App\Models\DepartmentField::where('department_id', $request->department_id)->get();

            // Check for problematic fields
            $this->checkAndCleanFields($request->department_id);
        }

        // إضافة قاعدة التحقق من sub_department_id
        $validationRules = [
            'user_id' => 'required|exists:users,id',
            'department_id' => 'required|exists:departments,id',
            'sub_department_id' => 'nullable|exists:sub_departments,id',
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
        ];

        // بناء قواعد التحقق الديناميكية
        $customFieldRules = $this->buildCustomFieldValidationRules($departmentFields, $request);
        $validationRules = array_merge($validationRules, $customFieldRules);

        // تمرير أي حقل من custom_fields إلى الجذر إذا كان مطلوبًا في الفاليديشن
        foreach (array_keys($validationRules) as $field) {
            if (!$request->has($field) && $request->has("custom_fields.$field")) {
                $request->merge([
                    $field => $request->input("custom_fields.$field")
                ]);
            }
        }

        // Debug: Log validation rules for troubleshooting
        Log::info('Validation rules for service creation:', [
            'department_id' => $request->department_id,
            'custom_field_rules' => $customFieldRules,
            'all_rules' => $validationRules
        ]);

        // Debug: Log the actual request data for image fields
        if ($request->has('custom_fields')) {
            Log::info('Custom fields data:', [
                'custom_fields' => $request->input('custom_fields'),
                'files' => $request->allFiles()
            ]);
        }

        try {
            $data = $request->validate($validationRules);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Service validation failed:', [
                'errors' => $e->errors(),
                'request_data' => $request->all(),
                'validation_rules' => $validationRules
            ]);
            throw $e;
        }

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

        // معالجة الحقول المخصصة (بما فيها الصور)
        $department = Department::with('fields')->find($request->department_id);
        $customFields = [];
        $imagesToSave = []; // Array to store images for later saving to GeneralImage

        if ($department && $department->fields) {
            $grouped = $department->fields->groupBy('input_group');
            foreach ($department->fields as $field) {
                $fieldName = $field->name;
                $group = $field->input_group;
                // إذا كانت المجموعة قابلة للتكرار
                if ($group && $field->is_repeatable && isset($request->custom_fields[$group]) && is_array($request->custom_fields[$group])) {
                    // خذ فقط أول 10 عناصر
                    $instances = array_slice($request->custom_fields[$group], 0, 10);
                    $customFields[$group] = [];
                    foreach ($instances as $instanceIdx => $instance) {
                        $instanceData = [];
                        foreach ($grouped[$group] as $groupField) {
                            $fname = $groupField->name;
                            if ($groupField->type === 'image' || $groupField->type === 'images[]' || $groupField->type === 'imagess') {
                                $files = $request->file("custom_fields.$group.$instanceIdx.$fname");
                                if ($files) {
                                    $paths = [];
                                    if (is_array($files)) {
                                        foreach ($files as $file) {
                                            if ($file && $file->isValid()) {
                                                $path = $file->store('services/images', 'public');
                                                $paths[] = $path;
                                                $imagesToSave[] = $path; // Store for GeneralImage
                                            }
                                        }
                                    } else {
                                        if ($files->isValid()) {
                                            $path = $files->store('services/images', 'public');
                                            $paths[] = $path;
                                            $imagesToSave[] = $path; // Store for GeneralImage
                                        }
                                    }
                                    $instanceData[$fname] = $paths;
                                } else {
                                    $instanceData[$fname] = [];
                                }
                            } elseif ($groupField->type === 'checkbox') {
                                $instanceData[$fname] = isset($instance[$fname]) ? 1 : 0;
                            } else {
                                $instanceData[$fname] = $instance[$fname] ?? null;
                            }
                        }
                        $customFields[$group][] = $instanceData;
                    }
                } elseif ($group && !$field->is_repeatable) {
                    // الحقول المجمعة غير القابلة للتكرار
                    if ($field->type === 'image' || $field->type === 'images[]') {
                        $files = $request->file("custom_fields.$fieldName");
                        if ($files && !empty($files)) {
                            $paths = [];
                            if (is_array($files)) {
                                foreach ($files as $file) {
                                    if ($file && $file->isValid()) {
                                        $path = $file->store('services/images', 'public');
                                        $paths[] = $path;
                                        $imagesToSave[] = $path; // Store for GeneralImage
                                    }
                                }
                                $customFields[$fieldName] = $paths;
                            } else {
                                if ($files->isValid()) {
                                    $path = $files->store('services/images', 'public');
                                    $customFields[$fieldName] = [$path];
                                    $imagesToSave[] = $path; // Store for GeneralImage
                                }
                            }
                        } else {
                            $customFields[$fieldName] = [];
                        }
                    } elseif ($field->type === 'checkbox') {
                        $customFields[$fieldName] = $request->has("custom_fields.$fieldName") ? 1 : 0;
                    } else {
                        $customFields[$fieldName] = $request->input("custom_fields.$fieldName");
                    }
                } elseif (!$group) {
                    // الحقول العادية (غير مجمعة)
                    if ($field->type === 'image' || $field->type === 'images[]' || $field->type === 'imagess') {
                        $files = $request->file("custom_fields.$fieldName");
                        if ($files) {
                            $paths = [];
                            if (is_array($files)) {
                                foreach ($files as $file) {
                                    if ($file && $file->isValid()) {
                                        $path = $file->store('services/images', 'public');
                                        $paths[] = $path;
                                        $imagesToSave[] = $path; // Store for GeneralImage
                                    }
                                }
                                $customFields[$fieldName] = $paths;
                            } else {
                                if ($files->isValid()) {
                                    $path = $files->store('services/images', 'public');
                                    $customFields[$fieldName] = [$path];
                                    $imagesToSave[] = $path; // Store for GeneralImage
                                }
                            }
                        } else {
                            $customFields[$fieldName] = [];
                        }
                    } elseif ($field->type === 'checkbox') {
                        $customFields[$fieldName] = $request->has("custom_fields.$fieldName") ? 1 : 0;
                    } else {
                        $customFields[$fieldName] = $request->input("custom_fields.$fieldName");
                    }
                }
            }
        }
        $data['custom_fields'] = $customFields;

        $service = Services::create($data);

        // Save images to GeneralImage model
        foreach ($imagesToSave as $imagePath) {
            $image = new GeneralImage([
                'path' => $imagePath,
            ]);
            $service->images()->save($image);
        }

       // إرسال إشعار لمزودي الخدمة المشتركين في القسم أو القسم الفرعي
        if ($service) {
            $mainProviders = \App\Models\User::where('role_id', 3)
                ->where('status', 'active')
                ->whereHas('userDepartments', function($q) use ($service) {
                    $q->where('commentable_type', \App\Models\Department::class)
                      ->where('commentable_id', $service->department_id);
                })->get();
            $subProviders = collect();
            if ($service->sub_department_id) {
                $subProviders = \App\Models\User::where('role_id', 3)
                    ->where('status', 'active')
                    ->whereHas('userDepartments', function($q) use ($service) {
                        $q->where('commentable_type', \App\Models\SubDepartment::class)
                          ->where('commentable_id', $service->sub_department_id);
                    })->get();
            }
            $providers = $mainProviders->merge($subProviders)->unique('id');
            $department = Department::find($service->department_id);
            $departmentName = $department ? $department->name_ar : '';
            $cityName = $service->city ?? ($service->from_city ?? '');
            $settings = Settings::first();
            $template = $settings->whatsapp_offer_template ?? 'مرحبا يوجد عميل يحتاج خدمة خاصة بقسم {department} علي موقع endak.net في مدينة {city} , قدم عرض الان';
            $serviceLink = route('show_myservice', $service->id);
            $message = str_replace(
                ['{department}', '{city}'],
                [$departmentName, $cityName],
                $template
            );
            // أضف لينك الخدمة أسفل الرسالة
            $message .= "\nرابط الخدمة: $serviceLink";
            // دالة لتوحيد تنسيق الأرقام
            function normalizePhone($phone, $defaultCode = '+966') {
                $phone = preg_replace('/[^0-9]/', '', $phone); // أرقام فقط
                if (strpos($phone, '966') === 0) {
                    return '+'.$phone;
                } elseif (strpos($phone, '05') === 0) {
                    return $defaultCode . substr($phone, 1);
                } elseif (strpos($phone, '5') === 0 && strlen($phone) == 9) {
                    return $defaultCode . $phone;
                } else {
                    return $defaultCode . $phone;
                }
            }
            $sentPhones = [];
            foreach ($providers as $provider) {
                $provider->notify(new \App\Notifications\CommentNotification([
                    'id' => $service->id,
                    'title' => 'طلب خدمة جديدة في قسم ' . $departmentName,
                    'body' => $message,
                    'url' => route('show_myservice', $service->id),
                ]));
                if ($provider->phone) {
                    $countryCode = $provider->countryObj ? $provider->countryObj->code : '+966';
                    $whatsappPhone = normalizePhone($provider->phone, $countryCode);
                    if (!in_array($whatsappPhone, $sentPhones)) {
                        $sender = \App\Models\WhatsappSender::inRandomOrder()->first();
                        if ($sender) {
                            SendWhatsappMessageJob::dispatch($whatsappPhone, $message, $sender->number, $sender->token, $sender->instance_id)
                                ->delay(now()->addSeconds(rand(1, 3600)));
                        }
                        $sentPhones[] = $whatsappPhone;
                    }
                }
            }
            // إرسال رسالة واتساب للأرقام المرتبطة بالجدول WhatsappRecipients بدون تكرار
            $recipients = [];
            if ($service->sub_department_id) {
                $recipients = \App\Models\WhatsappRecipients::where('department_id', $service->sub_department_id)->pluck('number')->toArray();
            }
            $mainRecipients = \App\Models\WhatsappRecipients::where('department_id', $service->department_id)->pluck('number')->toArray();
            $recipients = array_unique(array_merge($recipients, $mainRecipients));
            $sender = \App\Models\WhatsappSender::inRandomOrder()->first();
            foreach ($recipients as $number) {
                $normalized = normalizePhone($number);
                if ($sender && !in_array($normalized, $sentPhones)) {
                    SendWhatsappMessageJob::dispatch($normalized, $message, $sender->number, $sender->token, $sender->instance_id)
                        ->delay(now()->addSeconds(rand(1, 3600)));
                    $sentPhones[] = $normalized;
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
     * Helper method to build validation rules for custom fields
     */
    private function buildCustomFieldValidationRules($departmentFields, $request)
    {
        $validationRules = [];

        foreach ($departmentFields as $field) {
            // Skip invalid fields
            if (empty($field->name) || empty($field->type)) {
                continue;
            }

            $rule = [];
            if ($field->is_required) {
                $rule[] = 'required';
            } else {
                $rule[] = 'nullable';
            }

            switch ($field->type) {
                case 'number':
                    $rule[] = 'numeric';
                    break;
                case 'date':
                    $rule[] = 'date';
                    break;
                case 'checkbox':
                    $rule[] = 'boolean';
                    break;
                case 'image':
                    $rule[] = 'nullable';
                    $rule[] = 'array';
                    $rule[] = 'max:10';
                    break;
                case 'images[]':
                    $rule[] = 'nullable';
                    $rule[] = 'array';
                    $rule[] = 'max:10';
                    break;
                case 'imagess':
                    $rule[] = 'nullable';
                    $rule[] = 'array';
                    $rule[] = 'max:10';
                    break;
                case 'select':
                    if (is_array($field->options) && count($field->options)) {
                        $rule[] = 'in:' . implode(',', $field->options);
                    }
                    break;
                case 'time':
                    $rule[] = 'date_format:H:i';
                    break;
                case 'textarea':
                case 'text':
                default:
                    $rule[] = 'string';
                    break;
            }

            // إضافة قواعد التحقق للحقول المجمعة
            if ($field->input_group) {
                // للحقول المجمعة القابلة للتكرار
                if ($field->is_repeatable) {
                    $validationRules["custom_fields.{$field->input_group}"] = 'array';
                    $validationRules["custom_fields.{$field->input_group}.*"] = 'array';
                    if ($field->type === 'image' || $field->type === 'images[]' || $field->type === 'imagess') {
                        $validationRules["custom_fields.{$field->input_group}.*.{$field->name}"] = 'nullable|array';
                        $validationRules["custom_fields.{$field->input_group}.*.{$field->name}.*"] = 'nullable|file|image|max:5120';
                    } else {
                        $validationRules["custom_fields.{$field->input_group}.*.{$field->name}"] = implode('|', $rule);
                    }
                } else {
                    // للحقول المجمعة غير القابلة للتكرار
                    $validationRules["custom_fields.{$field->input_group}"] = 'array';
                    $validationRules["custom_fields.{$field->input_group}.0"] = 'array';
                    if ($field->type === 'image' || $field->type === 'images[]' || $field->type === 'imagess') {
                        $validationRules["custom_fields.{$field->input_group}.0.{$field->name}"] = 'nullable|array';
                        $validationRules["custom_fields.{$field->input_group}.0.{$field->name}.*"] = 'nullable|file|image|max:5120';
                    } else {
                        $validationRules["custom_fields.{$field->input_group}.0.{$field->name}"] = implode('|', $rule);
                    }
                }
            } else {
                // أضف قاعدة التحقق للحقول العادية
                if ($field->type === 'image' || $field->type === 'images[]' || $field->type === 'imagess') {
                    $validationRules["custom_fields.{$field->name}"] = 'nullable|array';
                    $validationRules["custom_fields.{$field->name}.*"] = 'nullable|file|image|max:5120';
                } else {
                    $validationRules["custom_fields.{$field->name}"] = implode('|', $rule);
                }
            }
        }

        return $validationRules;
    }

    /**
     * Helper method to check and clean up problematic fields
     */
    private function checkAndCleanFields($departmentId)
    {
        $problematicFields = \App\Models\DepartmentField::where('department_id', $departmentId)
            ->where(function($query) {
                $query->where('name', 'like', '%imagess%')
                      ->orWhere('name', 'like', '%images%')
                      ->orWhere('type', 'image');
            })
            ->get();

        if ($problematicFields->count() > 0) {
            Log::warning('Found potentially problematic fields:', [
                'department_id' => $departmentId,
                'fields' => $problematicFields->map(function($field) {
                    return [
                        'id' => $field->id,
                        'name' => $field->name,
                        'type' => $field->type,
                        'name_ar' => $field->name_ar,
                        'name_en' => $field->name_en
                    ];
                })
            ]);
        }

        return $problematicFields;
    }

    /**
     * Custom validation method for image fields
     */
    private function validateImageField($field, $value)
    {
        if ($field->type === 'images[]') {
            if (!is_array($value)) {
                return false;
            }

            foreach ($value as $file) {
                if (!$file || !$file->isValid() || !in_array($file->getMimeType(), ['image/jpeg', 'image/png', 'image/gif', 'image/webp'])) {
                    return false;
                }
            }
            return true;
        } elseif ($field->type === 'image') {
            return $value && $value->isValid() && in_array($value->getMimeType(), ['image/jpeg', 'image/png', 'image/gif', 'image/webp']);
        }

        return true;
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id, Request $request)
    {
        $departments = Department::with('sub_departments')->find($id);
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
                // التحقق من وجود أقسام فرعية
                $hasSubDepartments = $departments->sub_departments && $departments->sub_departments->count() > 0;
                if ($hasSubDepartments && !$request->has('sub_department_id')) {
                    // إذا كان هناك أقسام فرعية ولم يتم تحديد قسم فرعي، اعرض صفحة الأقسام الفرعية
                    return view('front_office.departments.sub_departments', [
                        'department' => $departments,
                        'cities' => $cities,
                    ]);
                }
                // إذا تم اختيار قسم فرعي، أضفه للبيانات المرسلة للصفحة
                $selectedSubDepartmentId = null;
                if ($request->has('sub_department_id')) {
                    $selectedSubDepartmentId = $request->get('sub_department_id');
                    // إذا تم اختيار قسم فرعي، اعرض صفحة طلب الخدمة مع تحديد القسم الفرعي
                    return view('front_office.departments.show', [
                        'department' => $departments,
                        'cities' => $cities,
                        'services' => \App\Models\Services::where('department_id', $departments->id)->latest()->paginate(9),
                        'selectedSubDepartmentId' => $selectedSubDepartmentId,
                    ]);
                }
                // عرض صفحة عرض عامة لأي قسم جديد مع الحقول المخصصة
                return view('front_office.departments.show', [
                    'department' => $departments,
                    'cities' => $cities,
                    'services' => \App\Models\Services::where('department_id', $departments->id)->latest()->paginate(9),
                    'selectedSubDepartmentId' => $selectedSubDepartmentId,
                ]);
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

            $service = Services::with(['department.fields'])->findOrFail($id);
            $cities = \App\Models\Governements::all();
            $groupedFields = $service->department && $service->department->fields ? $service->department->fields->groupBy('input_group') : collect();
            return view('front_office.services.edit', compact('service', 'cities', 'groupedFields'));
        }
    }

    public function show_services($id, Request $request)
    {
        $service = Services::findOrFail($id); // تأكد من التعريف دائمًا
        $user = auth()->user();

        if (auth()->user()) {

            $form_city = Governements::where('id', $service->from_city)->first();
            $to_city = Governements::where('id', $service->to_city)->first();
        } else {
            $form_city = null;
            $to_city = null;
        }
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
            $service = \App\Models\Services::with(['department.sub_departments', 'department.fields'])->findOrFail($id);

            // التحقق من وجود أقسام فرعية
            if ($service->department && $service->department->sub_departments && $service->department->sub_departments->count() > 0) {
                // إذا تم تحديد قسم فرعي، اعرض الصفحة العادية
                if ($request->has('sub_department_id')) {
                    $subDepartmentId = $request->get('sub_department_id');
                    $service->selected_sub_department_id = $subDepartmentId;
                    return view('front_office.services.show', compact('service'));
                }

                // إذا لم يتم تحديد قسم فرعي، اعرض صفحة الأقسام الفرعية
                return view('front_office.services.show', compact('service'));
            }

            // إذا لم يكن هناك أقسام فرعية، اعرض الصفحة العادية
            return view('front_office.services.show', compact('service'));
        }
    }

    /**
     * عرض تفاصيل خدمة مع جميع الحقول المخصصة والصور
     */
    public function showService($id, Request $request)
    {
        $service = \App\Models\Services::with(['department.sub_departments', 'department.fields'])->findOrFail($id);
        $user = auth()->user();
        $is_add = null;
        // if ($user) {
        //     $is_add = $service->comments()->where('user_id', $user->id)->exists();
        // }
        $hasSubDepartments = $service->department && $service->department->sub_departments && $service->department->sub_departments->count() > 0;
        if ($hasSubDepartments && !$request->has('sub_department_id')) {
            // اعرض صفحة الأقسام الفرعية فقط
            return view('front_office.services.sub_departments', compact('service'));
        }
        // إذا تم اختيار قسم فرعي أو لا يوجد أقسام فرعية
        if ($hasSubDepartments && $request->has('sub_department_id')) {
            $service->selected_sub_department_id = $request->get('sub_department_id');
        }
        return view('front_office.services.show', compact('service', 'is_add', 'hasSubDepartments'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // تحقق من تسجيل الدخول
        if (!auth()->check()) {
            return redirect()->route('login-page')->with('error', 'يجب تسجيل الدخول أولاً لتعديل الخدمة.');
        }

// dd($request->all());

        // جلب الحقول الديناميكية للقسم
        $departmentFields = [];
        if ($request->has('department_id')) {
            $departmentFields = \App\Models\DepartmentField::where('department_id', $request->department_id)->get();
        }

        $validationRules = [
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
        ];

        // بناء قواعد التحقق الديناميكية
        $customFieldRules = $this->buildCustomFieldValidationRules($departmentFields, $request);
        $validationRules = array_merge($validationRules, $customFieldRules);
        // تمرير أي حقل من custom_fields إلى الجذر إذا كان مطلوبًا في الفاليديشن
        foreach (array_keys($validationRules) as $field) {
            if (!$request->has($field) && $request->has("custom_fields.$field")) {
                $request->merge([
                    $field => $request->input("custom_fields.$field")
                ]);
            }
        }

        // Debug: Log validation rules for troubleshooting
        Log::info('Validation rules for service update:', [
            'service_id' => $id,
            'custom_field_rules' => $customFieldRules,
            'all_rules' => $validationRules
        ]);

        // Debug: Log the actual request data for image fields
        if ($request->has('custom_fields')) {
            Log::info('Custom fields data for update:', [
                'custom_fields' => $request->input('custom_fields'),
                'files' => $request->allFiles()
            ]);
        }

        try {
            $data = $request->validate($validationRules);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Service update validation failed:', [
                'service_id' => $id,
                'errors' => $e->errors(),
                'request_data' => $request->all(),
                'validation_rules' => $validationRules
            ]);
            throw $e;
        }

        // تمرير الحقول المطلوبة من custom_fields إلى الطلب الرئيسي
        foreach (array_keys($data) as $field) {
            if (!$request->has($field) && $request->has('custom_fields.' . $field)) {
                $request->merge([
                    $field => $request->input('custom_fields.' . $field)
                ]);
            }
        }

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
        $department = $service->department()->with('fields')->first();
        $customFields = $service->custom_fields ?? [];
        $imagesToSave = []; // Array to store images for later saving to GeneralImage

        if ($department && $department->fields) {
            $grouped = $department->fields->groupBy('input_group');
            foreach ($department->fields as $field) {
                $fieldName = $field->name;
                $group = $field->input_group;
                // إذا كانت المجموعة قابلة للتكرار
                if ($group && $field->is_repeatable && isset($request->custom_fields[$group]) && is_array($request->custom_fields[$group])) {
                    // خذ فقط أول 10 عناصر
                    $instances = array_slice($request->custom_fields[$group], 0, 10);
                    $customFields[$group] = [];
                    foreach ($instances as $instanceIdx => $instance) {
                        $instanceData = [];
                        foreach ($grouped[$group] as $groupField) {
                            $fname = $groupField->name;
                            if ($groupField->type === 'image' || $groupField->type === 'images[]' || $groupField->type === 'imagess') {
                                $files = $request->file("custom_fields.$group.$instanceIdx.$fname");
                                if ($files && !empty($files)) {
                                    $paths = [];
                                    if (is_array($files)) {
                                        foreach ($files as $file) {
                                            if ($file && $file->isValid()) {
                                                $path = $file->store('services/images', 'public');
                                                $paths[] = $path;
                                                $imagesToSave[] = $path; // Store for GeneralImage
                                            }
                                        }
                                    } else {
                                        if ($files->isValid()) {
                                            $path = $files->store('services/images', 'public');
                                            $paths[] = $path;
                                            $imagesToSave[] = $path; // Store for GeneralImage
                                        }
                                    }
                                    $instanceData[$fname] = $paths;
                                } else {
                                    $instanceData[$fname] = [];
                                }
                            } elseif ($groupField->type === 'checkbox') {
                                $instanceData[$fname] = isset($instance[$fname]) ? 1 : 0;
                            } else {
                                $instanceData[$fname] = $instance[$fname] ?? null;
                            }
                        }
                        $customFields[$group][] = $instanceData;
                    }
                } elseif ($group && !$field->is_repeatable) {
                    // الحقول المجمعة غير القابلة للتكرار
                    if ($field->type === 'image' || $field->type === 'images[]' || $field->type === 'imagess') {
                        $files = $request->file("custom_fields.$fieldName");
                        if ($files && !empty($files)) {
                            $paths = [];
                            if (is_array($files)) {
                                foreach ($files as $file) {
                                    if ($file && $file->isValid()) {
                                        $path = $file->store('services/images', 'public');
                                        $paths[] = $path;
                                        $imagesToSave[] = $path; // Store for GeneralImage
                                    }
                                }
                                $customFields[$fieldName] = $paths;
                            } else {
                                if ($files->isValid()) {
                                    $path = $files->store('services/images', 'public');
                                    $customFields[$fieldName] = [$path];
                                    $imagesToSave[] = $path; // Store for GeneralImage
                                }
                            }
                        } else {
                            $customFields[$fieldName] = [];
                        }
                    } elseif ($field->type === 'checkbox') {
                        $customFields[$fieldName] = $request->has("custom_fields.$fieldName") ? 1 : 0;
                    } else {
                        $customFields[$fieldName] = $request->input("custom_fields.$fieldName");
                    }
                } elseif (!$group) {
                    // الحقول العادية (غير مجمعة)
                    if ($field->type === 'image' || $field->type === 'images[]' || $field->type === 'imagess') {
                        $files = $request->file("custom_fields.$fieldName");
                        if ($files && !empty($files)) {
                            $paths = [];
                            if (is_array($files)) {
                                foreach ($files as $file) {
                                    if ($file && $file->isValid()) {
                                        $path = $file->store('services/images', 'public');
                                        $paths[] = $path;
                                        $imagesToSave[] = $path; // Store for GeneralImage
                                    }
                                }
                                $customFields[$fieldName] = $paths;
                            } else {
                                if ($files->isValid()) {
                                    $path = $files->store('services/images', 'public');
                                    $customFields[$fieldName] = [$path];
                                    $imagesToSave[] = $path; // Store for GeneralImage
                                }
                            }
                        } else {
                            $customFields[$fieldName] = [];
                        }
                    } elseif ($field->type === 'checkbox') {
                        $customFields[$fieldName] = $request->has("custom_fields.$fieldName") ? 1 : 0;
                    } else {
                        $customFields[$fieldName] = $request->input("custom_fields.$fieldName");
                    }
                }
            }
        }
        $data['custom_fields'] = $customFields;

        $service->update($data);

        // Save images to GeneralImage model
        foreach ($imagesToSave as $imagePath) {
            $image = new GeneralImage([
                'path' => $imagePath,
            ]);
            $service->images()->save($image);
        }


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

        return redirect()->route('home')->with('success', 'تم تحديث الخدمة بنجاح');
    }

    /**
     * عرض جميع الخدمات المطلوبة للجميع
     */
    public function allServices(Request $request)
    {
        $departments = \App\Models\Department::where('department_id', 0)->where('status', 1)->get();
        $cities = \App\Models\Services::whereNotNull('city')->distinct()->pluck('city');
        $query = \App\Models\Services::where('status', 'open');
        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }
        if ($request->filled('city')) {
            $query->where('city', $request->city);
        }
        $services = $query->latest()->paginate(12);
        return view('front_office.services.all_services', compact('services', 'departments', 'cities'));
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
