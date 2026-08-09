<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title','AUR Beauty Lounge & Atelier')</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500&family=Poppins:wght@300;400;500;600;700&family=Cairo:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<script src="https://unpkg.com/lucide@latest"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
<script src="https://unpkg.com/lucide@latest"></script>
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@vite(['resources/css/app.css', 'resources/js/app.js'])

{{-- <link rel="stylesheet" href="{{ asset('css/app.css') }}"> --}}
<style>

</style>
</head>
<body>

<!-- Page Loader -->
<div id="loader">
  <img src="images/logo.jpeg" alt="AUR Logo">
<div class="loader-text"> AUR Beauty Lounge </div>
</div>

<!-- Custom Cursor -->
<div class="cursor-dot"></div>
<div class="cursor-ring"></div>

<!-- Lightbox -->
<div class="lightbox" id="lightbox">
  <button class="lightbox-close" id="lightboxClose" onclick="closeLightbox()">✕</button>
  <img src="" alt="" id="lightboxImg">
  <div class="lightbox-caption" id="lightboxCaption"></div>
  <button class="lightbox-nav lightbox-prev" id="lightboxPrev">‹</button>
  <button class="lightbox-nav lightbox-next" id="lightboxNext">›</button>
</div>

<!-- Toast -->
<div class="toast" id="toast"></div>
@if(session('success'))
  <div style="position:fixed;top:20px;left:50%;transform:translateX(-50%);z-index:9999;background:rgba(34,139,34,0.95);color:#fff;padding:14px 24px;border-radius:999px;box-shadow:0 20px 40px rgba(0,0,0,0.2);font-weight:700;max-width:90%;text-align:center;">
    {{ session('success') }}
  </div>
@endif

<!-- Mobile Menu -->
<div class="mobile-menu" id="mobileMenu">
  <a href="#services" onclick="closeMobile()">الخدمات</a>
  <a href="#bridal" onclick="closeMobile()">العرائس</a>
  <a href="#gallery" onclick="closeMobile()">معرض الأعمال</a>
  <a href="#booking" onclick="closeMobile()">الباقات</a>
  <a href="#contact" onclick="closeMobile()">تواصل معنا</a>
  @auth
    <a href="{{ route('admin.dashboard') }}" onclick="closeMobile()">login</a>
  @else
    <a href="{{ route('login') }}" onclick="closeMobile()">login</a>
  @endauth
</div>

<!-- Navbar -->
<nav id="navbar">
  <div class="nav-container">
    <a href="#hero" class="nav-logo">
      <div class="logo-circle">
        <img src="images/logo.jpeg" alt="AUR">
      </div>

      <div class="logo-text">
        <p class="logo-title">AUR</p>
        <p class="logo-subtitle">Beauty Lounge &amp; Atelier</p>
      </div>
    </a>

    <ul class="nav-links">
      <li><a href="#services">الخدمات</a></li>
      <li><a href="#bridal">العرائس</a></li>
      <li><a href="#gallery">معرض الأعمال</a></li>
      <li><a href="#packages">الباقات</a></li>
      <li><a href="#about">من نحن</a></li>
      <li><a href="#contact">تواصل معنا</a></li>
    </ul>

    <div class="nav-actions">
      <a href="https://wa.me/+201044925333" class="btn-whatsapp">
        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"></path></svg>
        واتساب
      </a>
      <a href="#booking" class="btn-booking">
        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 2v4"></path><path d="M16 2v4"></path><rect width="18" height="18" x="3" y="4" rx="2"></rect><path d="M3 10h18"></path></svg>
        احجزي موعداً
      </a>
      @auth
        <a href="{{ route('admin.dashboard') }}" class="nav-cta">login</a>
      @else
        <a href="{{ route('login') }}" class="nav-cta">login</a>
      @endauth
    </div>
    <div class="mobile-toggle" id="mobileToggle" onclick="toggleMobile()">
      <span></span><span></span><span></span>
    </div>
  </div>
</nav>

<!-- Hero -->
<section id="hero">
  <div class="hero-bg">
    <img src="images/storage/Woman_wearing_hijab_makeup_202607021118.jpeg" alt="AUR Beauty Lounge">
    <div style="position:absolute;inset:0;background:linear-gradient(to left,rgba(18,18,18,0.2),rgba(18,18,18,0.85))"></div>
  </div>
  <div class="hero-particles" id="particles"></div>
  <div class="hero-content">
    <div class="hero-badge">
      <i data-lucide="sparkles"></i>
      LUXURY BEAUTY EXPERIENCE
    </div>
    <h1 class="hero-title" id="heroTitle">
      AUR Beauty
      <span class="gold">Lounge & Atelier</span>
    </h1>
    <p class="hero-subtitle" id="heroSub">وجهتك الأولى للجمال والعناية والعروس المتكاملة</p>
    <div class="hero-buttons" id="heroBtns">
      <a href="#booking" class="btn-primary">
        <i data-lucide="calendar" style="width:18px;height:18px"></i>
        احجزي الآن
      </a>
      <a href="https://wa.me/+201044925333" target="_blank" class="btn-outline">
        <i data-lucide="message-circle" style="width:18px;height:18px"></i>
        واتساب
      </a>
    </div>
  </div>
  <div class="hero-scroll">
    <div class="scroll-line"></div>
    SCROLL
  </div>
