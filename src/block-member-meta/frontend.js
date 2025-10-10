document.addEventListener("DOMContentLoaded", () => {
  const items = document.querySelectorAll('.aurora__item');
  if(items.length > 0){
    items.forEach((el) => {
      el.style.top = `${Math.random() * 80}%`;
      el.style.left = `${Math.random() * 80}%`;
    });
  }
});

//enqued so not needed
// import { gsap } from "gsap";
// import { ScrollTrigger } from "gsap/ScrollTrigger";

// not needed as AOS is working
// document.addEventListener('DOMContentLoaded', () => {
//     gsap.registerPlugin(ScrollTrigger);
//     ScrollTrigger.refresh();
    
//     document.querySelectorAll(".wp-mold-inline-banner-block").forEach(block => {
//         gsap.from(block, {
//             scrollTrigger: {
//                 trigger: block,
//                 start: "top 80%",
//                 toggleActions: "play pause resume reset",
//             },
//             opacity: 0,
//             y: 100,
//             duration: 1,
//             ease: "power2.out"
//         });
//     });
// });

