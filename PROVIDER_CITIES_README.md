# Provider City Management - إدارة مدن المزودين

## نظرة عامة
تم تطوير ميزة تسمح لمزودي الخدمات باختيار المدن التي يمكنهم العمل فيها، وفلترة الخدمات والطلبات بناءً على هذه المدن المختارة.

## الميزات المضافة

### 1. إدارة مدن المزودين
- **صفحة إدارة المدن**: صفحة مخصصة للمزودين لاختيار المدن التي يعملون فيها
- **واجهة سهلة الاستخدام**: اختيار متعدد للمدن مع إمكانية الإضافة والحذف
- **حفظ تلقائي**: حفظ التغييرات فوراً في قاعدة البيانات

### 2. فلترة الخدمات
- **فلترة تلقائية**: عرض الخدمات في المدن المختارة + المدينة الأصلية للمزودين
- **تطبيق شامل**: الفلترة تطبق على جميع صفحات عرض الخدمات
- **مرونة**: إذا لم يختر المزود أي مدن، تظهر له الخدمات في مدينته الأصلية فقط

### 3. فلترة الإشعارات
- **إشعارات مستهدفة**: إرسال الإشعارات للمزودين في المدن المختارة فقط
- **توفير الوقت**: عدم إزعاج المزودين بطلبات خارج نطاق عملهم

## الملفات المضافة

### 1. قاعدة البيانات
```php
// database/migrations/2025_08_14_110952_create_provider_cities_table.php
Schema::create('provider_cities', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
    $table->foreignId('governement_id')->constrained('governements')->cascadeOnDelete();
    $table->timestamps();
    $table->unique(['user_id', 'governement_id']);
});
```

### 2. النماذج (Models)
```php
// app/Models/ProviderCity.php
class ProviderCity extends Model {
    protected $fillable = ['user_id', 'governement_id'];
    
    public function user() {
        return $this->belongsTo(User::class);
    }
    
    public function governement() {
        return $this->belongsTo(Governements::class);
    }
}
```

### 3. التحكم (Controllers)
```php
// app/Http/Controllers/ProviderCityController.php
class ProviderCityController extends Controller {
    public function index() // عرض صفحة إدارة المدن
    public function update(Request $request) // حفظ المدن المختارة
    public function getProviderCities() // API لجلب المدن المختارة
}
```

### 4. الواجهات (Views)
```blade
// resources/views/front_office/provider/cities.blade.php
// صفحة إدارة المدن للمزودين
```

## الملفات المعدلة

### 1. نموذج المستخدم
```php
// app/Models/User.php
public function providerCities() {
    return $this->hasMany(ProviderCity::class);
}

public function getServiceCities() {
    return $this->providerCities()->with('governement')->get()->pluck('governement');
}

public function canWorkInCity($cityId) {
    return $this->providerCities()->where('governement_id', $cityId)->exists();
}

public function getAllWorkCities() {
    $selectedCities = $this->getServiceCities();
    $originalCity = $this->governementObj;
    
    $allCities = $selectedCities;
    
    // إضافة المدينة الأصلية إذا لم تكن موجودة في المدن المختارة
    if ($originalCity && !$selectedCities->contains('id', $originalCity->id)) {
        $allCities->push($originalCity);
    }
    
    return $allCities;
}

public function getAllWorkCityNames() {
    $allCities = $this->getAllWorkCities();
    $cityNames = $allCities->pluck('name_ar')->toArray();
    $cityNamesEn = $allCities->pluck('name_en')->toArray();
    
    return array_merge($cityNames, $cityNamesEn);
}
```

### 2. تحكم الخدمات
```php
// app/Http/Controllers/ServiceController.php
// تم إضافة فلترة المدن في:
- index() // صفحة خدمات المستخدم
- allServices() // صفحة جميع الخدمات
- store() // عرض الخدمات في صفحات الأقسام
```

### 3. تحكم الأقسام
```php
// app/Http/Controllers/DepartmentsController.php
// تم إضافة فلترة المدن في:
- show() // عرض الخدمات في صفحة القسم
```

### 4. تحكم الطلبات العامة
```php
// app/Http/Controllers/GeneralOrderController.php
// تم إضافة فلترة الإشعارات حسب المدن المختارة
```

### 5. الواجهات
```blade
// resources/views/front_office/profile/show.blade.php
// إضافة رابط إدارة المدن وعرض المدن المختارة

// resources/views/front_office/departments/show.blade.php
// إضافة رسائل توضيحية عن الفلترة

// resources/views/layouts/home.blade.php
// تحديث روابط الخدمات
```