</section>

<!-- Services -->
<section id="services" class="section">
  <div class="section-container">
    <div class="section-header" data-aos="fade-up">
      <div class="section-label">OUR SERVICES</div>
      <h2 class="section-title">خدماتنا <span class="gold">الاستثنائية</span></h2>
      <p class="section-desc">نقدم لكم مجموعة متكاملة من خدمات العناية بالجمال بأعلى معايير الجودة والفخامة</p>
    </div>

 <div class="services-grid">
    @forelse($services as $service)
        <div class="service-card" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
            <div class="service-media">
                {{-- ✅ استخدام الـ Accessor مباشرة --}}
                <img src="{{ $service->image_url ?? asset('images/default-service.jpg') }}" 
                     alt="{{ $service->title_ar }}">
                <div class="media-gradient"></div>
                <div class="service-badge">{{ $service->icon ?? '✨' }}</div>
            </div>
            <div class="service-body">
                <p class="service-label">{{ $service->label ?? $service->title_en }}</p>
                <h3 class="service-title">{{ $service->title_ar }}</h3>
                @if(!empty($service->features_ar) && count($service->features_ar))
                    <ul class="service-list">
                        @foreach($service->features_ar as $feature)
                            <li>{{ $feature }}</li>
                        @endforeach
                    </ul>
                @else
                    <p>{{ $service->description_ar ?? 'خدمة مميزة' }}</p>
                @endif
                <a href="#booking" class="service-btn">احجزي الآن</a>
            </div>
        </div>
    @empty
        <div class="service-card" data-aos="fade-up" data-aos-delay="0">
            <div class="service-media">
                <img src="{{ asset('images/default-service.jpg') }}" alt="خدمة">
                <div class="media-gradient"></div>
                <div class="service-badge">✨</div>
            </div>
            <div class="service-body">
                <p class="service-label">الخدمات</p>
                <h3 class="service-title">لا توجد خدمات حالياً</h3>
                <p>سيتم عرض الخدمات هنا عند إضافتها من لوحة الإدارة.</p>
            </div>
        </div>
    @endforelse
</div>
  </div>
</section>

<!-- ===== BRIDAL SECTION ===== -->
<!-- ======================================== -->
<!-- SECTION 1: BRIDAL EXCELLENCE (NEW DESIGN) -->
<!-- ======================================== -->
<section id="bridal">
    <div class="container">
        @if($bridal)
            <div class="bridal-image">
                <img src="{{ $bridal->image ? asset('storage/' . $bridal->image) : asset('images/White_Egyptian_bride_wedding_dress_202607211944.jpeg') }}" class="main-image">
                <div class="counter">
                    <h2>{{ $bridal->stats_number ?? '1500+' }}</h2>
                    <p>{{ $bridal->stats_label_ar ?? 'عروس جميلة' }}</p>
                </div>
                @if($bridal->small_image)
                    <img src="{{ asset('storage/' . $bridal->small_image) }}" alt="صورة مصغرة" class="small-image">
                @endif
            </div>

            <div class="bridal-content">
                <span class="section-title">{{ $bridal->title_en ? 'Bridal Excellence' : 'Bridal Excellence' }}</span>
                <div class="divider">
                    <span></span>
                    <div class="diamond"></div>
                    <span></span>
                </div>
                <h2>
                    {{ $bridal->title_ar ?? 'تجهيز العرائس' }}
                    <span>{{ $bridal->title_en ? $bridal->title_en : 'بلمسة فاخرة' }}</span>
                </h2>
                <p>{{ $bridal->description_ar ?? 'نؤمن بأن كل عروس تستحق أن تكون نسخة استثنائية من جمالها في أهم يوم من حياتها، لذلك نهتم بأدق التفاصيل لنمنحك إطلالة لا تُنسى.' }}</p>
                @if(!empty($bridal->features_ar) && count($bridal->features_ar))
                    @foreach($bridal->features_ar as $feature)
                        <div class="feature">✔ {{ $feature }}</div>
                    @endforeach
                @else
                    <div class="feature">✔ فريق متخصص للعرائس</div>
                    <div class="feature">✔ جلسة تجريبية قبل الزفاف</div>
                    <div class="feature">✔ منتجات عالمية أصلية</div>
                @endif
                <a href="#packeges" class="btn">استفسري عن باقات العرائس</a>
            </div>
        @else
            <div class="bridal-image">
                <img src="images/White_Egyptian_bride_wedding_dress_202607211944.jpeg" class="main-image">
                <div class="counter">
                    <h2>1500+</h2>
                    <p>عروس جميلة</p>
                </div>
                <img src="storage/services/Woman_wearing_hijab_makeup_202607021118.jpeg" class="small-image">
            </div>
            <div class="bridal-content">
                <span class="section-title">Bridal Excellence</span>
                <div class="divider">
                    <span></span>
                    <div class="diamond"></div>
                    <span></span>
                </div>
                <h2>
                    تجهيز العرائس
                    <span>بلمسة فاخرة</span>
                </h2>
                <p>نؤمن بأن كل عروس تستحق أن تكون نسخة استثنائية من جمالها في أهم يوم من حياتها، لذلك نهتم بأدق التفاصيل لنمنحك إطلالة لا تُنسى.</p>
                <div class="feature">✔ فريق متخصص للعرائس</div>
                <div class="feature">✔ جلسة تجريبية قبل الزفاف</div>
                <div class="feature">✔ منتجات عالمية أصلية</div>
                <a href="#packages" class="btn">استفسري عن باقات العرائس</a>
            </div>
        @endif
    </div>
