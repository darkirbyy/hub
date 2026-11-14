import { Controller } from '@hotwired/stimulus';

/**
 * TODO : comment
 */
export default class extends Controller {
  static targets = ['iframe'];
  static values = {
    url: String,
  };

  connect() {
    // todo : reeanble
    // if (sessionStorage.getItem('sso_checked')) return;
    // sessionStorage.setItem('sso_checked', '1');

    this.iframeTarget.src = this.urlValue;
    window.addEventListener('message', this.onMessage.bind(this), { once: true });
    this.timeout = setTimeout(() => this.iframeTarget.remove(), 5000);
  }

  onMessage(event) {
    console.log(event);
    if (event.origin !== window.location.origin) return;
    clearTimeout(this.timeout);
    if (event.data === 'authenticated') {
      window.location.reload();
    }
  }
}
