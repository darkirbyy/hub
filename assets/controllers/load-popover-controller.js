import { Controller } from '@hotwired/stimulus';
import { Popover } from 'bootstrap';

export default class extends Controller {
  connect() {
    // Enable popover
    document.addEventListener('DOMContentLoaded', function () {
      const popoverTriggerList = document.querySelectorAll(
        '[data-bs-toggle="popover"]'
      );
      popoverTriggerList.forEach(
        (popoverTriggerEl) => new Popover(popoverTriggerEl)
      );
    });
  }
}
