import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
  connect() {
    const clearButtonList = document.querySelectorAll('[data-app-clear-id]');
    clearButtonList.forEach((item) => {
      item.addEventListener('click', () => {
        const inputField = document.getElementById(
          item.getAttribute('data-app-clear-id')
        );
        inputField.value = '';
        item.blur();
      });
    });
  }
}
