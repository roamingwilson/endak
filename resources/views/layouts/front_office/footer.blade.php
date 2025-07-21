        <!-- Start::footer -->
        <footer class="footer bg-dark text-white mt-auto pt-5 pb-0 position-relative" style="background: linear-gradient(90deg, #ff9800 60%, #ffb74d 100%);">
            <div class="container">
                <div class="row gy-4 align-items-start">
                    <div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
                        <a href="#" class="d-inline-block mb-3"><img src="{{ $settings->image_url ?? '' }}" width="150px" height="50px" alt="logo"></a>
                        <ul class="list-unstyled mb-3 mt-3">
                            <li class="mb-2"><i class="bi bi-telephone me-2 text-white-50"></i> <span class="text-white">{{ $settings->phone ?? '' }}</span></li>
                            <li class="mb-2"><i class="bi bi-envelope-plus me-2 text-white-50"></i> <span class="text-white">{{ $settings->email ?? '' }}</span></li>
                            <li><i class="bi bi-geo-alt me-2 text-white-50"></i> <span class="text-white">{{ $settings->address ?? '' }}</span></li>
                        </ul>
                        <div class="d-flex align-items-center gap-2 mt-3">
                            <a href="{{ $settings->facebook ?? '' }}" class="btn btn-icon rounded-circle" style="background:#fff3e0;color:#ff9800;"><i class="bi bi-facebook"></i></a>
                            <a href="{{ $settings->linkedin ?? '' }}" class="btn btn-icon rounded-circle" style="background:#fff3e0;color:#ff9800;"><i class="bi bi-linkedin"></i></a>
                            <a href="{{ $settings->instagram ?? '' }}" class="btn btn-icon rounded-circle" style="background:#fff3e0;color:#ff9800;"><i class="bi bi-instagram"></i></a>
                            <a href="{{ $settings->twitter ?? '' }}" class="btn btn-icon rounded-circle" style="background:#fff3e0;color:#ff9800;"><i class="bi bi-twitter"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-6 mb-4 mb-lg-0">
                        <h6 class="fw-bold mb-3" style="color:#fffbe7;">{{ $lang == 'ar' ? 'روابط سريعة' : 'Quick Links' }}</h6>
                        <ul class="list-unstyled">
                            <li class="mb-2"><a href="{{ route('home') }}" class="footer-link" style="color:#fffbe7;opacity:0.85;">{{ $lang == 'ar' ? 'الرئيسية' : 'Home' }}</a></li>
                            <li class="mb-2"><a href="{{ route('departments') }}" class="footer-link" style="color:#fffbe7;opacity:0.85;">{{ $lang == 'ar' ? 'الأقسام' : 'Departments' }}</a></li>
                            <li class="mb-2"><a href="{{ route('login-page') }}" class="footer-link" style="color:#fffbe7;opacity:0.85;">{{ $lang == 'ar' ? 'تسجيل الدخول' : 'Login' }}</a></li>
                            <li><a href="{{ route('register-page') }}" class="footer-link" style="color:#fffbe7;opacity:0.85;">{{ $lang == 'ar' ? 'إنشاء حساب' : 'Register' }}</a></li>
                        </ul>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-4 mb-lg-0">
                        <h6 class="fw-bold mb-3" style="color:#fffbe7;">{{ $lang == 'ar' ? 'عن المنصة' : 'About' }}</h6>
                        <p class="mb-2" style="font-size:15px;color:#fffbe7;opacity:0.9;">{{ $settings->about ?? ($lang == 'ar' ? 'منصة عندك تربط العملاء بمزودي الخدمات والمنتجات في جميع المجالات.' : 'Endak platform connects customers with service and product providers in all fields.') }}</p>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-4 mb-lg-0">
                        <h6 class="fw-bold mb-3" style="color:#fffbe7;">{{ $lang == 'ar' ? 'تواصل معنا' : 'Contact Us' }}</h6>
                        <form action="#" method="post" class="d-flex flex-column gap-2">
                            <input type="email" class="form-control rounded-pill" style="background:#fffbe7;color:#ff9800;border:none;" placeholder="{{ $lang == 'ar' ? 'بريدك الإلكتروني' : 'Your Email' }}" required>
                            <textarea class="form-control rounded-3" rows="2" style="background:#fffbe7;color:#ff9800;border:none;" placeholder="{{ $lang == 'ar' ? 'رسالتك' : 'Your Message' }}" required></textarea>
                            <button type="submit" class="btn rounded-pill fw-bold" style="background:#ff9800;color:#fff;">{{ $lang == 'ar' ? 'إرسال' : 'Send' }}</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="py-3 border-top border-white-2 text-center mt-4" style="background:rgba(255,152,0,0.08);">
                <div class="container">
                    <span class="tx-14 op-8" style="color:#fffbe7;">
                        &copy; <span id="year"></span> - <?php $mytime = Carbon\Carbon::now(); echo $mytime->format('Y'); ?>
                        <a href="" class="text-white">Endak</a>
                        {{ $lang == 'ar' ? 'كل الحقوق محفوظة' : 'All Rights Reserved' }}
                        <span class="fa fa-heart" style="color:#ff9800;"></span>
                        {{ $lang == 'ar' ? 'تصميم' : 'Designed by' }}
                        <a href="{{ config('app.developer_name') }}" class="text-white">{{ config('app.developer_name') }}</a>
                    </span>
                </div>
            </div>
        </footer>
        <!-- End::footer -->


        </div>
