import { Controller } from '@hotwired/stimulus';

/**
 * Stimulus controller that MUST be placed on a delete modal, to customize parts of it when displayed
 * Customizable parts are: form action path, modal body text
 */
export default class extends Controller {
  static targets = ['form', 'body', 'hidden'];

  connect() {
    this.element.addEventListener('show.bs.modal', (event) => {
      const button = event.relatedTarget;
      const path = button.getAttribute('data-bs-path');
      const body = button.getAttribute('data-bs-body');
      this.formTarget.setAttribute('action', path);
      this.bodyTarget.innerHTML = body;
    });
  }
}
