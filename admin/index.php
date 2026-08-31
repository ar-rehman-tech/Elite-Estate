<?php
require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/auth.php';

if (isLoggedIn() && isAdmin()) {
    header('Location: dashboard.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username && $password) {
        try {
            $db = DB::connect();
            $stmt = $db->prepare("SELECT * FROM users WHERE (name = ? OR email = ?) AND role = 'admin'");
            $stmt->execute([$username, $username]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user'] = $user;
                header('Location: dashboard.php');
                exit;
            } else {
                $error = 'Invalid admin credentials.';
            }
        } catch (Exception $e) {
            $error = 'System error occurred.';
        }
    } else {
        $error = 'Please fill in all fields.';
    }
}

$pageTitle = 'Admin Portal — Elite Estates';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $pageTitle ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500&family=DM+Sans:wght@300;400;500;600&family=Cinzel:wght@400;500;600;700&display=swap" rel="stylesheet">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
  <script src="https://unpkg.com/lucide@latest"></script>
  <style>
    :root {
      --midnight: #060E1C;
      --navy: #0A1628;
      --sapphire: #0F2A4A;
      --panel: #111D2E;
      --emerald: #1E6B55;
      --emerald-hover: #27896C;
      --platinum: #B8C4D4;
      --warm-white: #EEF2F7;
      --border: rgba(184, 196, 212, 0.12);
      
      --font-heading: 'Playfair Display', serif;
      --font-body: 'DM Sans', sans-serif;
      --font-logo: 'Cinzel', serif;
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: var(--font-body);
      background-color: var(--midnight);
      color: var(--platinum);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      background-image: radial-gradient(circle at 50% 0%, var(--sapphire), transparent 60%);
      overflow: hidden;
      -webkit-font-smoothing: antialiased;
    }

    .admin-login-container {
      position: relative;
      z-index: 10;
      width: 100%;
      max-width: 440px;
      padding: 3.5rem 3rem;
      background: var(--panel);
      border: 1px solid var(--border);
      box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
      transform: translateY(30px);
      opacity: 0;
    }
    
    .admin-login-container::after {
      content: '';
      position: absolute;
      top: 0; left: 0; width: 100%; height: 100%;
      box-shadow: inset 0 0 0 1px rgba(255,255,255,0.02);
      pointer-events: none;
    }

    .logo-container {
      text-align: center;
      margin-bottom: 2.5rem;
    }

    .logo-container h1 {
      font-family: var(--font-logo);
      font-size: 2.2rem;
      font-weight: 500;
      letter-spacing: 0.05em;
      color: var(--warm-white);
    }

    .logo-container p {
      font-family: var(--font-body);
      color: var(--platinum);
      font-size: 0.8rem;
      text-transform: uppercase;
      letter-spacing: 0.2em;
      margin-top: 0.5rem;
    }

    .form-group {
      margin-bottom: 1.5rem;
      position: relative;
    }

    .form-group label {
      display: block;
      font-size: 0.8rem;
      text-transform: uppercase;
      letter-spacing: 0.1em;
      margin-bottom: 0.75rem;
      color: var(--platinum);
    }

    .form-control {
      width: 100%;
      padding: 1rem;
      background: rgba(6, 14, 28, 0.4);
      border: 1px solid var(--border);
      color: var(--warm-white);
      font-family: var(--font-body);
      font-size: 1rem;
      transition: all 0.4s ease;
      outline: none;
    }

    .form-control:focus {
      border-color: var(--emerald);
      background: rgba(6, 14, 28, 0.8);
      box-shadow: 0 0 15px rgba(30, 107, 85, 0.2);
    }

    .pw-wrapper {
      position: relative;
    }

    .pw-toggle {
      position: absolute;
      right: 1rem;
      top: 50%;
      transform: translateY(-50%);
      background: none;
      border: none;
      color: var(--platinum);
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: color 0.3s;
    }

    .pw-toggle:hover {
      color: var(--warm-white);
    }

    .btn-login {
      width: 100%;
      padding: 1.2rem;
      background: var(--emerald);
      color: var(--warm-white);
      border: 1px solid rgba(255,255,255,0.1);
      font-family: var(--font-body);
      font-weight: 500;
      font-size: 0.95rem;
      letter-spacing: 0.05em;
      cursor: pointer;
      transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.75rem;
      margin-top: 2rem;
      position: relative;
      overflow: hidden;
      opacity: 1 !important;
      visibility: visible !important;
    }
    
    .btn-login::before {
      content: '';
      position: absolute;
      top: 0; left: -100%; width: 100%; height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
      transition: all 0.6s ease;
    }

    .btn-login:hover {
      background: var(--emerald-hover);
      transform: translateY(-2px);
      box-shadow: 0 10px 25px rgba(30, 107, 85, 0.4);
    }
    
    .btn-login:hover::before { left: 100%; }

    .alert {
      padding: 1rem;
      border: 1px solid rgba(230, 57, 70, 0.3);
      background: rgba(230, 57, 70, 0.1);
      color: #e63946;
      margin-bottom: 1.5rem;
      font-size: 0.85rem;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .back-link {
      display: block;
      text-align: center;
      margin-top: 2rem;
      color: var(--platinum);
      text-decoration: none;
      font-size: 0.8rem;
      text-transform: uppercase;
      letter-spacing: 0.1em;
      transition: color 0.3s;
    }

    .back-link:hover {
      color: var(--warm-white);
    }
  </style>
</head>
<body>

<div class="admin-login-container">
  <div class="logo-container">
    <h1>ELITE <span>ESTATE</span></h1>
    <p>Advisory Portal</p>
  </div>

  <?php if ($error): ?>
    <div class="alert">
      <i data-lucide="alert-circle" style="width: 18px; height: 18px;"></i>
      <?= htmlspecialchars($error) ?>
    </div>
  <?php endif; ?>

  <form method="POST">
    <div class="form-group">
      <label for="username">Administrator ID</label>
      <input type="text" id="username" name="username" class="form-control" placeholder="Enter ID" required value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
    </div>

    <div class="form-group">
      <label for="password">Security Passkey</label>
      <div class="pw-wrapper">
        <input type="password" id="password" name="password" class="form-control" placeholder="••••••••" required>
        <button type="button" class="pw-toggle" id="pwToggle">
          <i data-lucide="eye" id="eyeIcon"></i>
          <i data-lucide="eye-off" id="eyeSlashIcon" style="display:none"></i>
        </button>
      </div>
    </div>

    <button type="submit" class="btn-login">
      Authenticate
      <i data-lucide="arrow-right" style="width: 18px; height: 18px;"></i>
    </button>
  </form>

  <a href="../index.php" class="back-link">Return to Public Site</a>
</div>

<script>
  lucide.createIcons();

  gsap.to('.admin-login-container', {
    y: 0,
    opacity: 1,
    duration: 1.2,
    ease: 'power3.out',
    delay: 0.2
  });

  gsap.from('.form-group, .logo-container, .alert', {
    y: 20,
    opacity: 0,
    duration: 0.8,
    stagger: 0.1,
    ease: 'power2.out',
    delay: 0.5
  });

  const pwToggle = document.getElementById('pwToggle');
  const pwInput = document.getElementById('password');
  const eyeOn = document.getElementById('eyeIcon');
  const eyeOff = document.getElementById('eyeSlashIcon');

  pwToggle.addEventListener('click', () => {
    if (pwInput.type === 'password') {
      pwInput.type = 'text';
      eyeOn.style.display = 'none';
      eyeOff.style.display = 'block';
    } else {
      pwInput.type = 'password';
      eyeOn.style.display = 'block';
      eyeOff.style.display = 'none';
    }
  });
</script>
</body>
</html>
