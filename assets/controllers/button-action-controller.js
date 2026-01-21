import { Controller } from '@hotwired/stimulus';

/**
 * Stimulus controller that SHOULD be placed on a form tag, to enable action buttons in form.
 * Currently supported actions are :
 * - clear: to empty a text/textarea field
 * - reveal: to show/hide a password field
 */
export default class extends Controller {
  static targets = ['clear', 'reveal'];

  connect() {
    this.clearTargets.forEach((item) => {
      const faSpan = item.querySelector('span');
      faSpan.classList.add('fa-xmark');

      item.addEventListener('click', () => {
        const inputField = document.getElementById(item.getAttribute('data-button-action-id'));
        inputField.value = '';
        item.blur();
      });
    });

    this.revealTargets.forEach((item) => {
      const faSpan = item.querySelector('span');
      const inputField = document.getElementById(item.getAttribute('data-button-action-id'));
      faSpan.classList.remove('fa-xmark');
      faSpan.classList.add('fa-eye-slash');

      item.addEventListener('click', () => {
        if (inputField.type == 'text') {
          faSpan.classList.remove('fa-eye');
          faSpan.classList.add('fa-eye-slash');
          inputField.type = 'password';
        } else {
          faSpan.classList.remove('fa-eye-slash');
          faSpan.classList.add('fa-eye');
          inputField.type = 'text';
        }
        item.blur();
      });
    });
  }
}
