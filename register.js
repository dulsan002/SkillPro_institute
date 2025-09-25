document.getElementById('registerForm')?.addEventListener('submit', (e) => {
  const firstName = document.getElementById('firstName').value.trim();
  const lastName = document.getElementById('lastName').value.trim();
  const email = document.getElementById('email').value.trim();
  const accountType = document.getElementById('accountType').value;

  if (!firstName || !lastName || !email || !accountType) {
    e.preventDefault();
    alert('Please fill out all fields.');
  }
});