</section>


<!-- Bridal Packages -->
<section id="packages" class="section">
  <div class="section-container">
    <div class="section-header" data-aos="fade-up">
      <div class="section-label">BRIDAL PACKAGES</div>
      <h2 class="section-title">باقات <span class="gold">العرائس</span></h2>
      <p class="section-desc">اختاري من بين باقاتنا الفاخرة المصممة خصيصاً لتجعلكِ أجمل عروس في ليلتك</p>
    </div>
    <div class="bridal-grid">
      @forelse($packages as $package)
        <div class="bridal-card {{ $package->tier }}" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
          @if($package->is_popular)
            <div class="popular-badge">الأكثر طلباً</div>
          @endif
          <div class="bridal-card-inner">
            <div class="bridal-front">
              <div class="bridal-tier">{{ ucfirst($package->tier) }}</div>
              <div class="bridal-name">{{ $package->name_ar }}</div>
              <div class="bridal-price">{{ number_format($package->price, 0, '.', ',') }} {{ $package->currency }}</div>
              @if(!empty($package->features_ar) && count($package->features_ar))
                <ul class="bridal-features">
                  @foreach($package->features_ar as $feature)
                    <li><i data-lucide="check"></i>{{ $feature }}</li>
                  @endforeach
                </ul>
              @endif
              {{-- <a href="#" class="bridal-btn" data-package="{{ $package->name_ar }}" onclick="event.preventDefault(); contactPackage(this.dataset.package)">احجزي الآن</a> --}}
            </div>
            <div class="bridal-back">
              <h3>{{ $package->name_ar }}</h3>
              <p>{{ $package->description_ar ?? 'باقة مميزة للعروس تحتوي على أفضل الخدمات لضمان يوم زفاف رائع.' }}</p>
              <a href="#" class="back-btn" data-package="{{ $package->name_ar }}" onclick="event.preventDefault(); contactPackage(this.dataset.package)"><i data-lucide="calendar" style="width:16px;height:16px"></i> احجزي الآن</a>
            </div>
          </div>
        </div>
      @empty
        <div class="bridal-card silver" data-aos="fade-up" data-aos-delay="0">
          <div class="bridal-card-inner">
            <div class="bridal-front">
              <div class="bridal-tier">Silver</div>
              <div class="bridal-name">Silver Bride</div>
              <div class="bridal-price">5,000 ج.م</div>
              <ul class="bridal-features">
                <li><i data-lucide="check"></i>ميكاب سواريه</li>
                <li><i data-lucide="check"></i>تسريحة شعر</li>
                <li><i data-lucide="check"></i>عناية بالبشرة</li>
                <li><i data-lucide="check"></i>مانيكير وبديكير</li>
                <li><i data-lucide="check"></i>تجربة قبل اليوم</li>
              </ul>
              <a href="#" class="bridal-btn" data-package="Silver Bride" onclick="event.preventDefault(); contactPackage(this.dataset.package)">احجزي الآن</a>
            </div>
            <div class="bridal-back">
              <h3>Silver Bride</h3>
              <p>باقة فضية متكاملة تشمل كل ما تحتاجينه لإطلالة ساحرة في يومك الخاص.</p>
              <a href="#" class="back-btn" data-package="Silver Bride" onclick="event.preventDefault(); contactPackage(this.dataset.package)"><i data-lucide="calendar" style="width:16px;height:16px"></i> احجزي الآن</a>
            </div>
          </div>
        </div>
      @endforelse
    </div>
  </div>
</section>

