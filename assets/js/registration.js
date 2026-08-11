document.querySelectorAll('.password-toggle').forEach((toggle) => {
  toggle.addEventListener('click', () => {
    const input = document.querySelector(`input[name="${toggle.dataset.target}"]`);
    if (!input) return;
    const visible = input.type === 'text';
    input.type = visible ? 'password' : 'text';
    toggle.textContent = visible ? 'Anzeigen' : 'Verbergen';
    toggle.setAttribute('aria-label', visible ? 'Passwort anzeigen' : 'Passwort verbergen');
  });
});
