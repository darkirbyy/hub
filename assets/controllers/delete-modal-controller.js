import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
  static targets = ['form', 'body', 'hidden'];

  connect() {
    this.element.addEventListener('show.bs.modal', (event) => {
      const button = event.relatedTarget;
      const path = button.getAttribute('data-bs-path');
      const body = button.getAttribute('data-bs-body');
      const token = button.getAttribute('data-bs-token');
      this.formTarget.setAttribute('action', path);
      this.bodyTarget.innerHTML = body;
      this.hiddenTarget.value = token;
    });
  }
}
