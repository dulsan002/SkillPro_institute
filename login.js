// Toggle password visibility
document.getElementById('togglePassword')?.addEventListener('click', () => {
  const pw = document.getElementById('password');
  pw.type = pw.type === 'password' ? 'text' : 'password';
});

// Form validation
document.getElementById('loginForm')?.addEventListener('submit', (e) => {
  const email = document.getElementById('email').value.trim();
  const password = document.getElementById('password').value.trim();

  if (!email || !password) {
    e.preventDefault();
    alert('Please enter both email and password.');
  }
});