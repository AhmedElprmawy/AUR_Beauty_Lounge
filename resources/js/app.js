import '../css/app.css';

let dot = null;
let ring = null;
let mouseX = 0, mouseY = 0, ringX = 0, ringY = 0;
let particlesContainer = null;
let navbar = null;

window.addEventListener('load', () => {
  setTimeout(() => {
    const loader = document.getElementById('loader');
    if (loader) loader.classList.add('hidden');
    initAnimations();
  }, 2000);
});

function initPage() {
  dot = document.querySelector('.cursor-dot');
  ring = document.querySelector('.cursor-ring');
  navbar = document.getElementById('navbar');
  particlesContainer = document.getElementById('particles');

  if (dot && ring) {
    document.addEventListener('mousemove', e => {
      mouseX = e.clientX;
      mouseY = e.clientY;
      dot.style.left = mouseX + 'px';
      dot.style.top = mouseY + 'px';
    });

    function animateCursor() {
      ringX += (mouseX - ringX) * 0.15;
      ringY += (mouseY - ringY) * 0.15;
      ring.style.left = ringX + 'px';
      ring.style.top = ringY + 'px';
      requestAnimationFrame(animateCursor);
    }

    animateCursor();

    document.querySelectorAll('a, button, .service-card, .bridal-card, .portfolio-item, .expert-card, .product-card, .gift-card, .insta-item, .contact-item, .filter-btn, .service-opt, .staff-opt, .testi-dot').forEach(el => {
      el.addEventListener('mouseenter', () => {
        dot.classList.add('hover');
        ring.classList.add('hover');
      });
      el.addEventListener('mouseleave', () => {
        dot.classList.remove('hover');
        ring.classList.remove('hover');
      });
    });
  }

  if (navbar) {
    window.addEventListener('scroll', () => {
      navbar.classList.toggle('scrolled', window.scrollY > 50);
    });
  }

  if (particlesContainer) {
    for (let i = 0; i < 20; i++) {
      const p = document.createElement('div');
      p.className = 'particle';
      p.style.left = Math.random() * 100 + '%';
      p.style.animationDelay = Math.random() * 6 + 's';
      p.style.animationDuration = (4 + Math.random() * 4) + 's';
      particlesContainer.appendChild(p);
    }
  }

  initPortfolioFilter();
  initLightbox();
  initTestimonialSlider();
  initBeforeAfterSlider();
  initBAFilter();
  initBookingSteps();
  initGalleryFilter();
  initSmoothScroll();
  lucide.createIcons();
}

document.addEventListener('DOMContentLoaded', initPage);

function initAnimations() {
  if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined' || typeof AOS === 'undefined') {
    return;
  }

  gsap.registerPlugin(ScrollTrigger);

  if (document.querySelector('#hero')) {
    const heroTl = gsap.timeline();
    heroTl.from('#heroTitle', { y: 60, opacity: 0, duration: 1, ease: 'power3.out' })
      .from('#heroSub', { y: 40, opacity: 0, duration: 0.8, ease: 'power3.out' }, '-=0.5')
      .from('#heroBtns', { y: 30, opacity: 0, duration: 0.8, ease: 'power3.out' }, '-=0.4')
      .from('.hero-badge', { y: -20, opacity: 0, duration: 0.6, ease: 'power3.out' }, '-=0.8');

    gsap.to('.hero-bg img', {
      y: 150,
      ease: 'none',
      scrollTrigger: { trigger: '#hero', start: 'top top', end: 'bottom top', scrub: true }
    });
  }

  const statNumbers = document.querySelectorAll('.stat-number');
  statNumbers.forEach(num => {
    const target = parseInt(num.dataset.target);
    if (Number.isNaN(target)) return;
    ScrollTrigger.create({
      trigger: num,
      start: 'top 85%',
      once: true,
      onEnter: () => {
        gsap.to({ val: 0 }, {
          val: target,
          duration: 2,
          ease: 'power2.out',
          onUpdate: function() { num.textContent = Math.floor(this.targets()[0].val).toLocaleString(); }
        });
      }
    });
  });

  AOS.init({ duration: 800, once: true, offset: 80 });
}

function initPortfolioFilter() {
  const filterBtns = document.querySelectorAll('.filter-btn');
  const portfolioItems = document.querySelectorAll('.portfolio-item');

  filterBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      filterBtns.forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
      const filter = btn.dataset.filter;

      portfolioItems.forEach(item => {
        if (filter === 'all' || item.dataset.category === filter) {
          item.classList.remove('hidden');
          item.style.position = '';
          gsap.from(item, { scale: 0.8, opacity: 0, duration: 0.5, ease: 'power2.out' });
        } else {
          item.classList.add('hidden');
        }
      });
    });
  });
}

