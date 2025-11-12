import { Controller } from '@hotwired/stimulus';
import * as Turbo from '@hotwired/turbo';

/**
 * Stimulus controller that MUST be placed on a form tag within a turbo-frame.
 */
export default class extends Controller {
  static values = {
    action: String,
  };

  connect() {
    document.addEventListener('turbo:frame-missing', this.missing.bind(this));
    this.element.addEventListener('turbo:submit-end', this.next.bind(this));
  }

  missing(event) {
    event.preventDefault();
    const response = event.detail.response;
    const visit = event.detail.visit;
    visit(response); // you have to render your "application" layout for this
  }

  next(event) {
    if (event.detail.success) {
      const fetchResponse = event.detail.fetchResponse;
      const action = this.actionValue ? this.actionValue : 'advance';
      Turbo.visit(fetchResponse.response.url, { action: action });
    }
  }
}
