const passwordToggle = document.querySelector('.password-toggle');
const passwordInput = document.querySelector('input[name="password"]');
passwordToggle?.addEventListener('click', () => {
  const visible = passwordInput.type === 'text';
  passwordInput.type = visible ? 'password' : 'text';
  passwordToggle.textContent = visible ? 'Anzeigen' : 'Verbergen';
  passwordToggle.setAttribute('aria-label', visible ? 'Passwort anzeigen' : 'Passwort verbergen');
});