function initLightbox() {
  const lightbox = document.getElementById('lightbox');
  if (!lightbox) return;
  const lightboxImg = document.getElementById('lightboxImg');
  const lightboxCaption = document.getElementById('lightboxCaption');
  const lightboxClose = document.getElementById('lightboxClose');
  const lightboxPrev = document.getElementById('lightboxPrev');
  const lightboxNext = document.getElementById('lightboxNext');
  let visibleItems = [];
  let currentIndex = 0;

  const items = document.querySelectorAll('.gallery-item');
  items.forEach(item => {
    item.addEventListener('click', () => {
      if (item.classList.contains('hide')) return;
      visibleItems = Array.from(items).filter(i => !i.classList.contains('hide'));
      currentIndex = visibleItems.indexOf(item);
      openLightbox();
    });
  });

  lightboxClose?.addEventListener('click', closeLightbox);
  lightboxPrev?.addEventListener('click', e => { e.stopPropagation(); navigate(-1); });
  lightboxNext?.addEventListener('click', e => { e.stopPropagation(); navigate(1); });
  lightbox.addEventListener('click', e => { if (e.target === lightbox) closeLightbox(); });

  document.addEventListener('keydown', e => {
    if (!lightbox.classList.contains('active')) return;
    if (e.key === 'Escape') closeLightbox();
    if (e.key === 'ArrowLeft') navigate(-1);
    if (e.key === 'ArrowRight') navigate(1);
  });

  function openLightbox() {
    if (!lightboxImg || !lightboxCaption) return;
    const item = visibleItems[currentIndex];
    if (!item) return;
    const img = item.querySelector('img');
    if (!img) return;
    lightboxImg.src = img.src;
    lightboxCaption.textContent = item.dataset.caption || '';
    lightbox.classList.add('active');
    document.body.style.overflow = 'hidden';
  }

  function closeLightbox() {
    lightbox.classList.remove('active');
    document.body.style.overflow = '';
  }

  function navigate(dir) {
    if (!visibleItems.length) return;
    currentIndex = (currentIndex + dir + visibleItems.length) % visibleItems.length;
    openLightbox();
  }
}

function initTestimonialSlider() {
  const testiTrack = document.getElementById('testiTrack');
  if (!testiTrack) return;
  const testiSlides = testiTrack.querySelectorAll('.testi-slide');
  const testiNav = document.getElementById('testiNav');
  if (!testiNav || testiSlides.length === 0) return;
  let currentSlide = 0;

  testiSlides.forEach((_, i) => {
    const dot = document.createElement('div');
    dot.className = 'testi-dot' + (i === 0 ? ' active' : '');
    dot.addEventListener('click', () => goToSlide(i));
    testiNav.appendChild(dot);
  });

  function goToSlide(index) {
    currentSlide = index;
    testiTrack.style.transform = `translateX(${index * 100}%)`;
    document.querySelectorAll('.testi-dot').forEach((d, i) => d.classList.toggle('active', i === index));
  }

  setInterval(() => {
    goToSlide((currentSlide + 1) % testiSlides.length);
  }, 5000);
}

function initBeforeAfterSlider() {
  document.querySelectorAll('.ba-slider').forEach(slider => {
    let isDragging = false;
    const item = slider.parentElement;
    const afterDiv = item?.querySelector('.ba-after');
    if (!afterDiv) return;

    function updateSlider(x) {
      const rect = item.getBoundingClientRect();
      let pos = (x - rect.left) / rect.width * 100;
      pos = Math.max(5, Math.min(95, pos));
      slider.style.left = pos + '%';
      afterDiv.style.clipPath = `inset(0 0 0 ${pos}%)`;
    }

    slider.addEventListener('mousedown', () => isDragging = true);
    slider.addEventListener('touchstart', () => isDragging = true);
    document.addEventListener('mousemove', e => { if (isDragging) updateSlider(e.clientX); });
    document.addEventListener('touchmove', e => { if (isDragging) updateSlider(e.touches[0].clientX); });
    document.addEventListener('mouseup', () => isDragging = false);
    document.addEventListener('touchend', () => isDragging = false);
  });
}

