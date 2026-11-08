
const menu = document.querySelector('.mobile-menu');
if (menu) {
  const summary = menu.querySelector('summary');
  menu.addEventListener('click', event => {
    if (event.target.closest('a')) {
      menu.open = false;
    }
  });
  document.addEventListener('click', event => {
    if (menu.open && !menu.contains(event.target)) {
      menu.open = false;
    }
  });
  document.addEventListener('keydown', event => {
    if (event.key === 'Escape' && menu.open) {
      menu.open = false;
      summary.focus();
    }
  });
  menu.addEventListener('focusout', event => {
    if (event.relatedTarget && !menu.contains(event.relatedTarget)) {
      menu.open = false;
    }
  });
}

const photo = document.querySelector('.visit-photo img');
const reducedMotion = matchMedia('(prefers-reduced-motion: reduce)');
if (photo?.animate && 'IntersectionObserver' in window && !reducedMotion.matches) {
  let reveal;
  const observer = new IntersectionObserver(entries => {
    if (!entries.some(entry => entry.isIntersecting)) {
      return;
    }
    observer.disconnect();
    if (!reducedMotion.matches) {
      reveal = photo.animate([
        { clipPath: 'circle(28% at 52% 67%)' },
        { clipPath: 'circle(110% at 52% 67%)' }
      ], { duration: 960, easing: 'cubic-bezier(.22,1,.36,1)' });
    }
  }, { threshold: .25 });
  observer.observe(photo.parentElement);
  reducedMotion.addEventListener('change', event => {
    if (event.matches) {
      reveal?.cancel();
      observer.disconnect();
    }
  });
}
