import { Controller } from '@hotwired/stimulus';

/**
 * Stimulus controller that MUST be placed on the switch theme modal
 */
export default class extends Controller {
  static targets = ['button'];

  initialize() {
    this.storagePath = 'hub/theme';
  }

  connect() {
    // Setting the preferred theme on page load
    this.setPreferredTheme(this.getPreferredTheme());

    // If there is a theme stored, activate the corresponding button when opening the modal
    this.element.addEventListener('show.bs.modal', () => {
      const storedTheme = localStorage.getItem(this.storagePath);
      if (storedTheme) {
        const buttonId = 'theme-modal-' + storedTheme;
        const button = document.getElementById(buttonId);
        this.activateButton(button);
      }
    });

    // Changing theme and storing it when selecting a button
    this.buttonTargets.forEach((button) => {
      button.addEventListener('click', (event) => {
        const newTheme = event.currentTarget.id.split('-').pop();
        this.setPreferredTheme(newTheme);
        this.activateButton(event.currentTarget);
      });
    });
  }

  activateButton(button) {
    this.buttonTargets.forEach((button) => {
      button.classList.remove('active');
    });
    button.classList.add('active');
  }

  // Return the stored theme if exists, or auto otherwise
  getPreferredTheme() {
    const storedTheme = localStorage.getItem(this.storagePath);
    return storedTheme ? storedTheme : 'auto';
  }

  // Set the theme using the system theme in auto, or directly the value for light/dark
  setPreferredTheme(theme) {
    localStorage.setItem(this.storagePath, theme);
    if (theme === 'auto') {
      this.setCurrentTheme(this.getSystemTheme());
    } else {
      this.setCurrentTheme(theme);
    }
  }

  // WILL return light/dark from the user preferences
  getSystemTheme() {
    return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
  }

  // WILL return light/dark from the html tag
  getCurrentTheme() {
    return document.documentElement.getAttribute('data-bs-theme');
  }

  // MUST receive light/dark as an argument for the html tag
  setCurrentTheme(theme) {
    document.documentElement.setAttribute('data-bs-theme', theme);
  }
}