<!-- Before & After -->
<section class="ba-section">
  <div class="ba-container">

    <div class="ba-header">
      <p class="ba-label">Transformations</p>
      <div class="ba-decor">
        <div class="ba-decor-line right"></div>
        <div class="ba-decor-diamond"></div>
        <div class="ba-decor-line left"></div>
      </div>
      <h2 class="ba-title">Before & After</h2>
      <p class="ba-subtitle">قبل وبعد</p>
    </div>

    <div class="ba-filters">
      <button class="ba-filter-btn" data-filter="makeup">💄 مكياج</button>
      <button class="ba-filter-btn active" data-filter="hair">✂️ شعر</button>
      <button class="ba-filter-btn" data-filter="video">🎥 فيديو</button>
    </div>

    <div class="ba-grid" id="baGrid">
      @forelse($transformations ?? [] as $transformation)
        <div class="ba-card {{ $loop->first ? 'featured show' : 'show' }}" data-category="{{ $transformation->category ?? 'hair' }}">
          <div class="ba-images">
            <div class="ba-img-wrap">
              {{-- ✅ المسار الصحيح للصور --}}
              @php
                $beforeImage = asset('images/default-before.jpg');
                if ($transformation->before_image) {
                    // إذا كانت الصورة في storage
                    if (str_starts_with($transformation->before_image, 'transformations/')) {
                        $beforeImage = asset('storage/' . $transformation->before_image);
                    } 
                    // إذا كانت الصورة في public مباشرة
                    elseif (str_starts_with($transformation->before_image, 'images/')) {
                        $beforeImage = asset($transformation->before_image);
                    }
                    // إذا كان رابط كامل
                    elseif (filter_var($transformation->before_image, FILTER_VALIDATE_URL)) {
                        $beforeImage = $transformation->before_image;
                    }
                }
              @endphp
              <img src="{{ $beforeImage }}" alt="قبل" onerror="this.src='{{ asset('images/default-before.jpg') }}'">
              <span class="ba-tag-before">قبل</span>
            </div>
            <div class="ba-img-wrap">
              {{-- ✅ المسار الصحيح للصور --}}
              @php
                $afterImage = asset('images/default-after.jpg');
                if ($transformation->after_image) {
                    if (str_starts_with($transformation->after_image, 'transformations/')) {
                        $afterImage = asset('storage/' . $transformation->after_image);
                    } 
                    elseif (str_starts_with($transformation->after_image, 'images/')) {
                        $afterImage = asset($transformation->after_image);
                    }
                    elseif (filter_var($transformation->after_image, FILTER_VALIDATE_URL)) {
                        $afterImage = $transformation->after_image;
                    }
                }
              @endphp
              <img src="{{ $afterImage }}" alt="بعد" onerror="this.src='{{ asset('images/default-after.jpg') }}'">
              <span class="ba-tag-after">بعد</span>
            </div>
          </div>
          <div class="ba-info">
            <p class="ba-info-cat">{{ ucfirst($transformation->category) }}</p>
            <h3 class="ba-info-title">{{ $transformation->title_ar }}</h3>
            <p class="ba-info-desc">{{ $transformation->description_ar }}</p>
          </div>
        </div>
      @empty
        <div class="ba-empty visible" id="baEmpty">لا توجد نتائج في هذا القسم حالياً</div>
      @endforelse
 @forelse($videos ?? [] as $video)
        <div class="ba-card show" data-category="video" style="display: none;">
          <div class="ba-images" style="flex-direction: column;">
            <div class="ba-video-wrap" style="width: 100%; border-radius: 12px; overflow: hidden; border: 2px solid #D4AF37;">
              <video controls style="width: 100%; display: block;">
                <source src="{{ asset('storage/' . $video->video) }}" type="video/mp4">
                متصفحك لا يدعم تشغيل الفيديو.
              </video>
            </div>
          </div>
          <div class="ba-info">
            <h3 class="ba-info-title" style="color: #D4AF37;">{{ $video->title_ar }}</h3>
            <p class="ba-info-desc">{{ $video->description_ar }}</p>
          </div>
        </div>
      @empty
        <div class="ba-empty visible" id="baEmptyVideo" style="display: none;">لا توجد فيديوهات في هذا القسم حالياً</div>
      @endforelse

    </div>
  </div>
</section>


<section class="gallery-section" id="gallery">
  <div class="gallery-container">

    <!-- الهيدر -->
    <div class="gallery-header">
      <p class="gallery-label">Our Work</p>
      <div class="gallery-decor">
        <div class="gallery-decor-line right"></div>
        <div class="gallery-decor-diamond"></div>
        <div class="gallery-decor-line left"></div>
      </div>
      <h2 class="gallery-title">Portfolio Gallery</h2>
      <p class="gallery-subtitle">معرض الأعمال</p>
    </div>

    <!-- الفلتر -->
    <div class="gallery-filters">
      <button class="gallery-filter-btn active" data-filter="all">الكل</button>
      <button class="gallery-filter-btn" data-filter="bridal">عرائس</button>
      <button class="gallery-filter-btn" data-filter="makeup">مكياج</button>
      <button class="gallery-filter-btn" data-filter="fashion">أزياء</button>
      <button class="gallery-filter-btn" data-filter="hair">شعر</button>
      <button class="gallery-filter-btn" data-filter="skin">بشرة</button>
    </div>

    <!-- الشبكة -->
    <div class="gallery-grid" id="galleryGrid">
      @forelse($galleries ?? [] as $gallery)
        <div class="gallery-item show" data-category="{{ $gallery->category }}" data-caption="{{ $gallery->caption ?? $gallery->title_ar ?? $gallery->title_en ?? '' }}">
          <img src="{{ asset($gallery->image) }}" alt="{{ $gallery->title_ar ?? $gallery->title_en ?? 'عرض' }}">
          <div class="gallery-overlay">
            <div class="gallery-overlay-icon">
              <svg viewBox="0 0 24 24"><path d="M9.937 15.5A2 2 0 0 0 8.5 14.063l-6.135-1.582a.5.5 0 0 1 0-.962L8.5 9.936A2 2 0 0 0 9.937 8.5l1.582-6.135a.5.5 0 0 1 .963 0L14.063 8.5A2 2 0 0 0 15.5 9.937l6.135 1.581a.5.5 0 0 1 0 .964L15.5 14.063a2 2 0 0 0-1.437 1.437l-1.582 6.135a.5.5 0 0 1-.963 0z"/><path d="M20 3v4"/><path d="M22 5h-4"/><path d="M4 17v2"/><path d="M5 18H3"/></svg>
            </div>
            <p class="gallery-overlay-text">{{ $gallery->caption ?? $gallery->title_ar ?? $gallery->title_en ?? '' }}</p>
          </div>
        </div>
      @empty
        <div class="gallery-empty visible" id="galleryEmpty">لا توجد نتائج في هذا القسم حالياً</div>
      @endforelse

    </div>
  </div>
