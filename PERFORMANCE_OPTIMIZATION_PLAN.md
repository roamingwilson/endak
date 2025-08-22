# خطة تحسين أداء الموقع

## المشاكل الحالية:
- **First Contentful Paint**: 5.3s (بطيء جداً)
- **Largest Contentful Paint**: 6.3s (بطيء جداً)
- **Speed Index**: 5.3s (بطيء جداً)
- **Cumulative Layout Shift**: 0.538 (مرتفع)

## المشاكل المحددة:

### 1. تحميل CSS و JavaScript
- تحميل ملفات CSS متعددة من CDN
- تحميل jQuery و Select2 من CDN
- تحميل Font Awesome من CDN
- ملفات CSS كبيرة (styles.css = 170KB)

### 2. تحميل الصور
- عدم وجود تحسين للصور
- عدم وجود lazy loading

### 3. تحميل الخطوط
- تحميل خطوط كبيرة من CDN

### 4. JavaScript غير محسن
- كود JavaScript كبير في الـ head
- عدم استخدام defer/async

## خطة التحسين:

### المرحلة الأولى: تحسين CSS و JavaScript

#### 1. دمج ملفات CSS
```bash
# دمج ملفات CSS المحلية
cat public/home/assets/css/styles.css public/home/assets/css/icons.css > public/home/assets/css/combined.css
```

#### 2. تحسين تحميل JavaScript
- نقل JavaScript إلى نهاية الصفحة
- استخدام defer/async
- دمج ملفات JavaScript

#### 3. تحسين CDN
- استخدام CDN محلي أو أقرب
- تحميل الملفات الضرورية فقط

### المرحلة الثانية: تحسين الصور

#### 1. ضغط الصور
```bash
# تثبيت أداة ضغط الصور
npm install -g imagemin-cli
```

#### 2. إضافة lazy loading
```html
<img src="image.jpg" loading="lazy" alt="description">
```

### المرحلة الثالثة: تحسين الخطوط

#### 1. استخدام خطوط محلية
- تحميل الخطوط محلياً
- استخدام font-display: swap

### المرحلة الرابعة: تحسين قاعدة البيانات

#### 1. تحسين الاستعلامات
- إضافة indexes
- تحسين العلاقات

## التنفيذ:

### 1. إنشاء ملف CSS محسن
### 2. إنشاء ملف JavaScript محسن
### 3. تحسين تحميل الصور
### 4. تحسين الخطوط
### 5. تحسين قاعدة البيانات

## الأهداف:
- **First Contentful Paint**: < 2s
- **Largest Contentful Paint**: < 2.5s
- **Speed Index**: < 2s
- **Cumulative Layout Shift**: < 0.1
