<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

$current = basename($_SERVER['PHP_SELF']);
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Online Car Connect | Premier Automotive Marketplace</title>

  <!-- Google Fonts: Barlow Condensed & Montserrat (Matching Lamborghini LamboType) -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:ital,wght@0,400;0,500;0,600;0,700;0,800;0,900;1,700&family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="/carconnect/assets/css/style.css">
  <link rel="stylesheet" href="/carconnect/assets/css/responsive.css">
</head>

<body class="<?php echo ($current === 'index.php') ? 'page-home' : 'page-inner'; ?>">

<header class="nav" id="mainHeader">
  <div class="nav__inner">

    <!-- LOGO with Lamborghini-inspired Shield Emblem -->
    <a class="brand" href="/carconnect/index.php">
      <span class="brand-emblem">
        <svg viewBox="0 0 28 32" fill="none" xmlns="http://www.w3.org/2000/svg" class="emblem-svg">
          <path d="M14 0L27 6.5V17C27 25 14 32 14 32C14 32 1 25 1 17V6.5L14 0Z" stroke="url(#goldGrad)" stroke-width="2" fill="#0d0e11"/>
          <path d="M14 5L22 9.5V16.5C22 21.5 14 26.5 14 26.5C14 26.5 6 21.5 6 16.5V9.5L14 5Z" fill="url(#goldGrad)" opacity="0.35"/>
          <path d="M14 9L18 12V16C18 19 14 22 14 22C14 22 10 19 10 16V12L14 9Z" fill="url(#goldGrad)"/>
          <defs>
            <linearGradient id="goldGrad" x1="0" y1="0" x2="28" y2="32" gradientUnits="userSpaceOnUse">
              <stop stop-color="#f5b027"/>
              <stop offset="0.5" stop-color="#e5a93c"/>
              <stop offset="1" stop-color="#b67f1b"/>
            </linearGradient>
          </defs>
        </svg>
      </span>
      <span class="brand-text">
        CAR<span class="brand-highlight">CONNECT</span>
      </span>
    </a>

    <!-- MOBILE MENU TOGGLE BUTTON -->
    <button class="nav-toggle" id="navToggle" aria-label="Toggle Navigation">
      <span></span>
      <span></span>
      <span></span>
    </button>

    <!-- NAV LINKS -->
    <nav class="nav__links" id="navLinks">

      <?php if (!empty($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>

        <!-- ✅ ADMIN VIEW (ONLY DASHBOARD) -->
        <a class="nav-link" href="/carconnect/admin/admin_dashboard.php">Dashboard</a>

        <span class="user-badge badge-admin">
          <span class="badge-dot"></span> Admin
        </span>

        <a class="btn btn-logout" href="/carconnect/auth/logout.php">Logout</a>

      <?php else: ?>

        <!-- ✅ NORMAL USERS -->
        <a class="nav-link <?php echo ($current=='index.php')?'active':''; ?>" 
           href="/carconnect/index.php">Home</a>

        <a class="nav-link <?php echo ($current=='about.php')?'active':''; ?>" 
           href="/carconnect/about.php">About</a>

        <a class="nav-link <?php echo ($current=='cars.php')?'active':''; ?>" 
           href="/carconnect/cars.php">Browse Cars</a>

        <?php if (!empty($_SESSION['role'])): ?>

          <!-- DASHBOARD -->
          <?php if ($_SESSION['role'] === 'buyer'): ?>
            <a class="nav-link" href="/carconnect/buyer/home.php">Dashboard</a>
          <?php elseif ($_SESSION['role'] === 'seller'): ?>
            <a class="nav-link" href="/carconnect/seller/seller_dashboard.php">Seller Panel</a>
          <?php endif; ?>

          <!-- USER BADGE -->
          <span class="user-badge">
            <span class="badge-dot"></span> <?php echo ucfirst($_SESSION['role']); ?>
          </span>

          <!-- LOGOUT -->
          <a class="btn btn-logout" href="/carconnect/auth/logout.php">Logout</a>

        <?php else: ?>

          <!-- LOGIN / REGISTER -->
          <a class="btn btn-outline" href="/carconnect/auth/login.php">
            Login
          </a>

          <a class="btn primary" href="/carconnect/auth/register.php">
            Register
          </a>

        <?php endif; ?>

      <?php endif; ?>

    </nav>

  </div>
</header>

<main class="<?php echo ($current === 'index.php') ? 'main-home' : 'container'; ?>">