function initBAFilter() {
  const filterBtns = document.querySelectorAll('.ba-filter-btn');
  const items = document.querySelectorAll('.ba-card');
  const emptyMsg = document.getElementById('baEmpty');

  if (!filterBtns.length || !items.length) return;

  filterBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      filterBtns.forEach(b => b.classList.remove('active'));
      btn.classList.add('active');

      const filter = btn.dataset.filter;
      let visibleCount = 0;

      items.forEach(item => {
        const match = filter === 'all' || item.dataset.category === filter;
        if (match) {
          item.classList.remove('hide');
          item.classList.add('show');
          item.style.display = '';
          visibleCount += 1;
        } else {
          item.classList.add('hide');
          item.classList.remove('show');
          item.style.display = 'none';
        }
      });

      if (emptyMsg) {
        emptyMsg.classList.toggle('visible', visibleCount === 0);
      }
    });
  });
}

function initBookingSteps() {
  let currentStep = 1;
  let bookingData = { serviceId: '', serviceLabel: '', staffId: '', staffLabel: '', date: '', time: '' };

  window.selectService = function(el) {
    document.querySelectorAll('.service-opt').forEach(o => o.classList.remove('selected'));
    el.classList.add('selected');
    bookingData.serviceId = el.dataset.serviceId || '';
    bookingData.serviceLabel = el.querySelector('h4')?.textContent || '';
  };

  window.selectStaff = function(el) {
    document.querySelectorAll('.staff-opt').forEach(o => o.classList.remove('selected'));
    el.classList.add('selected');
    bookingData.staffId = el.dataset.staffId || '';
    bookingData.staffLabel = el.querySelector('h4')?.textContent || '';
  };

  function updateStepIndicators() {
    document.querySelectorAll('.step-ind').forEach(ind => {
      const step = parseInt(ind.dataset.step);
      ind.classList.remove('active', 'done');
      if (step === currentStep) ind.classList.add('active');
      else if (step < currentStep) ind.classList.add('done');
    });
    document.querySelectorAll('.step-line').forEach((line, i) => {
      line.classList.toggle('done', i + 1 < currentStep);
    });
  }

  window.nextStep = function() {
    if (currentStep === 1 && !bookingData.serviceId) { showToast('يرجى اختيار خدمة أولاً'); return; }
    if (currentStep === 2 && !bookingData.staffId) { showToast('يرجى اختيار الخبيرة أولاً'); return; }
    if (currentStep === 3) {
      bookingData.date = document.getElementById('bookDate')?.value || '';
      bookingData.time = document.getElementById('bookTime')?.value || '';
      bookingData.notes = document.getElementById('bookNotes')?.value || '';
      if (!bookingData.date || !bookingData.time) { showToast('يرجى اختيار التاريخ والوقت'); return; }
      updateSummary();
    }

    document.querySelector(`.step-content[data-step="${currentStep}"]`)?.classList.remove('active');
    currentStep = Math.min(currentStep + 1, 4);
    document.querySelector(`.step-content[data-step="${currentStep}"]`)?.classList.add('active');
    updateStepIndicators();
  };

  window.prevStep = function() {
    document.querySelector(`.step-content[data-step="${currentStep}"]`)?.classList.remove('active');
    currentStep = Math.max(currentStep - 1, 1);
    document.querySelector(`.step-content[data-step="${currentStep}"]`)?.classList.add('active');
    updateStepIndicators();
  };

  function updateSummary() {
    const summary = document.getElementById('bookingSummary');
    if (!summary) return;
    summary.innerHTML = `
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;font-size:14px">
        <div><span style="color:rgba(250,248,245,0.5)">الخدمة:</span><br><strong style="color:var(--gold)">${bookingData.serviceLabel}</strong></div>
        <div><span style="color:rgba(250,248,245,0.5)">الخبيرة:</span><br><strong style="color:var(--gold)">${bookingData.staffLabel}</strong></div>
        <div><span style="color:rgba(250,248,245,0.5)">التاريخ:</span><br><strong style="color:var(--gold)">${bookingData.date}</strong></div>
        <div><span style="color:rgba(250,248,245,0.5)">الوقت:</span><br><strong style="color:var(--gold)">${bookingData.time}</strong></div>
      </div>
    `;
  }

  window.confirmBooking = function() {
    const name = document.getElementById('bookingName')?.value.trim();
    const phone = document.getElementById('bookingPhone')?.value.trim();
    const email = document.getElementById('bookingEmail')?.value.trim();

    if (!name || !phone) {
      showToast('يرجى إدخال الاسم ورقم الهاتف لإنهاء الحجز');
      return;
    }

    if (!bookingData.serviceId || !bookingData.staffId) {
      showToast('يرجى اختيار الخدمة والخبيرة أولاً');
      return;
    }

    const serviceInput = document.getElementById('bookingServiceId');
    const staffInput = document.getElementById('bookingStaffId');
    const notesInput = document.getElementById('bookNotes');
    const form = document.getElementById('bookingForm');
    if (serviceInput) serviceInput.value = bookingData.serviceId;
    if (staffInput) staffInput.value = bookingData.staffId;
    if (notesInput) notesInput.value = bookingData.notes || '';
    form?.submit();
  };

  window.sendConsultation = function() {
    const whatsappNumber = '+201044925333';
    const name = document.getElementById('consultName')?.value.trim();
    const phone = document.getElementById('consultPhone')?.value.trim();
    const type = document.getElementById('consultType')?.value.trim();
    const message = document.getElementById('consultMessage')?.value.trim();

    if (!name || !phone || !message) {
      showToast('يرجى إدخال الاسم، رقم الهاتف، ونص الاستشارة');
      return;
    }

    const whatsappText = [
      'استشارة جديدة من موقع AUR Beauty Lounge',
      `الاسم: ${name}`,
      `رقم الهاتف: ${phone}`,
      `نوع الاستشارة: ${type}`,
      `الرسالة: ${message}`,
    ].map(encodeURIComponent).join('%0A');

    const url = `https://wa.me/${whatsappNumber}?text=${whatsappText}`;
    window.open(url, '_blank');
    showToast('جارٍ فتح واتساب لإرسال الاستشارة');
  };

  window.contactPackage = function(packageName) {
    const whatsappNumber = '+201044925333';
    const message = [
      'طلب باقة عرائس من موقع AUR Beauty Lounge',
      `نوع الباقة: ${packageName}`,
      'أرغب في الحصول على تفاصيل أكثر حول هذه الباقة.',
    ].map(encodeURIComponent).join('%0A');
    const url = `https://wa.me/${whatsappNumber}?text=${message}`;
    window.open(url, '_blank');
    showToast('جارٍ فتح واتساب لإرسال طلب الباقة');
  };
}

