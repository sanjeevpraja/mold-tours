document.addEventListener("DOMContentLoaded", function() {
  const carousels = document.querySelectorAll(".mold-tour-gallery");

  carousels.forEach(el => {
    const speed = parseInt(el.getAttribute('data-attr-speed')) || 600;
    const autoDelay = parseInt(el.getAttribute('data-attr-auto-delay')) || 3000;
    const itemPerView = parseInt(el.getAttribute('data-attr-item-per-view')) || 1;
    const pagination = el.getAttribute('data-attr-pagination') === 'true';
    const navigation = el.getAttribute('data-attr-navigation') === 'true';
    const effect = el.getAttribute('data-attr-effect') || 'slide';
    const gap = parseInt(el.getAttribute('data-attr-gap')) || 10;
    const equalHeight = el.getAttribute('data-attr-equal-height') === 'true';

    const swiperConfig = {
      spaceBetween: gap,
      slidesPerView: itemPerView,
      centeredSlides: true,
      loop: true,
      speed: speed,
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
    if (['cards','fade','coverflow','flip','cube','creative'].includes(effect)) {
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
});
