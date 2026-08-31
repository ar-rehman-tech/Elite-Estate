<?php
require_once 'includes/config.php';
include 'includes/db.php';
include 'includes/auth.php';

if (isLoggedIn()) { header('Location: index.php'); exit; }

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email && $password) {
        $db = DB::connect();
        $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user;
            if (($user['role'] ?? 'user') === 'admin') {
                header('Location: ' . siteUrl('admin/dashboard.php'));
            } else {
                header('Location: ' . siteUrl('index.php'));
            }
            exit;
        } else {
            $error = 'Invalid email or password. Please try again.';
        }
    } else {
        $error = 'Please fill in all fields.';
    }
}

$pageTitle = 'Sign In — Elite Estates';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $pageTitle ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500&family=DM+Sans:wght@300;400;500;600&family=Cinzel:wght@400;500;600&display=swap" rel="stylesheet">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
  <link rel="stylesheet" href="<?= asset('css/luxury.css') ?>">
</head>
<body>
<div class="cursor-dot"></div>
<div class="cursor-ring"></div>

<div class="auth-page">
  <!-- Visual Side -->
  <div class="auth-visual">
    <div>
      <div class="section-label" style="color:rgba(184,196,212,.7)">Est. 2005</div>
      <p class="auth-visual-quote">"The finest homes deserve the most discerning custodians."</p>
      <div class="gold-line" style="margin-top:1.5rem"></div>
      <p style="font-size:.8rem;color:rgba(255,255,255,.5)">Elite Estates Advisory</p>
    </div>
  </div>

  <!-- Form Side -->
  <div class="auth-form-area">
    <a href="index.php" class="auth-logo">Elite <span>Estates</span></a>

    <h2 class="auth-title">Welcome Back</h2>
    <p class="auth-subtitle">Sign in to access your private portfolio</p>

    <?php if ($error): ?>
      <div class="error-msg"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST">
      <div class="form-group">
        <label>Email Address</label>
        <input type="email" name="email" placeholder="your@email.com" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
      </div>
      <div class="form-group">
        <label>Password</label>
        <div class="pw-wrapper">
          <input type="password" name="password" id="loginPassword" placeholder="••••••••" required aria-label="Password">
          <button type="button" class="pw-toggle" id="pwToggle" aria-label="Toggle password visibility">
            <!-- Eye icon (visible when password hidden) -->
            <svg id="eyeIcon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
              <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
              <circle cx="12" cy="12" r="3"/>
            </svg>
            <!-- Eye-slash icon (visible when password shown) -->
            <svg id="eyeSlashIcon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" style="display:none">
              <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/>
              <path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/>
              <line x1="1" y1="1" x2="23" y2="23"/>
            </svg>
          </button>
        </div>
      </div>

      <div style="display:flex;justify-content:flex-end;margin-bottom:2rem">
        <a href="#" style="font-size:.75rem;color:var(--platinum)">Forgot password?</a>
      </div>

      <button type="submit" class="btn-luxury" style="width:100%;justify-content:center">
        <span>Sign In</span>
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
      </button>
    </form>

    <p class="auth-link">Don't have an account? <a href="register.php">Create one</a></p>
    <p style="margin-top:.75rem;text-align:center"><a href="index.php" style="font-size:.75rem;color:var(--text-muted)">← Back to homepage</a></p>
  </div>
</div>

<script>
const dot = document.querySelector('.cursor-dot');
const ring = document.querySelector('.cursor-ring');
if (dot && ring) {
  let mx=0,my=0,rx=0,ry=0;
  document.addEventListener('mousemove',e=>{mx=e.clientX;my=e.clientY;gsap.to(dot,{x:mx,y:my,duration:.1});});
  (function loop(){rx+=(mx-rx)*.12;ry+=(my-ry)*.12;gsap.set(ring,{x:rx,y:ry});requestAnimationFrame(loop);})();
  document.querySelectorAll('a,button,input').forEach(el=>{el.addEventListener('mouseenter',()=>ring.classList.add('hover'));el.addEventListener('mouseleave',()=>ring.classList.remove('hover'));});
}
// Animate in
gsap.from('.auth-visual', {x: -60, opacity: 0, duration: 1, ease: 'power3.out'});
gsap.from('.auth-form-area', {x: 60, opacity: 0, duration: 1, ease: 'power3.out', delay: .2});
gsap.from('.form-group', {y: 20, opacity: 0, duration: .6, stagger: .12, ease: 'power2.out', delay: .6});
// Password visibility toggle
const pwToggle = document.getElementById('pwToggle');
const pwInput  = document.getElementById('loginPassword');
const eyeOn    = document.getElementById('eyeIcon');
const eyeOff   = document.getElementById('eyeSlashIcon');
if (pwToggle && pwInput) {
  pwToggle.addEventListener('click', () => {
    const isHidden = pwInput.type === 'password';
    pwInput.type   = isHidden ? 'text' : 'password';
    eyeOn.style.display  = isHidden ? 'none' : '';
    eyeOff.style.display = isHidden ? ''     : 'none';
    pwToggle.setAttribute('aria-label', isHidden ? 'Hide password' : 'Show password');
  });
}

</script>
</body>
</html>
