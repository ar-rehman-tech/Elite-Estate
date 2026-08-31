<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once dirname(__DIR__, 2) . '/includes/config.php';
require_once dirname(__DIR__, 2) . '/includes/db.php';
require_once dirname(__DIR__, 2) . '/includes/auth.php';

requireAdmin();
$currentUser = currentUser();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $pageTitle ?? 'Elite Estates Admin' ?></title>
  
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500&family=DM+Sans:wght@300;400;500;600&family=Cinzel:wght@400;500;600;700&display=swap" rel="stylesheet">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
  <script src="https://unpkg.com/lucide@latest"></script>
  
  <style>
    :root {
      /* Theme Variables based on Public Site */
      --midnight: #060E1C;
      --navy: #0A1628;
      --sapphire: #0F2A4A;
      --panel: #111D2E;
      --emerald: #1E6B55;
      --emerald-hover: #27896C;
      --platinum: #B8C4D4;
      --bright-platinum: #D8E4F0;
      --warm-white: #EEF2F7;
      --border: rgba(184, 196, 212, 0.12);
      
      --font-heading: 'Playfair Display', serif;
      --font-body: 'DM Sans', sans-serif;
      --font-logo: 'Cinzel', serif;
      
      --admin-danger: #e63946;
      --admin-glass-blur: blur(20px);
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
      overflow-x: hidden;
      -webkit-font-smoothing: antialiased;
    }

    /* Scrollbar */
    ::-webkit-scrollbar { width: 6px; height: 6px; }
    ::-webkit-scrollbar-track { background: var(--midnight); }
    ::-webkit-scrollbar-thumb { background: rgba(184, 196, 212, 0.2); border-radius: 4px; }
    ::-webkit-scrollbar-thumb:hover { background: rgba(184, 196, 212, 0.4); }

    /* Buttons */
    .btn-luxury {
      display: inline-flex;
      align-items: center;
      gap: 0.75rem;
      padding: 0.8rem 2rem;
      background: var(--emerald);
      color: var(--warm-white);
      font-family: var(--font-body);
      font-weight: 500;
      font-size: 0.9rem;
      border: 1px solid rgba(255,255,255,0.1);
      border-radius: 0;
      cursor: pointer;
      text-decoration: none;
      transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
      position: relative;
      overflow: hidden;
    }
    
    .btn-luxury::before {
      content: '';
      position: absolute;
      top: 0; left: -100%; width: 100%; height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
      transition: all 0.6s ease;
    }
    
    .btn-luxury:hover {
      background: var(--emerald-hover);
      transform: translateY(-2px);
      box-shadow: 0 10px 25px rgba(30, 107, 85, 0.4);
    }
    
    .btn-luxury:hover::before { left: 100%; }

    .btn-outline {
      display: inline-flex;
      align-items: center;
      gap: 0.75rem;
      padding: 0.8rem 2rem;
      background: transparent;
      color: var(--warm-white);
      font-family: var(--font-body);
      font-weight: 500;
      font-size: 0.9rem;
      border: 1px solid var(--platinum);
      cursor: pointer;
      text-decoration: none;
      transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .btn-outline:hover {
      background: var(--platinum);
      color: var(--midnight);
    }

    /* Sidebar */
    .admin-sidebar {
      width: 280px;
      height: 100vh;
      position: fixed;
      left: 0;
      top: 0;
      background: var(--navy);
      border-right: 1px solid var(--border);
      display: flex;
      flex-direction: column;
      z-index: 50;
      transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .sidebar-header {
      padding: 2.5rem 2rem;
      border-bottom: 1px solid var(--border);
    }

    .sidebar-logo {
      font-family: var(--font-logo);
      font-size: 1.6rem;
      font-weight: 500;
      color: var(--warm-white);
      text-decoration: none;
      display: flex;
      align-items: center;
      gap: 0.5rem;
      letter-spacing: 0.05em;
    }

    .sidebar-nav {
      padding: 2rem 1.5rem;
      display: flex;
      flex-direction: column;
      gap: 0.5rem;
      flex: 1;
      overflow-y: auto;
    }

    .nav-item {
      display: flex;
      align-items: center;
      gap: 1rem;
      padding: 1rem 1.25rem;
      color: var(--platinum);
      text-decoration: none;
      border: 1px solid transparent;
      font-family: var(--font-body);
      font-weight: 400;
      font-size: 0.95rem;
      transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .nav-item i {
      width: 18px;
      height: 18px;
      opacity: 0.7;
    }

    .nav-item:hover {
      background: var(--panel);
      color: var(--bright-platinum);
      border: 1px solid var(--border);
      transform: translateX(4px);
    }

    .nav-item.active {
      background: var(--emerald);
      color: var(--warm-white);
      border: 1px solid rgba(255,255,255,0.1);
      box-shadow: 0 4px 15px rgba(30, 107, 85, 0.3);
    }

    .nav-item.active i {
      opacity: 1;
    }

    /* Main Content */
    .admin-main {
      flex: 1;
      margin-left: 280px;
      width: calc(100% - 280px);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      position: relative;
    }

    /* Background glow effect */
    .admin-main::before {
      content: '';
      position: absolute;
      top: 0; left: 0; width: 100%; height: 500px;
      background: radial-gradient(circle at 50% -20%, var(--sapphire), transparent 70%);
      opacity: 0.3;
      pointer-events: none;
      z-index: 0;
    }

    /* Top Navbar */
    .admin-topbar {
      height: 80px;
      padding: 0 3rem;
      background: rgba(6, 14, 28, 0.7);
      backdrop-filter: blur(12px);
      -webkit-backdrop-filter: blur(12px);
      border-bottom: 1px solid var(--border);
      display: flex;
      align-items: center;
      justify-content: space-between;
      position: sticky;
      top: 0;
      z-index: 40;
    }

    .topbar-actions {
      display: flex;
      align-items: center;
      gap: 2rem;
    }

    .profile-menu {
      display: flex;
      align-items: center;
      gap: 1rem;
      cursor: pointer;
    }

    .profile-avatar {
      width: 40px;
      height: 40px;
      background: var(--emerald);
      display: flex;
      align-items: center;
      justify-content: center;
      color: var(--warm-white);
      font-family: var(--font-logo);
      font-size: 1.2rem;
      border: 1px solid rgba(255,255,255,0.2);
    }

    .profile-info {
      display: flex;
      flex-direction: column;
      text-align: right;
    }

    .profile-name {
      font-family: var(--font-heading);
      font-size: 1rem;
      color: var(--warm-white);
      font-style: italic;
    }

    .profile-role {
      font-size: 0.7rem;
      color: var(--platinum);
      text-transform: uppercase;
      letter-spacing: 0.1em;
    }

    /* Content Area */
    .admin-content {
      padding: 2.5rem 3rem;
      flex: 1;
      position: relative;
      z-index: 10;
      overflow-x: hidden;
    }

    .page-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 3rem;
      flex-wrap: wrap;
      gap: 1.5rem;
    }

    .page-header > div {
      min-width: 0;
    }

    .section-label {
      font-size: 0.7rem;
      letter-spacing: 0.2em;
      text-transform: uppercase;
      color: var(--platinum);
      margin-bottom: 0.5rem;
    }

    .page-title {
      font-family: var(--font-heading);
      font-size: 2.5rem;
      font-weight: 400;
      color: var(--warm-white);
      margin-bottom: 0.5rem;
    }

    .glass-card {
      background: var(--panel);
      border: 1px solid var(--border);
      padding: 2.5rem;
      position: relative;
      transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    }
    
    .glass-card::after {
      content: '';
      position: absolute;
      top: 0; left: 0; width: 100%; height: 100%;
      box-shadow: inset 0 0 0 1px rgba(255,255,255,0.02);
      pointer-events: none;
    }
    
    .glass-card:hover {
      border-color: rgba(184, 196, 212, 0.25);
      transform: translateY(-4px);
      box-shadow: 0 20px 40px rgba(0,0,0,0.4);
    }

    @media (max-width: 1024px) {
      .admin-sidebar {
        transform: translateX(-100%);
      }
      .admin-sidebar.open {
        transform: translateX(0);
      }
      .admin-main {
        margin-left: 0;
        width: 100%;
      }
      .mobile-toggle {
        display: block;
      }
      .admin-topbar {
        padding: 0 1.5rem;
      }
      .admin-content {
        padding: 1.5rem;
      }
    }
    
    .mobile-toggle {
      display: none;
      background: none;
      border: none;
      color: var(--platinum);
      cursor: pointer;
    }
  </style>
</head>
<body>

<!-- Sidebar -->
<aside class="admin-sidebar" id="sidebar">
  <div class="sidebar-header">
    <a href="dashboard.php" class="sidebar-logo">
      ELITE <span>ESTATE</span>
    </a>
  </div>
  
  <nav class="sidebar-nav">
    <?php
      $current_page = basename($_SERVER['PHP_SELF']);
    ?>
    <div class="section-label" style="padding-left: 1.25rem; margin-bottom: 1rem; margin-top: 1rem;">Menu</div>
    <a href="dashboard.php" class="nav-item <?= $current_page == 'dashboard.php' ? 'active' : '' ?>">
      <i data-lucide="layout-dashboard"></i> Dashboard
    </a>
    <a href="properties.php" class="nav-item <?= in_array($current_page, ['properties.php', 'add-property.php', 'edit-property.php']) ? 'active' : '' ?>">
      <i data-lucide="home"></i> Properties
    </a>
    <a href="inquiries.php" class="nav-item <?= $current_page == 'inquiries.php' ? 'active' : '' ?>">
      <i data-lucide="mail"></i> Inquiries
    </a>
    <a href="users.php" class="nav-item <?= $current_page == 'users.php' ? 'active' : '' ?>">
      <i data-lucide="users"></i> Users
    </a>
    
    <div class="section-label" style="padding-left: 1.25rem; margin-bottom: 1rem; margin-top: 2rem;">System</div>
    <a href="../index.php" class="nav-item" target="_blank">
      <i data-lucide="external-link"></i> Public Site
    </a>
    <a href="../logout.php" class="nav-item" style="color: var(--admin-danger);">
      <i data-lucide="log-out"></i> Sign Out
    </a>
  </nav>
</aside>

<!-- Main -->
<main class="admin-main">
  <!-- Topbar -->
  <header class="admin-topbar">
    <div style="display: flex; align-items: center; gap: 1.5rem;">
      <button class="mobile-toggle" id="mobileToggle">
        <i data-lucide="menu"></i>
      </button>
      <div style="font-family: var(--font-logo); color: var(--platinum); letter-spacing: 0.1em; font-size: 0.8rem;">
        ADVISORY PORTAL
      </div>
    </div>
    
    <div class="topbar-actions">
      <div class="profile-menu">
        <div class="profile-info">
          <span class="profile-name"><?= htmlspecialchars($currentUser['name'] ?? 'Administrator') ?></span>
          <span class="profile-role">Admin</span>
        </div>
        <div class="profile-avatar">
          <?= strtoupper(substr($currentUser['name'] ?? 'A', 0, 1)) ?>
        </div>
      </div>
    </div>
  </header>

  <div class="admin-content">
