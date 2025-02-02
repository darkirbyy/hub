import { Controller } from '@hotwired/stimulus';
import { Popover } from 'bootstrap';

export default class extends Controller {
  connect() {
    // Enable popovers and clear buttons
    // document.addEventListener('DOMContentLoaded', function () {
    const popoverTriggerList = document.querySelectorAll(
      '[data-bs-toggle="popover"]'
    );
    popoverTriggerList.forEach((item) => new Popover(item));

    const clearButtonList = document.querySelectorAll('[data-app-clear-id]');
    clearButtonList.forEach((item) => {
      item.addEventListener('click', () => {
        const inputField = document.getElementById(
          item.getAttribute('data-app-clear-id')
        );
        inputField.value = '';
        inputField.focus();
      });
    });
    // });
  }
}
