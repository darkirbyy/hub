import { Controller } from '@hotwired/stimulus';
import { Popover } from 'bootstrap';

/**
 * Stimulus controller that CAN be placed on any tag, to enable any popover nested inside it.
 */
export default class extends Controller {
  connect() {
    const popoverTriggerList = this.element.querySelectorAll('[data-bs-toggle="popover"]');
    popoverTriggerList.forEach(
      (item) =>
        new Popover(item, {
          container: item.parentElement.parentElement,
          trigger: 'click hover',
        })
    );
  }
}
