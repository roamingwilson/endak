# نظام الوضع المظلم والوضع الفاتح

## نظرة عامة

تم إضافة نظام شامل للوضع المظلم والوضع الفاتح للمشروع. النظام يدعم كلاً من الواجهة الأمامية ولوحة التحكم.

## الميزات

### ✅ الميزات المنجزة

1. **تبديل تلقائي بين الأوضاع**
   - زر تبديل ثابت في الزاوية العلوية اليمنى
   - اختصار لوحة المفاتيح: `Ctrl/Cmd + T`
   - حفظ التفضيل في localStorage

2. **دعم كامل للواجهة الأمامية**
   - جميع الصفحات والمواد
   - نماذج التسجيل والتسجيل الدخول
   - صفحات الخدمات والأقسام

3. **دعم كامل للوحة التحكم**
   - جميع صفحات الإدارة
   - الجداول والنماذج
   - القوائم الجانبية والتنقل

4. **تصميم متجاوب**
   - دعم الأجهزة المحمولة
   - دعم RTL (العربية)
   - انتقالات سلسة

5. **إشعارات تفاعلية**
   - إشعار عند تغيير الوضع
   - رسائل باللغة العربية
   - أيقونات معبرة

## الملفات المضافة

### CSS Files
- `public/css/dark-mode.css` - الأنماط الأساسية للوضع المظلم
- `public/css/dashboard-dark-mode.css` - أنماط خاصة بلوحة التحكم

### JavaScript Files
- `public/js/theme-switcher.js` - منطق التحكم في تبديل الأوضاع

### Layout Updates
- `resources/views/layouts/home.blade.php` - تحديث التخطيط الرئيسي
- `resources/views/layouts/dashboard/dashboard.blade.php` - تحديث تخطيط لوحة التحكم

## كيفية الاستخدام

### للمستخدمين

1. **التبديل اليدوي**: انقر على زر التبديل في الزاوية العلوية اليمنى
2. **الاختصار**: استخدم `Ctrl + T` (Windows/Linux) أو `Cmd + T` (Mac)
3. **الحفظ التلقائي**: يتم حفظ تفضيلك تلقائياً

### للمطورين

#### إضافة دعم الوضع المظلم لصفحة جديدة

1. **إضافة CSS Variables**:
```css
.your-component {
    background-color: var(--light-bg-primary);
    color: var(--light-text-primary);
}

body.dark-theme .your-component {
    background-color: var(--dark-bg-primary);
    color: var(--dark-text-primary);
}
```

2. **استخدام JavaScript**:
```javascript
// الحصول على الوضع الحالي
const currentTheme = window.themeSwitcher.getCurrentTheme();

// تعيين وضع معين
window.themeSwitcher.setTheme('dark');

// الاستماع لتغيير الوضع
document.addEventListener('themeChanged', (e) => {
    console.log('Theme changed to:', e.detail.theme);
});
```

#### إضافة زر تبديل مخصص

```html
<button onclick="window.themeSwitcher.toggleTheme()">
    تبديل الوضع
</button>
```

## متغيرات CSS المتاحة

### ألوان الخلفية
- `--light-bg-primary` / `--dark-bg-primary`
- `--light-bg-secondary` / `--dark-bg-secondary`
- `--light-bg-tertiary` / `--dark-bg-tertiary`

### ألوان النصوص
- `--light-text-primary` / `--dark-text-primary`
- `--light-text-secondary` / `--dark-text-secondary`
- `--light-text-muted` / `--dark-text-muted`

### ألوان الحدود
- `--light-border` / `--dark-border`
- `--light-border-focus` / `--dark-border-focus`

### ألوان البطاقات والحقول
- `--light-card-bg` / `--dark-card-bg`
- `--light-input-bg` / `--dark-input-bg`

### ألوان الأزرار
- `--light-btn-primary` / `--dark-btn-primary`
- `--light-btn-secondary` / `--dark-btn-secondary`

## التوافق

### المتصفحات المدعومة
- ✅ Chrome 60+
- ✅ Firefox 55+
- ✅ Safari 12+
- ✅ Edge 79+

### الميزات المدعومة
- ✅ CSS Variables
- ✅ localStorage
- ✅ Custom Events
- ✅ CSS Transitions

## استكشاف الأخطاء

### مشاكل شائعة

1. **الوضع لا يتغير**
   - تأكد من تحميل ملف `theme-switcher.js`
   - تحقق من وجود أخطاء في Console

2. **الألوان لا تتطبق**
   - تأكد من استخدام متغيرات CSS
   - تحقق من عدم وجود أنماط متضاربة

3. **الزر لا يظهر**
   - تأكد من وجود Font Awesome
   - تحقق من CSS الخاص بالزر

### إصلاحات سريعة

```javascript
// إعادة تهيئة النظام
if (window.themeSwitcher) {
    window.themeSwitcher.init();
}

// تعيين الوضع يدوياً
document.body.classList.remove('light-theme', 'dark-theme');
document.body.classList.add('dark-theme');
```

## التطوير المستقبلي

### ميزات مقترحة
- [ ] دعم الألوان المخصصة
- [ ] دعم الوضع التلقائي (حسب النظام)
- [ ] دعم المزيد من الألوان
- [ ] حفظ التفضيل في قاعدة البيانات

### تحسينات مقترحة
- [ ] تحسين الأداء
- [ ] دعم المزيد من المكونات
- [ ] تحسين الانتقالات
- [ ] دعم المزيد من الاختصارات

## المساهمة

لإضافة ميزات جديدة أو إصلاح مشاكل:

1. تأكد من اختبار التغييرات في كلا الوضعين
2. استخدم متغيرات CSS الموجودة
3. أضف تعليقات توضيحية
4. اختبر التوافق مع المتصفحات المختلفة

## الترخيص

هذا النظام جزء من المشروع الرئيسي ويخضع لنفس شروط الترخيص.
