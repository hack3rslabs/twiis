<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Login | Twiis Innovations</title>
  <link rel="stylesheet" href="/css/main.css">
  <style>
    body { background: #f0f4f8; display: flex; align-items: center; justify-content: center; height: 100vh; }
    .login-box { background: white; padding: 3rem; border-radius: 16px; box-shadow: 0 10px 40px rgba(0,0,0,0.1); width: 100%; max-width: 400px; text-align: center; }
    .login-box h2 { margin-bottom: 2rem; color: #1f3a5f; }
    .form-group { margin-bottom: 1.5rem; text-align: left; }
    .form-group label { display: block; margin-bottom: 0.5rem; font-weight: 600; color: #1f3a5f; }
    .form-group input { width: 100%; padding: 1rem; border: 2px solid #e5e7eb; border-radius: 8px; }
    .btn-login { width: 100%; padding: 1rem; background: #1f3a5f; color: white; border: none; border-radius: 8px; font-weight: 700; cursor: pointer; }
  </style>
</head>
<body>
  <div class="login-box">
    <h2>Admin Login</h2>
    <div class="form-group">
      <label>Username</label>
      <input type="text" id="cms_user">
    </div>
    <div class="form-group">
      <label>Password</label>
      <input type="password" id="cms_pass">
    </div>
    <button class="btn-login" onclick="login()">Access Dashboard</button>
  </div>

  <script>
    async function login() {
      const u = document.getElementById('cms_user').value;
      const p = document.getElementById('cms_pass').value;
      
      const res = await fetch('../api/auth.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({username: u, password: p})
      });
      const data = await res.json();
      
      if (data.status === 'success') {
        window.location.href = 'index';
      } else {
        alert(data.message || 'Invalid credentials.');
      }
    }
  </script>
</body>
</html>
