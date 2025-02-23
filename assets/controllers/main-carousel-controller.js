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
    return parseFloat(
      getComputedStyle(document.documentElement).getPropertyValue(
        '--bs-breakpoint-' + breakpoint
      )
    );
  }

  connect() {
    new Swiper('#swiper-' + this.idValue + '-main', {
      modules: [Navigation],
      direction: 'horizontal',
      loop: false,
      spaceBetween: 15,
      slidesPerView: 1,

      // when window width is >= 768px (md in boostrap)
      breakpoints: {
        [this.getBreakpoint('md')]: {
          slidesPerView: 'auto',
          spaceBetween: 15,
        },
      },

      // Navigation arrows
      navigation: {
        enabled: true,
        prevEl: '#swiper-' + this.idValue + '-button-prev',
        nextEl: '#swiper-' + this.idValue + '-button-next',
        disabledClass: 'opacity-0',
        // hiddenClass: 'd-none',
        // lockClass: 'd-none',
      },
    });
  }
}
