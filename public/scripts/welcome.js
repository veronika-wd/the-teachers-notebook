// Свайпер

const swiperSchool = new Swiper('.swiper-school', {
  slidesPerView: 1,
  effect: 'slide',
  spaceBetween: 20,
  loop: true,

  autoplay: {
    delay: 5000,
  }, 

  pagination: {
    el: '.swiper-pagination-school',
    type: 'bullets',
    
  }
});

const swiperStaff = new Swiper('.swiper-staff', {
  slidesPerView: 1,
  loop: true,
  effect: 'coverflow',

  // autoplay: {
  //   delay: 5000,
  // }, 

  pagination: {
    el: '.swiper-pagination',
    type: 'bullets',
    dynamicBullets: true,
  }
});

// Анимация текста

const targets = document.querySelectorAll('.about-block');

const aboutAnim = () => {
  let windowCenter = (window.innerHeight / 2) + window.scrollY + 100;
  targets.forEach(el => {
    let scrollOffset = el.offsetTop;
    if( windowCenter >= scrollOffset){
      el.classList.add('active');
      windowCenter = windowCenter + 100;
    } else{
      el.classList.remove('active');
    }
  })
}

// Анимация шапки

aboutAnim();

window.addEventListener('scroll', () => {
  aboutAnim();
})

