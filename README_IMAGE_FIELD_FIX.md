# إصلاح مشكلة حقل الصورة في الحقول المخصصة

## المشكلة
كانت تظهر رسالة خطأ عند محاولة رفع صورة في الحقول المخصصة:
```
The custom fields.image field must be a file.
The custom fields.image field must be an image
```

## سبب المشكلة
كان هناك خطأ في منطق التحقق من صحة البيانات في `ServiceController`. المشكلة كانت في:

1. **منطق خاطئ لتحديد ما إذا كان الحقل multiple أم لا**
2. **إضافة قواعد تحقق مكررة للحقول من نوع صورة**
3. **عدم التعامل الصحيح مع الحقول المجمعة**

## الإصلاحات المطبقة

### 1. إصلاح منطق تحديد الحقول المتعددة

**قبل الإصلاح:**
```php
$isMultiple = (isset($field->input_group) && !$field->input_group && isset($request->custom_fields[$field->name]) && is_array($request->custom_fields[$field->name]));
```

**بعد الإصلاح:**
```php
$fieldName = $field->name;
$isMultiple = false;

if ($request->has("custom_fields.$fieldName")) {
    $fieldValue = $request->input("custom_fields.$fieldName");
    if (is_array($fieldValue)) {
        $isMultiple = true;
    }
}
```

### 2. إصلاح إضافة قواعد التحقق

**قبل الإصلاح:**
```php
if ($field->type !== 'image' || !$isMultiple) {
    $validationRules["custom_fields.{$field->name}"] = implode('|', $rule);
}
```

**بعد الإصلاح:**
```php
if ($field->type === 'image') {
    if (!$isMultiple) {
        $validationRules["custom_fields.{$field->name}"] = implode('|', $rule);
    }
} else {
    $validationRules["custom_fields.{$field->name}"] = implode('|', $rule);
}
```

### 3. تحسين معالجة الصور

تم إضافة معالجة خاصة للصور في `DepartmentFieldController`:

```php
if ($fieldType === 'image' && $request->hasFile('value')) {
    $data['value'] = $request->file('value')->store('field_images', 'public');
}
```

## الملفات المحدثة

### 1. ServiceController.php
- ✅ إصلاح منطق التحقق من الحقول المتعددة
- ✅ إصلاح إضافة قواعد التحقق
- ✅ تحسين معالجة الصور

### 2. DepartmentFieldController.php
- ✅ إضافة معالجة خاصة للصور
- ✅ تحسين التحقق من صحة البيانات
- ✅ إضافة تسجيل الأخطاء

### 3. النماذج (Views)
- ✅ إضافة `enctype="multipart/form-data"`
- ✅ إضافة `accept="image/*"`
- ✅ تحسين عرض الأخطاء

## كيفية الاختبار

### 1. إنشاء حقل مخصص من نوع صورة:
1. انتقل إلى `/admin/departments/{id}/fields/create`
2. اختر نوع الحقل: "صورة"
3. املأ البيانات المطلوبة
4. اضغط "حفظ الحقل"

### 2. استخدام الحقل في الواجهة الأمامية:
1. انتقل إلى صفحة القسم
2. ابحث عن حقل الصورة المخصص
3. اضغط "اختيار ملف"
4. اختر صورة
5. اضغط "إرسال الطلب"

## أنواع الصور المدعومة
- JPEG
- PNG
- JPG
- GIF

## الحد الأقصى
- 2 ميجابايت لكل صورة في الحقول المخصصة
- 5 ميجابايت لكل صورة في طلبات الخدمة

## مجلدات التخزين
- الحقول المخصصة: `storage/app/public/field_images/`
- طلبات الخدمة: `storage/app/public/services/images/`

## استكشاف الأخطاء

### إذا ظهرت رسالة خطأ:
1. **تأكد من اختيار نوع الحقل "صورة"**
2. **تأكد من رفع ملف صورة صحيح**
3. **تأكد من أن حجم الصورة أقل من الحد الأقصى**
4. **تأكد من أن نوع الملف مدعوم**

### إذا لم تظهر الصورة:
1. **تأكد من وجود مجلد التخزين**
2. **قم بتشغيل `php artisan storage:link`**
3. **تأكد من صلاحيات المجلد**

## ملاحظات مهمة
- تم إصلاح المشكلة في كل من دالة `store` و `update`
- تم تحسين معالجة الأخطاء وإضافة تسجيل مفصل
- تم إضافة دعم للحقول المتعددة (multiple)
- تم تحسين واجهة المستخدم لعرض الأخطاء 
