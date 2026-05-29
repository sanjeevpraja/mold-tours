document.addEventListener("DOMContentLoaded", function () {

  /* ===================================================================
   *  1. SWIPER SLIDER INIT (existing)
   * =================================================================== */
  const carousels = document.querySelectorAll(".mold-tour-gallery");

  carousels.forEach(el => {
    const speed       = parseInt(el.getAttribute('data-attr-speed')) || 600;
    const autoDelay   = parseInt(el.getAttribute('data-attr-auto-delay')) || 3000;
    const itemPerView = parseInt(el.getAttribute('data-attr-slides-per-view')) || 1;
    const pagination  = el.getAttribute('data-attr-pagination') === 'true';
    const navigation  = el.getAttribute('data-attr-navigation') === 'true';
    const effect      = el.getAttribute('data-attr-effect') || 'slide';
    const gap         = parseInt(el.getAttribute('data-attr-slider-gap')) || 0;
    const height      = el.getAttribute('data-attr-slider-height') || '600px';
    const equalHeight = el.getAttribute('data-attr-equal-height') === 'true';

    const swiperConfig = {
      spaceBetween: gap,
      slidesPerView: itemPerView,
      centeredSlides: true,
      loop: true,
      speed: speed,
      height: height,
      autoplay: {
        delay: autoDelay,
        disableOnInteraction: false,
      },
      pagination: pagination ? {
        el: el.querySelector('.swiper-pagination'),
        clickable: true,
      } : false,
      navigation: navigation ? {
        nextEl: el.querySelector('.swiper-button-next'),
        prevEl: el.querySelector('.swiper-button-prev'),
      } : false,
      on: {
        init() {
          equalizeSlideHeights(el, equalHeight);
        },
        resize() {
          equalizeSlideHeights(el, equalHeight);
        }
      },
      breakpoints: {
        0: { slidesPerView: 1 },
        768: { slidesPerView: 1 },
        1024: { slidesPerView: 1 },
        1200: { slidesPerView: itemPerView },
      }
    };

    // Effects
    if (['cards', 'fade', 'coverflow', 'flip', 'cube', 'creative'].includes(effect)) {
      swiperConfig.effect = effect;
    } else {
      swiperConfig.effect = 'slide';
    }

    if (effect === 'coverflow') {
      swiperConfig.coverflowEffect = {
        rotate: 0,
        stretch: 50,
        depth: 100,
        modifier: 1,
        slideShadows: true
      };
    } else if (effect === 'creative') {
      swiperConfig.grabCursor = true;
      swiperConfig.creativeEffect = {
        prev: {
          shadow: true,
          translate: ["-125%", 0, -800],
          rotate: [0, 0, -20],
        },
        next: {
          shadow: true,
          translate: ["125%", 0, -800],
          rotate: [0, 0, 20],
        },
      };
    }

    new Swiper(el, swiperConfig);
  });

  function equalizeSlideHeights(carousel, equalHeight) {
    if (!equalHeight) return;
    const slides = carousel.querySelectorAll('.swiper-slide');
    let maxHeight = 0;
    slides.forEach(slide => {
      slide.style.height = 'auto';
      maxHeight = Math.max(maxHeight, slide.offsetHeight);
    });
    slides.forEach(slide => {
      slide.style.height = `${maxHeight}px`;
    });
  }

  /* ===================================================================
   *  2. GRID – lazy "loaded" class for fade-in
   * =================================================================== */
  document.querySelectorAll('.mold-tour-gallery-grid .mold-gallery-grid-item img').forEach(img => {
    if (img.complete) {
      img.classList.add('loaded');
    } else {
      img.addEventListener('load', () => img.classList.add('loaded'), { once: true });
    }
  });

  /* ===================================================================
   *  3. LIGHTBOX
   * =================================================================== */
  const lightboxContainers = document.querySelectorAll('.has-lightbox');
  if (!lightboxContainers.length) return;

  // Build overlay DOM once
  const overlay = document.createElement('div');
  overlay.className = 'mold-lightbox-overlay';
  overlay.innerHTML = `
    <button class="mold-lightbox-close" aria-label="Close">&times;</button>
    <button class="mold-lightbox-prev" aria-label="Previous">&#8249;</button>
    <button class="mold-lightbox-next" aria-label="Next">&#8250;</button>
    <div class="mold-lightbox-img-wrap">
      <img src="" alt="" />
    </div>
    <div class="mold-lightbox-counter"></div>
  `;
  document.body.appendChild(overlay);

  const lbImg     = overlay.querySelector('.mold-lightbox-img-wrap img');
  const lbClose   = overlay.querySelector('.mold-lightbox-close');
  const lbPrev    = overlay.querySelector('.mold-lightbox-prev');
  const lbNext    = overlay.querySelector('.mold-lightbox-next');
  const lbCounter = overlay.querySelector('.mold-lightbox-counter');

  let currentImages = [];
  let currentIndex  = 0;

  function openLightbox(images, startIndex) {
    currentImages = images;
    currentIndex  = startIndex;
    showImage();
    overlay.classList.add('is-active');
    document.body.style.overflow = 'hidden';
  }

  function closeLightbox() {
    overlay.classList.remove('is-active');
    document.body.style.overflow = '';
    lbImg.classList.remove('is-visible');
  }

  function showImage() {
    lbImg.classList.remove('is-visible');
    const src = currentImages[currentIndex];

    // Small delay so CSS transition replays
    requestAnimationFrame(() => {
      lbImg.src = src;
      lbImg.onload = () => lbImg.classList.add('is-visible');
      // Fallback if cached
      if (lbImg.complete) lbImg.classList.add('is-visible');
    });

    lbCounter.textContent = `${currentIndex + 1} / ${currentImages.length}`;

    // Hide arrows when only 1 image
    const showArrows = currentImages.length > 1;
    lbPrev.style.display = showArrows ? '' : 'none';
    lbNext.style.display = showArrows ? '' : 'none';
  }

  function navigate(dir) {
    currentIndex = (currentIndex + dir + currentImages.length) % currentImages.length;
    showImage();
  }

  // Attach clicks per lightbox-enabled container
  lightboxContainers.forEach(container => {
    container.addEventListener('click', (e) => {
      const clickedImg = e.target.closest('img');
      if (!clickedImg) return;

      const allImgs = Array.from(container.querySelectorAll('img'));
      const srcs    = allImgs.map(img => img.getAttribute('data-full') || img.src);
      const idx     = allImgs.indexOf(clickedImg);

      openLightbox(srcs, Math.max(idx, 0));
    });
  });

  // Close
  lbClose.addEventListener('click', closeLightbox);
  overlay.addEventListener('click', (e) => {
    if (e.target === overlay) closeLightbox();
  });

  // Navigation
  lbPrev.addEventListener('click', (e) => { e.stopPropagation(); navigate(-1); });
  lbNext.addEventListener('click', (e) => { e.stopPropagation(); navigate(1); });

  // Keyboard
  document.addEventListener('keydown', (e) => {
    if (!overlay.classList.contains('is-active')) return;
    if (e.key === 'Escape')     closeLightbox();
    if (e.key === 'ArrowLeft')  navigate(-1);
    if (e.key === 'ArrowRight') navigate(1);
  });

  // Swipe support (mobile)
  let touchStartX = 0;
  overlay.addEventListener('touchstart', (e) => {
    touchStartX = e.changedTouches[0].clientX;
  }, { passive: true });

  overlay.addEventListener('touchend', (e) => {
    const diff = e.changedTouches[0].clientX - touchStartX;
    if (Math.abs(diff) > 50) {
      navigate(diff > 0 ? -1 : 1);
    }
  }, { passive: true });
});
