// login_logout.js

document.addEventListener('DOMContentLoaded', () => {
  // Handle login form submission
  const loginForm = document.querySelector('.login-form');
  if (loginForm) {
    loginForm.addEventListener('submit', async (e) => {
      e.preventDefault();

      const username = loginForm.username.value.trim();
      const password = loginForm.password.value;

      if (!username || !password) {
        alert('Please enter username and password');
        return;
      }

      try {
        const res = await fetch('/api/api_login.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ username, password }),
        });

        const data = await res.json();

        if (data.success) {
          // Redirect based on role
          if (data.data.is_admin) {
            window.location.href = '/index.php?page=admin_dashboard';
          } else {
            window.location.href = '/index.php?page=home';
          }
        } else {
          alert(data.error || 'Login failed');
        }
      } catch (err) {
        alert('Network error');
        console.error(err);
      }
    });
  }

  // Logout button logic
  const logoutBtn = document.querySelector('#logoutBtn');
  if (logoutBtn) {
    logoutBtn.addEventListener('click', async () => {
      try {
        const res = await fetch('/api/api_logout.php', { method: 'POST' });
        const data = await res.json();
        if (data.success) {
          window.location.href = '/index.php?page=login';
        } else {
          alert('Logout failed');
        }
      } catch (err) {
        alert('Network error during logout');
        console.error(err);
      }
    });
  }
});