### 6. المسارات
```php
// routes/web.php
Route::get('/provider/cities', [ProviderCityController::class, 'index'])->name('provider.cities');
Route::post('/provider/cities', [ProviderCityController::class, 'update'])->name('provider.cities.update');
Route::get('/provider/cities/get', [ProviderCityController::class, 'getProviderCities'])->name('provider.cities.get');
```

## كيفية الاستخدام

### للمزودين:
1. **تسجيل الدخول** بحساب مزود الخدمة
2. **الذهاب للملف الشخصي** والنقر على "إدارة المدن"
3. **اختيار المدن** التي يمكن العمل فيها
4. **حفظ التغييرات** - ستظهر الخدمات في المدن المختارة فقط

### للمطورين:
```php
// التحقق من مدن المزود
$user = auth()->user();
if ($user->role_id == 3 && $user->providerCities()->count() > 0) {
    $providerCities = $user->getServiceCities();
    $cityNames = $providerCities->pluck('name_ar')->toArray();
    // تطبيق الفلترة
}
```

## الفلترة المطبقة

### في جميع صفحات الخدمات:
```php
if ($user && $user->role_id == 3) {
    $allCityNames = $user->getAllWorkCityNames();
    
    $query->where(function($q) use ($allCityNames) {
        $q->whereIn('from_city', $allCityNames)
          ->orWhereIn('city', $allCityNames);
    });
}
```

### في الإشعارات:
```php
foreach ($allProviders as $provider) {
    if ($provider->providerCities()->count() == 0 || 
        ($serviceCity && in_array($serviceCity, $provider->getAllWorkCityNames()))) {
        $providers->push($provider);
    }
}
```

## المزايا

1. **تحسين الأداء**: تقليل عدد الخدمات المعروضة للمزودين
2. **تجربة مستخدم أفضل**: عرض الخدمات ذات الصلة فقط
3. **توفير الوقت**: عدم إزعاج المزودين بطلبات خارج نطاقهم
4. **مرونة**: إمكانية تغيير المدن في أي وقت
5. **شمولية**: تطبيق الفلترة على جميع أجزاء التطبيق
6. **شمولية المدينة الأصلية**: تضمين المدينة الأصلية للمزود تلقائياً

## التحديث الجديد: تضمين المدينة الأصلية

### الميزة الجديدة:
- **تضمين تلقائي**: المدينة الأصلية للمزود تضاف تلقائياً إلى قائمة المدن المعروضة
- **تمييز بصري**: المدينة الأصلية تظهر بلون مختلف (أخضر) مع علامة "مدينتي"
- **فلترة محسنة**: الخدمات تظهر في المدن المختارة + المدينة الأصلية

### الدوال الجديدة:
```php
// الحصول على جميع المدن (المختارة + المدينة الأصلية)
$user->getAllWorkCities();

// الحصول على أسماء جميع المدن
$user->getAllWorkCityNames();
```

### التطبيق:
- **الفلترة**: تطبق على جميع صفحات الخدمات
- **الإشعارات**: ترسل للمزودين في المدن المختارة + مدينتهم الأصلية
- **الواجهة**: عرض واضح للمدن مع تمييز المدينة الأصلية

## التحسينات المستقبلية

1. **إحصائيات المدن**: عرض عدد الخدمات في كل مدينة
2. **فلترة متقدمة**: إضافة فلترة حسب الحي أو المنطقة
3. **إشعارات ذكية**: إشعارات عند توفر خدمات في المدن المختارة
4. **تحليلات**: تقارير عن أداء المزود في كل مدينة
5. **API محسن**: واجهات برمجة للتطبيقات الخارجية

## ملاحظات تقنية

- **الأداء**: تم استخدام العلاقات المباشرة لتحسين الأداء
- **الأمان**: التحقق من صلاحيات المستخدم قبل عرض البيانات
- **التوافق**: العمل مع النظام الحالي بدون تعطيل
- **قابلية التوسع**: إمكانية إضافة ميزات جديدة بسهولة

## استكشاف الأخطاء

### مشاكل شائعة:
1. **عدم ظهور الخدمات**: تأكد من اختيار المدن في صفحة إدارة المدن
2. **أخطاء في الفلترة**: تأكد من تطابق أسماء المدن في قاعدة البيانات
3. **مشاكل في الإشعارات**: تحقق من إعدادات الإشعارات في النظام

### حلول:
1. **مسح الكاش**: `php artisan cache:clear`
2. **إعادة تشغيل الخادم**: `php artisan serve`
3. **فحص قاعدة البيانات**: التأكد من صحة البيانات

