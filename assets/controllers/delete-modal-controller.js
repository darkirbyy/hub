import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
  static targets = ['form', 'hidden'];

  connect() {
    this.element.addEventListener('show.bs.modal', (event) => {
      const button = event.relatedTarget;
      const path = button.getAttribute('data-bs-path');
      const token = button.getAttribute('data-bs-token');
      this.formTarget.setAttribute('action', path);
      this.hiddenTarget.value = token;
    });
  }
}