</section>


<!-- Experts -->
<section id="experts" class="section">
  <div class="section-container">
    <div class="section-header" data-aos="fade-up">
      <div class="section-label">OUR TEAM</div>
      <h2 class="section-title">فريق <span class="gold">الخبراء</span></h2>
      <p class="section-desc">نخبة من أمهر المتخصصين في عالم الجمال والعناية</p>
    </div>
    <div class="experts-grid">
      <div class="expert-card" data-aos="fade-up" data-aos-delay="0">
        <div class="expert-img">
          <img src="https://z-cdn-media.chatglm.cn/files/0113f9cf-3d40-4824-9a75-403d660dea3e.jpeg?auth_key=1883349577-356de691df754fc6a6e6440d510dba0b-0-3249b546b93c28ab1a1c17bcf9717c09" alt="Expert">
        </div>
        <h3>سارة أحمد</h3>
        <div class="role">مديرة الميكاب</div>
        <p>خبرة +12 عام في ميكاب العرائس والسواريه</p>
        <div class="expert-social">
          <a href="#"><i data-lucide="instagram" style="width:14px;height:14px"></i></a>
          <a href="#"><i data-lucide="twitter" style="width:14px;height:14px"></i></a>
        </div>
      </div>
      <div class="expert-card" data-aos="fade-up" data-aos-delay="100">
        <div class="expert-img">
          <img src="https://z-cdn-media.chatglm.cn/files/97b5c75c-9634-4a1d-b62c-8fcd7a05ee94.jpeg?auth_key=1883349577-42f63554e1a84a9aa86439585e12bc1a-0-7aa78def907d54623d8630181fcb83eb" alt="Expert">
        </div>
        <h3>نورا محمود</h3>
        <div class="role">خبيرة الشعر</div>
        <p>متخصصة في التسريحات الفاخرة والصبغات</p>
        <div class="expert-social">
          <a href="#"><i data-lucide="instagram" style="width:14px;height:14px"></i></a>
          <a href="#"><i data-lucide="twitter" style="width:14px;height:14px"></i></a>
        </div>
      </div>
      <div class="expert-card" data-aos="fade-up" data-aos-delay="200">
        <div class="expert-img">
          <img src="https://z-cdn-media.chatglm.cn/files/c20de836-20b4-4ddf-8aee-2e7142a7e8aa.jpeg?auth_key=1883349577-f06a2a54445f410f9ad25aa28719caa7-0-c50b28fc6018aac44e4d35bd36d305ed" alt="Expert">
        </div>
        <h3>ريم عبدالله</h3>
        <div class="role">أخصائية البشرة</div>
        <p>خبيرة في الهيدرافيشل والعناية المتقدمة</p>
        <div class="expert-social">
          <a href="#"><i data-lucide="instagram" style="width:14px;height:14px"></i></a>
          <a href="#"><i data-lucide="twitter" style="width:14px;height:14px"></i></a>
        </div>
      </div>
      <div class="expert-card" data-aos="fade-up" data-aos-delay="300">
        <div class="expert-img">
          <img src="https://z-cdn-media.chatglm.cn/files/b50f1b9a-5734-4b69-afd2-b3bd86f8a23a.jpeg?auth_key=1883349577-b118d3691ba149a99cc309f8638665f3-0-adaa061ac36857d3cb2864839d285257" alt="Expert">
        </div>
        <h3>هدى حسن</h3>
        <div class="role">خبيرة الأظافر</div>
        <p>فنانة في Gel و Acrylic والتصاميم الفاخرة</p>
        <div class="expert-social">
          <a href="#"><i data-lucide="instagram" style="width:14px;height:14px"></i></a>
          <a href="#"><i data-lucide="twitter" style="width:14px;height:14px"></i></a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Statistics -->
<section id="stats">
  <div class="stats-grid">
    <div class="stat-item" data-aos="fade-up">
      <div class="stat-number" data-target="5000">0</div>
      <div class="stat-label">+ عميلة سعيدة</div>
    </div>
    <div class="stat-item" data-aos="fade-up" data-aos-delay="100">
      <div class="stat-number" data-target="1500">0</div>
      <div class="stat-label">+ عروس</div>
    </div>
    <div class="stat-item" data-aos="fade-up" data-aos-delay="200">
      <div class="stat-number" data-target="10">0</div>
      <div class="stat-label">+ سنوات خبرة</div>
    </div>
    <div class="stat-item" data-aos="fade-up" data-aos-delay="300">
      <div class="stat-number" data-target="25">0</div>
      <div class="stat-label">+ خبيرة متخصصة</div>
    </div>
  </div>
</section>