function initGalleryFilter() {
  const filterBtns = document.querySelectorAll('.gallery-filter-btn');
  const items = document.querySelectorAll('.gallery-item');
  const emptyMsg = document.getElementById('galleryEmpty');
  const lightbox = document.getElementById('lightbox');

  filterBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      filterBtns.forEach(b => b.classList.remove('active'));
      btn.classList.add('active');

      const filter = btn.dataset.filter;
      let count = 0;
      items.forEach(item => {
        const match = filter === 'all' || item.dataset.category === filter;
        if (match) {
          item.classList.remove('hide');
          item.classList.add('show');
          item.style.display = '';
          count++;
        } else {
          item.classList.remove('show');
          item.classList.add('hide');
          setTimeout(() => {
            if (item.classList.contains('hide')) item.style.display = 'none';
          }, 400);
        }
      });
      emptyMsg?.classList.toggle('visible', count === 0);
    });
  });

  items.forEach(item => {
    item.addEventListener('click', () => {
      if (item.classList.contains('hide')) return;
      const visibleItems = Array.from(items).filter(i => !i.classList.contains('hide'));
      const currentIndex = visibleItems.indexOf(item);
      const lightboxImg = document.getElementById('lightboxImg');
      const lightboxCaption = document.getElementById('lightboxCaption');
      if (!lightbox || !lightboxImg || !lightboxCaption) return;
      lightboxImg.src = item.querySelector('img')?.src || '';
      lightboxCaption.textContent = item.dataset.caption || '';
      lightbox.classList.add('active');
      document.body.style.overflow = 'hidden';
    });
  });
}

function initSmoothScroll() {
  document.querySelectorAll('a[href^="#"]').forEach(a => {
    a.addEventListener('click', e => {
      e.preventDefault();
      const target = document.querySelector(a.getAttribute('href'));
      if (target) {
        target.scrollIntoView({ behavior: 'smooth', block: 'start' });
      }
    });
  });
}

function showToast(msg) {
  const toast = document.getElementById('toast');
  if (!toast) return;
  toast.textContent = msg;
  toast.classList.add('show');
  setTimeout(() => toast.classList.remove('show'), 3000);
}
