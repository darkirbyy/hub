import { Controller } from '@hotwired/stimulus';
import * as Turbo from '@hotwired/turbo';

/**
 * Stimulus controller that MUST be placed on a form tag within a turbo-frame, to follow redirection on success.
 */
export default class extends Controller {
  connect() {
    this.element.addEventListener('turbo:submit-end', this.next.bind(this));
  }

  next(event) {
    if (event.detail.success) {
      const fetchResponse = event.detail.fetchResponse;

      // history.pushState(
      //   { turbo_frame_history: true },
      //   '',
      //   fetchResponse.response.url
      // );

      Turbo.visit(fetchResponse.response.url);
    }
  }
}
