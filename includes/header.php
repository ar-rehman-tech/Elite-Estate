<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once dirname(__DIR__) . '/includes/config.php';
require_once dirname(__DIR__) . '/includes/auth.php';
$user = $_SESSION['user'] ?? null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $pageTitle ?? 'Elite Estates — Luxury Real Estate' ?></title>
  <meta name="description" content="<?= $pageDesc ?? 'Discover the world\'s finest luxury properties with Elite Estates.' ?>">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400;1,500&family=DM+Sans:wght@300;400;500;600&family=Cinzel:wght@400;500;600&display=swap" rel="stylesheet">

  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollToPlugin.min.js"></script>

  <link rel="stylesheet" href="<?= asset('css/luxury.css') ?>">
  <?= $extraHead ?? '' ?>
</head>
<body>

<div class="cursor-dot"></div>
<div class="cursor-ring"></div>

<div class="loading-overlay" id="loadingOverlay">
  <div class="loading-logo">Elite <span style="color:var(--warm-white)">Estates</span></div>
  <div class="loading-bar-container"><div class="loading-bar" id="loadingBar"></div></div>
  <div class="loading-num" id="loadingNum">0</div>
</div>

<nav class="navbar" id="navbar">
  <a href="<?= siteUrl() ?>" class="nav-logo" style="text-decoration:none">Elite <span>Estates</span></a>

  <div class="nav-links">
    <a href="<?= siteUrl() ?>">Home</a>
    <a href="<?= siteUrl('pages/search.php') ?>">Properties</a>
    <a href="<?= siteUrl('pages/search.php?type=villa') ?>">Villas</a>
    <a href="<?= siteUrl('pages/search.php?type=penthouse') ?>">Penthouses</a>
    <a href="<?= siteUrl('pages/about.php') ?>">About</a>
    <a href="<?= siteUrl('pages/contact.php') ?>">Contact</a>
    <?php if ($user): ?>
      <a href="<?= siteUrl('pages/favorites.php') ?>">Saved</a>
      <?php if (isAdmin()): ?>
        <a href="<?= siteUrl('admin/dashboard.php') ?>">Admin</a>
      <?php endif; ?>
      <a href="<?= siteUrl('logout.php') ?>" class="nav-cta">Logout</a>
    <?php else: ?>
      <a href="<?= siteUrl('login.php') ?>" class="nav-cta">Sign In</a>
    <?php endif; ?>
  </div>

  <div class="menu-toggle" onclick="toggleMobileNav(this)">
    <span></span><span></span><span></span>
  </div>
</nav>

<script>
  gsap.registerPlugin(ScrollTrigger, ScrollToPlugin);
  function toggleMobileNav(el) {
    el.classList.toggle('open');
    const links = document.querySelector('.nav-links');
    if (links.style.display === 'flex') {
      links.style.display = 'none';
    } else {
      links.style.cssText = 'display:flex;flex-direction:column;position:fixed;top:70px;left:0;right:0;background:rgba(6,14,28,0.98);padding:2rem;gap:1.5rem;z-index:999;backdrop-filter:blur(24px);border-bottom:1px solid rgba(184,196,212,0.15)';
    }
  }
</script>
