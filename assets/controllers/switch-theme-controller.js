import { Controller } from '@hotwired/stimulus';

/**
 * Stimulus controller that MUST be placed on the switch theme modal
 */
export default class extends Controller {
  static targets = ['radio'];

  connect() {
    // Setting the preferred theme on page load
    this.setPreferredTheme(this.getPreferredTheme());

    // If there is a theme stored, selecting the corresponding radio button when opening the modal
    this.element.addEventListener('show.bs.modal', () => {
      const storedTheme = localStorage.getItem('theme');
      if (storedTheme) {
        const radioId = 'theme-modal-' + storedTheme;
        const activeRadio = document.getElementById(radioId);
        activeRadio.checked = true;
      }
    });

    // Changing theme and storing it when selecting a radio button
    this.radioTargets.forEach((radio) => {
      radio.addEventListener('change', (event) => {
        const newTheme = event.target.id.split('-').pop();
        this.setPreferredTheme(newTheme);
      });
    });
  }

  // Return the stored theme if exists, or auto otherwise
  getPreferredTheme() {
    const storedTheme = localStorage.getItem('theme');
    return storedTheme ? storedTheme : 'auto';
  }

  // Set the theme using the system theme in auto, or directly the value for light/dark
  setPreferredTheme(theme) {
    localStorage.setItem('theme', theme);
    if (theme === 'auto') {
      this.setCurrentTheme(this.getSystemTheme());
    } else {
      this.setCurrentTheme(theme);
    }
  }

  // WILL return light/dark from the user preferences
  getSystemTheme() {
    return window.matchMedia('(prefers-color-scheme: dark)').matches
      ? 'dark'
      : 'light';
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
