document.addEventListener(
  "DOMContentLoaded",
  function () {
    var swiper = new Swiper(".js-slider", {
      slidesPerView: 1,
      spaceBetween: 30,
      loop: true,
      navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
      },

      pagination: {
        el: ".swiper-pagination",
        clickable: true,
      },
    });
  },
  false
);
