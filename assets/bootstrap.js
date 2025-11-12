import { startStimulusApp } from '@symfony/stimulus-bridge';
import * as Turbo from '@hotwired/turbo';

// Registers Stimulus controllers from controllers.json and in the controllers/ directory
export const app = startStimulusApp(
  require.context(
    '@symfony/stimulus-bridge/lazy-controller-loader!./controllers',
    true,
    /\.[jt]sx?$/
  )
);

// Follow the "redirect" action in Turbo Streams
Turbo.StreamActions.redirect = function () {
  Turbo.visit(this.target);
};

// Display the error page if a turbo-frame is missing (DEV only)
if (process.env.NODE_ENV === 'development') {
  document.addEventListener('turbo:frame-missing', (event) => {
    event.preventDefault();
    event.detail.visit(event.detail.response);
  });
}