<!-- Testimonials -->
<section id="testimonials" class="section">
  <div class="section-container">
    <div class="section-header" data-aos="fade-up">
      <div class="section-label">TESTIMONIALS</div>
      <h2 class="section-title">آراء <span class="gold">عملائنا</span></h2>
      <p class="section-desc">ماذا تقول عميلاتنا عن تجربتهن مع AUR</p>
    </div>
    <div class="testi-slider" data-aos="fade-up" data-aos-delay="100">
      <div class="testi-track" id="testiTrack">
        @forelse($testimonials ?? [] as $testimonial)
          <div class="testi-slide">
            <div class="testi-stars">{{ str_repeat('★', min($testimonial->rating ?? 5, 5)) }}</div>
            <p class="testi-text">{{ $testimonial->content_ar }}</p>
            <div class="testi-author">
              <div class="testi-avatar"><img src="{{ $testimonial->avatar ? asset($testimonial->avatar) : 'https://picsum.photos/seed/testimonial' . $testimonial->id . '/100/100.jpg' }}" alt="{{ $testimonial->client_name }}"></div>
              <div class="testi-info"><h4>{{ $testimonial->client_name }}</h4><p>{{ $testimonial->client_role_ar }}</p></div>
            </div>
          </div>
        @empty
          <div class="testi-slide">
            <div class="testi-stars">★★★★★</div>
            <p class="testi-text">لا توجد آراء العملاء في الوقت الحالي.</p>
            <div class="testi-author">
              <div class="testi-avatar"><img src="https://picsum.photos/seed/testimonial-empty/100/100.jpg" alt=""></div>
              <div class="testi-info"><h4>فريق AUR</h4><p>نحن هنا من أجلك</p></div>
            </div>
          </div>
        @endforelse
      </div>
      <div class="testi-nav" id="testiNav"></div>
    </div>
  </div>
</section>

<!-- Booking -->
<section id="booking" class="section">
  <div class="section-container">
    <div class="section-header" data-aos="fade-up">
      <div class="section-label">BOOKING</div>
      <h2 class="section-title">احجزي <span class="gold">موعديك</span></h2>
      <p class="section-desc">نظام حجز سهل وسريع لاختيار الخدمة والموعد المناسب لكِ</p>
    </div>
    <form id="bookingForm" action="{{ route('bookings.store') }}" method="POST">
      @csrf
      <input type="hidden" name="service_id" id="bookingServiceId">
      <input type="hidden" name="staff_id" id="bookingStaffId">
      <div class="booking-wrapper" data-aos="fade-up" data-aos-delay="100">
      <div class="step-indicators">
        <div class="step-ind active" data-step="1">1</div>
        <div class="step-line"></div>
        <div class="step-ind" data-step="2">2</div>
        <div class="step-line"></div>
        <div class="step-ind" data-step="3">3</div>
        <div class="step-line"></div>
        <div class="step-ind" data-step="4">4</div>
      </div>

      <!-- Step 1: Service -->
      <div class="step-content active" data-step="1">
        <div class="step-title">اختري الخدمة</div>
        <div class="service-options">
        @forelse($services ?? [] as $service)
          <div class="service-opt" data-service-id="{{ $service->id }}" onclick="selectService(this)">
            <h4>{{ $service->icon ?? '✨' }} {{ $service->title_ar ?? $service->title_en }}</h4>
            <p>{{ $service->description_ar ?? $service->description_en ?? $service->title_en ?? '' }}</p>
          </div>
        @empty
          <div class="service-empty" style="text-align:center;padding:40px 20px;grid-column:1/-1;">
            <h4>لا توجد خدمات متاحة حالياً</h4>
            <p>الرجاء التواصل معنا عبر الواتساب أو الهاتف لحجز موعدك.</p>
          </div>
        @endforelse
        </div>
      <div class="step-buttons"><div></div><button class="btn-next" type="button" onclick="nextStep()">التالي ←</button></div>
      </div>
 
      <!-- Step 2: Staff -->
      <div class="step-content" data-step="2">
        <div class="step-title">اختري الخبيرة</div>
        <div class="staff-options">
          @forelse($staff ?? [] as $member)
            <div class="staff-opt" data-staff-id="{{ $member->id }}" onclick="selectStaff(this)">
              <div class="staff-av"><img src="{{ $member->image ? asset($member->image) : 'https://picsum.photos/seed/staff' . $member->id . '/100/100.jpg' }}" alt="{{ $member->name_ar }}"></div>
              <h4>{{ $member->name_ar }}</h4>
              <p>{{ $member->role_ar }}</p>
            </div>
          @empty
            <div class="staff-empty" style="text-align:center;padding:40px 20px;grid-column:1/-1;">
              <h4>لا توجد خبيرات متاحة حالياً</h4>
              <p>سنعلمك عندما تتوفر الخبرات أو يمكنك التواصل معنا مباشرة.</p>
            </div>
          @endforelse
        </div>
        <div class="step-buttons"><button class="btn-back" type="button" onclick="prevStep()">→ السابق</button><button class="btn-next" type="button" onclick="nextStep()">التالي ←</button></div>
      </div>

      <!-- Step 3: Date & Time -->
      <div class="step-content" data-step="3">
        <div class="step-title">اختري الموعد</div>
        <div class="form-group">
          <label>التاريخ</label>
          <input type="date" id="bookDate" name="date">
        </div>
        <div class="form-group">
          <label>الوقت</label>
          <select id="bookTime" name="time">
            <option value="">اختري الوقت</option>
              <option value="10:00">10:00 ص</option><option value="11:00">11:00 ص</option><option value="12:00">12:00 م</option>
              <option value="13:00">1:00 م</option><option value="14:00">2:00 م</option><option value="15:00">3:00 م</option>
              <option value="16:00">4:00 م</option><option value="17:00">5:00 م</option><option value="18:00">6:00 م</option>
              <option value="19:00">7:00 م</option><option value="20:00">8:00 م</option><option value="21:00">9:00 م</option>
          </select>
        </div>
        <div class="form-group">
          <label>ملاحظات إضافية</label>
          <textarea id="bookNotes" name="notes" rows="3" placeholder="أي طلبات خاصة..."></textarea>
        </div>
       <div class="step-buttons"><button class="btn-back" type="button" onclick="prevStep()">→ السابق</button><button class="btn-next" type="button" onclick="nextStep()">التالي ←</button></div>
      </div>
 
      <!-- Step 4: Confirm -->
      <div class="step-content" data-step="4">
        <div class="step-title">تأكيد الحجز</div>
        <div id="bookingSummary" style="background:rgba(212,175,55,0.05);border:1px solid rgba(212,175,55,0.15);border-radius:16px;padding:24px;margin-bottom:24px"></div>
        <div class="form-group">
          <label>الاسم الكامل</label>
          <input type="text" id="bookingName" name="customer_name" placeholder="أدخلي اسمك الكامل">
        </div>
        <div class="form-group">
          <label>رقم الهاتف</label>
          <input type="tel" id="bookingPhone" name="phone" placeholder="01xxxxxxxxx">
        </div>
        <div class="form-group">
          <label>البريد الإلكتروني</label>
          <input type="email" id="bookingEmail" name="email" placeholder="example@email.com">
        </div>
        <div class="step-buttons">
          <button class="btn-back" type="button" onclick="prevStep()">→ السابق</button>
          <button class="btn-next" type="button" onclick="confirmBooking()" style="background:linear-gradient(135deg,var(--gold),var(--gold-dark))">✓ تأكيد الحجز</button>
        </div>
      </div>
    </div>
  </form>
  </div>
