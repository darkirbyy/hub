import { Controller } from '@hotwired/stimulus';
import { Swiper } from 'swiper';
import { Navigation } from 'swiper/modules';

/**
 * Stimulus controller that enable and configure swiper for the homepage carousels
 */
export default class extends Controller {
  static values = {
    id: String,
  };

  getBreakpoint(breakpoint) {
    return parseFloat(getComputedStyle(document.documentElement).getPropertyValue('--bs-breakpoint-' + breakpoint));
  }

  connect() {
    const buttonPrevId = '#swiper-' + this.idValue + '-button-prev';
    const buttonNextId = '#swiper-' + this.idValue + '-button-next';

    new Swiper('#swiper-' + this.idValue + '-main', {
      modules: [Navigation],
      direction: 'horizontal',
      loop: false,
      spaceBetween: 50,
      slidesPerView: 1,
      speed: 300,

      // when window width is >= 768px (md in boostrap)
      breakpoints: {
        [this.getBreakpoint('md')]: {
          slidesPerView: 'auto',
          spaceBetween: 30,
        },
      },

      // Navigation arrows
      navigation: {
        enabled: true,
        prevEl: buttonPrevId,
        nextEl: buttonNextId,
        disabledClass: 'opacity-0',
      },
    });
  }
}
