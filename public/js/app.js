// Lightweight client-side validation enhancement.
document.querySelectorAll('form[novalidate]').forEach((form) => {
  form.addEventListener('submit', (event) => {
    const emailInput = form.querySelector('input[type="email"]');
    const passwordInput = form.querySelector('input[type="password"]');

    if (emailInput && !/^\S+@\S+\.\S+$/.test(emailInput.value)) {
      alert('Please enter a valid email address.');
      event.preventDefault();
      return;
    }

    if (passwordInput && passwordInput.value.length < 8) {
      alert('Password must be at least 8 characters long.');
      event.preventDefault();
    }
  });
});