</section>

<!-- Online Consultation -->
<section id="consultation" class="section">
  <div class="section-container">
    <div class="consult-wrapper">
      <div class="consult-info" data-aos="fade-right">
        <div class="section-label">ONLINE CONSULTATION</div>
        <h3>استشارة <span style="color:var(--gold)">مجانية</span> قبل الحجز</h3>
        <p>تواصلي معنا قبل حجزك للحصول على استشارة مخصصة تناسب احتياجاتك. فريقنا جاهز للإجابة على جميع استفساراتك.</p>
        <ul class="consult-features">
          <li><i data-lucide="video"></i>استشارة عبر الفيديو</li>
          <li><i data-lucide="message-circle"></i>استشارة عبر الواتساب</li>
          <li><i data-lucide="image"></i>تحليل البشرة عن بُعد</li>
          <li><i data-lucide="file-text"></i>توصيات مخصصة لكِ</li>
          <li><i data-lucide="clock"></i>رد خلال ساعة واحدة</li>
        </ul>
        <a href="https://wa.me/+201044925333?text=%D9%85%D8%B1%D8%AD%D8%A8%D8%A7+%D8%8C+%D8%A3%D8%B1%D9%8A%D8%AF+%D8%A7%D8%B3%D8%AA%D8%B4%D8%A7%D8%B1%D8%A9+%D9%85%D8%AC%D8%A7%D9%86%D9%8A%D8%A9+%D9%85%D8%B9+%D8%B1%D8%AC%D8%A7%D8%A1+%D8%AD%D8%AC%D8%B2%D8%A9" target="_blank" class="btn-primary" style="margin-top:24px;animation:none">
          <i data-lucide="message-circle" style="width:18px;height:18px"></i>
          تواصلي الآن
        </a>
      </div>
      <div class="consult-form" data-aos="fade-left">
        <h3 style="font-size:20px;color:var(--white);margin-bottom:20px;text-align:center">اطلبي استشارة</h3>
        <div class="form-group">
          <label>الاسم</label>
          <input id="consultName" type="text" placeholder="اسمك الكامل">
        </div>
        <div class="form-group">
          <label>رقم الهاتف</label>
          <input id="consultPhone" type="tel" placeholder="01xxxxxxxxx">
        </div>
        <div class="form-group">
          <label>نوع الاستشارة</label>
          <select id="consultType">
            <option>ميكاب وتسريحة</option>
            <option>عناية بالبشرة</option>
            <option>باقة عروس</option>
            <option>عناية بالشعر</option>
            <option>أخرى</option>
          </select>
        </div>
        <div class="form-group">
          <label>رسالتك</label>
          <textarea id="consultMessage" rows="3" placeholder="اكتبي استفسارك هنا..."></textarea>
        </div>
        <button type="button" class="btn-next" style="width:100%" onclick="sendConsultation()">إرسال الطلب</button>
      </div>
    </div>
  </div>
</section>

