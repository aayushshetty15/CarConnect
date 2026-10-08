</main>

<?php
$role = $_SESSION['role'] ?? '';
?>

<?php if($role === 'admin'): ?>

<!-- ✅ ADMIN SIMPLE FOOTER -->
<footer class="footer-admin">
  <div class="container text-center">
    <p class="muted">© <?php echo date("Y"); ?> CARCONNECT AUTOMOTIVE // ADMINISTRATIVE PORTAL</p>
  </div>
</footer>

<?php else: ?>

<!-- ✅ NORMAL FULL FOOTER -->
<footer class="footer">

  <div class="footer-accent-line"></div>

  <div class="container footer-grid">

    <!-- BRAND -->
    <div class="footer-brand-col">
      <div class="footer-brand-header">
        <span class="brand-emblem-small">
          <svg viewBox="0 0 28 32" fill="none" xmlns="http://www.w3.org/2000/svg" class="emblem-svg-small">
            <path d="M14 0L27 6.5V17C27 25 14 32 14 32C14 32 1 25 1 17V6.5L14 0Z" stroke="#e5a93c" stroke-width="2" fill="#0d0e11"/>
            <path d="M14 9L18 12V16C18 19 14 22 14 22C14 22 10 19 10 16V12L14 9Z" fill="#e5a93c"/>
          </svg>
        </span>
        <h3 class="footer-title">CAR<span class="text-gold">CONNECT</span></h3>
      </div>
      <p class="footer-desc">
        India's ultimate luxury & certified used car marketplace. Engineered for precision, certified authenticity, and peerless peer-to-peer automotive transactions.
      </p>
      <div class="footer-status">
        <span class="status-indicator"></span> NETWORK STATUS: OPERATIONAL
      </div>
    </div>

    <!-- QUICK LINKS -->
    <div>
      <h4 class="footer-sub">EXPLORE</h4>
      <ul class="footer-links">
        <li><a href="/carconnect/index.php">HOME</a></li>
        <li><a href="/carconnect/about.php">ABOUT THE PLATFORM</a></li>
        <li><a href="/carconnect/cars.php">BROWSE INVENTORY</a></li>
      </ul>
    </div>

    <!-- USER LINKS -->
    <div>
      <h4 class="footer-sub">MEMBERSHIP</h4>
      <ul class="footer-links">
        <li><a href="/carconnect/auth/login.php">CLIENT LOGIN</a></li>
        <li><a href="/carconnect/auth/register.php">JOIN THE PLATFORM</a></li>
      </ul>
    </div>

    <!-- INFO -->
    <div>
      <h4 class="footer-sub">CONCIERGE</h4>
      <p class="footer-contact"><span>EMAIL:</span> concierge@carconnect.in</p>
      <p class="footer-contact"><span>HEADQUARTERS:</span> Mumbai & New Delhi, India</p>
      <p class="footer-contact"><span>SUPPORT:</span> 24/7 Verified Assistance</p>
    </div>

  </div>

  <!-- BOTTOM BAR -->
  <div class="footer-bottom">
    <div class="container footer-bottom-inner">
      <span>© <?php echo date("Y"); ?> AUTOMOBILI CARCONNECT INDIA. ALL RIGHTS RESERVED.</span>
      <span class="footer-tagline">INNOVATION // PERFORMANCE // TRUST</span>
    </div>
  </div>

</footer>

<?php endif; ?>

<script src="/carconnect/assets/js/main.js"></script>
<script src="/carconnect/assets/js/validation.js"></script>

</body>
</html>