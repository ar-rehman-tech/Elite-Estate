<?php
require_once 'includes/config.php';
include 'includes/db.php';
include 'includes/auth.php';

if (isLoggedIn()) { header('Location: index.php'); exit; }

$error = $success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm'] ?? '';

    if (!$name || !$email || !$password) {
        $error = 'Please fill in all required fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif (strlen($password) < 8) {
        $error = 'Password must be at least 8 characters.';
    } elseif ($password !== $confirm) {
        $error = 'Passwords do not match.';
    } else {
        $db = DB::connect();
        $check = $db->prepare("SELECT id FROM users WHERE email = ?");
        $check->execute([$email]);
        if ($check->fetch()) {
            $error = 'An account with that email already exists.';
        } else {
            $db->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'user')")
               ->execute([$name, $email, password_hash($password, PASSWORD_DEFAULT)]);
            $success = 'Account created successfully! You may now sign in.';
        }
    }
}
$pageTitle = 'Create Account — Elite Estates';
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
  <div class="auth-visual" style="background-image:linear-gradient(135deg,rgba(6,14,28,.7),rgba(184,196,212,.15)),url('https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=1200&q=80');background-size:cover;background-position:center">
    <div>
      <div class="section-label" style="color:rgba(184,196,212,.7)">Join Our Circle</div>
      <p class="auth-visual-quote">"Membership grants access to a world most will never see."</p>
      <div class="gold-line" style="margin-top:1.5rem"></div>
      <p style="font-size:.8rem;color:rgba(255,255,255,.5)">Elite Estates Private Members</p>
    </div>
  </div>

  <div class="auth-form-area">
    <a href="index.php" class="auth-logo">Elite <span>Estates</span></a>

    <h2 class="auth-title">Create Account</h2>
    <p class="auth-subtitle">Join our exclusive network of global property investors</p>

    <?php if ($error): ?>
      <div class="error-msg"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
      <div class="success-msg"><?= htmlspecialchars($success) ?> <a href="login.php" style="color:var(--platinum)">Sign in here →</a></div>
    <?php endif; ?>

    <form method="POST">
      <div class="form-group">
        <label>Full Name</label>
        <input type="text" name="name" placeholder="Your full name" required value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
      </div>
      <div class="form-group">
        <label>Email Address</label>
        <input type="email" name="email" placeholder="your@email.com" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
      </div>
      <div class="form-group">
        <label>Password</label>
        <input type="password" name="password" placeholder="Minimum 8 characters" required>
      </div>
      <div class="form-group">
        <label>Confirm Password</label>
        <input type="password" name="confirm" placeholder="Repeat password" required>
      </div>

      <p style="font-size:.7rem;color:var(--text-muted);margin-bottom:1.5rem;line-height:1.6">
        By creating an account you agree to our <a href="#" style="color:var(--platinum)">Terms of Service</a> and <a href="#" style="color:var(--platinum)">Privacy Policy</a>.
      </p>

      <button type="submit" class="btn-luxury" style="width:100%;justify-content:center">
        <span>Create Account</span>
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
      </button>
    </form>

    <p class="auth-link">Already a member? <a href="login.php">Sign in</a></p>
  </div>
</div>

<script>
const dot=document.querySelector('.cursor-dot'),ring=document.querySelector('.cursor-ring');
if(dot&&ring){let mx=0,my=0,rx=0,ry=0;document.addEventListener('mousemove',e=>{mx=e.clientX;my=e.clientY;gsap.to(dot,{x:mx,y:my,duration:.1});});(function loop(){rx+=(mx-rx)*.12;ry+=(my-ry)*.12;gsap.set(ring,{x:rx,y:ry});requestAnimationFrame(loop);})();document.querySelectorAll('a,button,input').forEach(el=>{el.addEventListener('mouseenter',()=>ring.classList.add('hover'));el.addEventListener('mouseleave',()=>ring.classList.remove('hover'));});}
gsap.from('.auth-visual',{x:-60,opacity:0,duration:1,ease:'power3.out'});
gsap.from('.auth-form-area',{x:60,opacity:0,duration:1,ease:'power3.out',delay:.2});
gsap.from('.form-group',{y:20,opacity:0,duration:.6,stagger:.1,ease:'power2.out',delay:.5});
</script>
</body>
</html>