<!-- About -->
<section id="about" class="section" style="background:var(--black)">
  <div class="section-container">
    <div class="section-header" data-aos="fade-up">
      <div class="section-label">ABOUT US</div>
      <h2 class="section-title">من <span class="gold">نحن</span></h2>
    </div>
    <div style="max-width:800px;margin:0 auto;text-align:center" data-aos="fade-up" data-aos-delay="100">
      <p style="color:rgba(250,248,245,0.7);line-height:2;font-size:16px;margin-bottom:20px">
        <strong style="color:var(--gold)">AUR Beauty Lounge & Atelier</strong> هو وجهتك الأولى للجمال والعناية الفاخرة. نجمع بين الفخامة والاحترافية لنقدم لكِ تجربة استثنائية من لحظة دخولك حتى خروجك بإطلالة ساحرة.
      </p>
      <p style="color:rgba(250,248,245,0.5);line-height:2;font-size:15px">
        نؤمن بأن كل امرأة تستحق أن تشعر بالثقة والجمال. لذلك نستخدم أرقى المنتجات العالمية مثل Lancôme و Chanel و Dior، مع فريق من أمهر الخبراء المتخصصين الذين يضعون مهارتهم في خدمة إطلالتك المثالية.
      </p>
    </div>
  </div>
</section>


<!-- Contact -->
<section id="contact" class="section">
  <div class="section-container">
    <div class="section-header" data-aos="fade-up">
      <div class="section-label">CONTACT US</div>
      <h2 class="section-title">تواصلي <span class="gold">معنا</span></h2>
      <p class="section-desc">نحن هنا لخدمتك! تواصلي معنا بأي طريقة تناسبك</p>
    </div>
    <div class="contact-grid">
      <div class="contact-info" data-aos="fade-right">
        <a href="https://wa.me/201000000000" target="_blank" class="contact-item">
          <div class="contact-icon"><i data-lucide="message-circle"></i></div>
          <div><h4>واتساب</h4><p>01 000 000 000</p></div>
        </a>
        <a href="tel:+201000000000" class="contact-item">
          <div class="contact-icon"><i data-lucide="phone"></i></div>
          <div><h4>اتصال هاتفي</h4><p>01 000 000 000</p></div>
        </a>
        <a href="#" class="contact-item">
          <div class="contact-icon"><i data-lucide="facebook"></i></div>
          <div><h4>فيسبوك</h4><p>@AURBeautyLounge</p></div>
        </a>
        <a href="#" class="contact-item">
          <div class="contact-icon"><i data-lucide="instagram"></i></div>
          <div><h4>إنستجرام</h4><p>@aur.beauty.lounge</p></div>
        </a>
        <a href="#" class="contact-item">
          <div class="contact-icon"><i data-lucide="map-pin"></i></div>
          <div><h4>العنوان</h4><p>القاهرة، مصر</p></div>
        </a>
      </div>
      <div class="contact-map" data-aos="fade-left">
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d110502.76718827498!2d31.18460469316498!3d30.059558299999996!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x14583fa60b21beeb%3A0x79dfb296e8423bba!2sCairo%2C%20Cairo%20Governorate%2C%20Egypt!5e0!3m2!1sen!2s!4v1700000000000" loading="lazy"></iframe>
      </div>
    </div>
  </div>
</section>

<!-- Footer -->
<footer>
  <div class="footer-grid">
    <div class="footer-brand">
      <img src="https://z-cdn-media.chatglm.cn/files/747557df-e36c-4698-8385-91701448f044.jpeg?auth_key=1883349577-7a3a90a07a174a2cb14f137d4ddb24d1-0-5bb18433b17a8e98927ce15f330e3b7c" alt="AUR">
      <p>وجهتك الأولى للجمال والعناية والعروس المتكاملة. نقدم خدمات عالمية المستوى بأعلى معايير الجودة والفخامة.</p>
      <div class="footer-social">
        <a href="#"><i data-lucide="instagram" style="width:16px;height:16px"></i></a>
        <a href="#"><i data-lucide="facebook" style="width:16px;height:16px"></i></a>
        <a href="#"><i data-lucide="twitter" style="width:16px;height:16px"></i></a>
        <a href="#"><i data-lucide="youtube" style="width:16px;height:16px"></i></a>
      </div>
    </div>
    <div class="footer-col">
      <h4>روابط سريعة</h4>
      <a href="#services">الخدمات</a>
      <a href="#packages">باقات العرائس</a>
      <a href="#gallery">معرض الأعمال</a>
      <a href="#booking">حجز موعد</a>
      <a href="#about">من نحن</a>
      <a href="#contact">تواصل معنا</a>
    </div>
    <div class="footer-col">
      <h4>الخدمات</h4>
      <a href="#">ميكاب</a>
      <a href="#">تسريحات شعر</a>
      <a href="#">عناية بالبشرة</a>
      <a href="#">أظافر</a>
      <a href="#">سبا</a>
      <a href="#">تجهيز عرائس</a>
    </div>
    <div class="footer-col">
      <h4>أوقات العمل</h4>
      <div class="hours">
        السبت - الخميس<br>
        10:00 ص - 10:00 م<br><br>
        الجمعة<br>
        2:00 م - 10:00 م
      </div>
    </div>
  </div>
  <div class="footer-bottom">
    <span>© 2025 AUR Beauty Lounge & Atelier. جميع الحقوق محفوظة.</span>
    <span>صُمم بـ <span style="color:var(--gold)">♥</span> في مصر</span>
  </div>
</footer>
{{-- <script src="{{ asset('js/app.js') }}"></script> --}}
<!-- AOS -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

<script>

</script>
</body>
</html>