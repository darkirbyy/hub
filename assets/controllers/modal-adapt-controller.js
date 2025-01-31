import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
  connect() {
    this.element.addEventListener('show.bs.modal', (event) => {
      const button = event.relatedTarget;
      const object_id = button.getAttribute('data-bs-object-id');
      console.log(object_id);
    });
  }
}
