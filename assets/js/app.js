const toggle = document.querySelector('.menu-toggle');
const nav = document.querySelector('#main-nav');
toggle?.addEventListener('click', () => {
  const open = toggle.getAttribute('aria-expanded') === 'true';
  toggle.setAttribute('aria-expanded', String(!open));
  nav.classList.toggle('open');
});

document.querySelectorAll('#main-nav a').forEach(link => link.addEventListener('click', () => {
  nav.classList.remove('open');
  toggle?.setAttribute('aria-expanded', 'false');
}));

const renderingsButton = document.querySelector('.show-renderings');
const extraRenderings = document.querySelectorAll('.rendering-extra');

renderingsButton?.addEventListener('click', () => {
  const isOpen = renderingsButton.getAttribute('aria-expanded') === 'true';

  extraRenderings.forEach((card) => {
    card.hidden = isOpen;
    if (!isOpen) {
      const frame = card.querySelector('iframe[data-src]');
      if (frame && !frame.src) frame.src = frame.dataset.src;
    }
  });

  renderingsButton.setAttribute('aria-expanded', String(!isOpen));
  renderingsButton.querySelector('span').textContent = isOpen ? 'Mehr anzeigen' : 'Weniger anzeigen';
  renderingsButton.querySelector('i').textContent = isOpen ? '↓' : '↑';
});

const propertyFilter = document.querySelector('.property-filter form');
const propertyResults = document.querySelector('.listings');

if (propertyFilter && propertyResults) {
  let filterTimer;
  let activeRequest;

  const updateProperties = async () => {
    activeRequest?.abort();
    activeRequest = new AbortController();
    const params = new URLSearchParams(new FormData(propertyFilter));
    const baseUrl = propertyFilter.getAttribute('action') || window.location.pathname;
    const requestUrl = `${baseUrl}?${params.toString()}`;
    propertyResults.classList.add('is-filtering');
    propertyFilter.setAttribute('aria-busy', 'true');

    try {
      const response = await fetch(requestUrl, { signal: activeRequest.signal });
      if (!response.ok) throw new Error('Filter request failed');
      const html = await response.text();
      const nextPage = new DOMParser().parseFromString(html, 'text/html');
      const nextResults = nextPage.querySelector('.listings');
      if (nextResults) {
        propertyResults.innerHTML = nextResults.innerHTML;
        window.history.replaceState({}, '', requestUrl);
      }
    } catch (error) {
      if (error.name !== 'AbortError') propertyFilter.submit();
    } finally {
      propertyResults.classList.remove('is-filtering');
      propertyFilter.removeAttribute('aria-busy');
    }
  };

  const queuePropertyUpdate = () => {
    window.clearTimeout(filterTimer);
    filterTimer = window.setTimeout(updateProperties, 280);
  };

  propertyFilter.addEventListener('input', queuePropertyUpdate);
  propertyFilter.addEventListener('change', updateProperties);
  propertyFilter.addEventListener('submit', (event) => {
    event.preventDefault();
    window.clearTimeout(filterTimer);
    updateProperties();
  });
}
