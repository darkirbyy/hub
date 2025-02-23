import { Controller } from '@hotwired/stimulus';

/**
 * Stimulus controller that display live the password strength when modifying the password,
 * using the same algorithm as the php symfony backend validation.
 */
export default class extends Controller {
  static targets = ['input', 'text', 'bar'];
  static values = {
    strengthTexts: Array,
  };

  static PasswordStrength = {
    STRENGTH_VERY_WEAK: 0,
    STRENGTH_WEAK: 1,
    STRENGTH_MEDIUM: 2,
    STRENGTH_STRONG: 3,
    STRENGTH_VERY_STRONG: 4,
  };

  initialize() {
    this.currentStrength = 0;
    this.barOuter = null;
    this.barInner = null;
  }

  connect() {
    this.barOuter = this.barTarget;
    this.barInner = this.barTarget.querySelector('.progress-bar');

    this.inputTarget.addEventListener('input', (event) => {
      let newStrength = this.estimateStrength(event.target.value);
      if (newStrength != this.currentStrength) {
        this.changeStrength(newStrength);
      }
    });

    this.changeStrength(0);
  }

  changeStrength(newStrength) {
    this.currentStrength = newStrength;
    this.barInner.classList = '';

    let barColor = '';
    if (this.currentStrength < 1) {
      barColor = 'bg-danger';
    } else if (this.currentStrength <= 2) {
      barColor = 'bg-warning';
    } else {
      barColor = 'bg-success';
    }

    this.barOuter.setAttribute('aria-valuenow', this.currentStrength * 25);
    this.barInner.classList.add(
      'progress-bar',
      'w-' + this.currentStrength * 25,
      barColor
    );
    this.textTarget.innerText = this.strengthTextsValue[this.currentStrength];
  }

  estimateStrength(password) {
    const {
      STRENGTH_VERY_WEAK,
      STRENGTH_WEAK,
      STRENGTH_MEDIUM,
      STRENGTH_STRONG,
      STRENGTH_VERY_STRONG,
    } = this.constructor.PasswordStrength;

    if (!password.length) {
      return STRENGTH_VERY_WEAK;
    }

    const charCounts = {};
    for (let char of password) {
      charCounts[char.charCodeAt(0)] =
        (charCounts[char.charCodeAt(0)] || 0) + 1;
    }

    let control = 0,
      digit = 0,
      upper = 0,
      lower = 0,
      symbol = 0,
      other = 0;
    for (let chr in charCounts) {
      chr = parseInt(chr);
      if (chr < 32 || chr === 127) {
        control = 33;
      } else if (chr >= 48 && chr <= 57) {
        digit = 10;
      } else if (chr >= 65 && chr <= 90) {
        upper = 26;
      } else if (chr >= 97 && chr <= 122) {
        lower = 26;
      } else if (chr >= 128) {
        other = 128;
      } else {
        symbol = 33;
      }
    }

    const pool = lower + upper + digit + symbol + control + other;
    const chars = Object.keys(charCounts).length;
    const entropy =
      chars * Math.log2(pool) + (password.length - chars) * Math.log2(chars);

    if (entropy >= 120) {
      return STRENGTH_VERY_STRONG;
    } else if (entropy >= 100) {
      return STRENGTH_STRONG;
    } else if (entropy >= 80) {
      return STRENGTH_MEDIUM;
    } else if (entropy >= 60) {
      return STRENGTH_WEAK;
    } else {
      return STRENGTH_VERY_WEAK;
    }
  }
}
