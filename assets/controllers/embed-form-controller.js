import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
  static targets = ['collectionContainer'];

  static values = {
    index: Number,
    prototype: String,
  };

  connect() {
    console.log(this.element);
  }

  addCollectionElement() {
    const item = document.createElement('li');
    item.innerHTML = this.prototypeValue.replace(/__name__/g, this.indexValue);
    this.collectionContainerTarget.appendChild(item);
    this.indexValue++;
  }
}
