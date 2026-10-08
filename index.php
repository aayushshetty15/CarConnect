<?php 
require_once __DIR__ . "/includes/header.php"; 
require_once __DIR__ . "/includes/db_connect.php"; 
require_once __DIR__ . "/includes/functions.php";

// Check if user is logged in and fetch the role
$isLoggedIn = !empty($_SESSION['user_id']);
$role = $_SESSION['role'] ?? ''; // Check the role of the logged-in user

// Role-based browse link
$browseLink = "/carconnect/auth/login.php";
if ($isLoggedIn) {
  if ($role == 'admin') {
    $browseLink = "/carconnect/cars.php?role=admin";
  } elseif ($role == 'seller') {
    $browseLink = "/carconnect/cars.php?role=seller";
  } else {
    $browseLink = "/carconnect/cars.php?role=buyer";
  }
}

$sellLink = $isLoggedIn 
  ? ($role === 'seller' ? "/carconnect/seller/add_car.php" : "/carconnect/cars.php") 
  : "/carconnect/auth/register.php";
?>

<!-- ============================================== -->
<!-- 🏎️ LAMBORGHINI-STYLE HERO SECTION WITH VIDEO -->
<!-- ============================================== -->
<section class="hero-video-section">
  
  <!-- Background Video Container -->
  <div class="hero-video-container">
    <video autoplay muted loop playsinline id="heroVideo" class="hero-video-bg">
      <source src="/carconnect/assets/videos/hero_video.mp4" type="video/mp4">
      <source src="/carconnect/assets/videos/videoplayback%20(2).mp4" type="video/mp4">
      Your browser does not support HTML5 video.
    </video>
    <!-- Multi-stage Dark Vignette Gradients -->
    <div class="hero-gradient-overlay"></div>
    <div class="hero-mesh-overlay"></div>
  </div>

  <!-- Hero Content Overlay -->
  <div class="hero-content">
    <div class="container hero-content-inner">

      <div class="hero-badge">
        <span class="badge-dot-pulse"></span>
        <span>ONLINE CAR CONNECT // EXCLUSIVE MOTORING</span>
      </div>

      <h1 class="hero-headline">
        BEYOND THE <span class="text-gold">ORDINARY</span>
      </h1>

      <p class="hero-subtext">
        Discover India’s premier verified used and performance automobile marketplace. 
        Direct peer-to-peer ownership, uncompromising inspection standards, and zero middleman markups.
      </p>

      <div class="hero-actions">
        <a class="btn btn-gold btn-chamfered" href="<?php echo $browseLink; ?>">
          <span>EXPLORE INVENTORY</span>
          <svg class="btn-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="5" y1="12" x2="19" y2="12"></line>
            <polyline points="12 5 19 12 12 19"></polyline>
          </svg>
        </a>

        <a class="btn btn-outline-white btn-chamfered" href="<?php echo $sellLink; ?>">
          <span>SELL YOUR CAR</span>
        </a>
      </div>

    </div>
  </div>

  <!-- Video Interaction Controls (Sound & Playback) -->
  <div class="hero-controls">
    <button type="button" class="ctrl-btn" id="audioToggle" aria-label="Toggle Sound" title="Sound Mute / Unmute">
      <svg id="soundMutedIcon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"></polygon>
        <line x1="23" y1="9" x2="17" y2="15"></line>
        <line x1="17" y1="9" x2="23" y2="15"></line>
      </svg>
      <svg id="soundActiveIcon" class="d-none" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"></polygon>
        <path d="M19.07 4.93a10 10 0 0 1 0 14.14M15.54 8.46a5 5 0 0 1 0 7.07"></path>
      </svg>
    </button>

    <button type="button" class="ctrl-btn" id="playPauseToggle" aria-label="Pause Video" title="Play / Pause">
      <svg id="pauseIcon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <rect x="6" y="4" width="4" height="16"></rect>
        <rect x="14" y="4" width="4" height="16"></rect>
      </svg>
      <svg id="playIcon" class="d-none" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <polygon points="5 3 19 12 5 21 5 3"></polygon>
      </svg>
    </button>
  </div>

  <!-- Scroll Down Indicator -->
  <a href="#marques" class="hero-scroll-indicator" aria-label="Scroll to content">
    <span class="scroll-text">EXPLORE</span>
    <div class="scroll-chevron"></div>
  </a>

</section>

<!-- ============================================== -->
<!-- 📊 TELEMETRY / PERFORMANCE METRICS STRIP -->
<!-- ============================================== -->
<section class="telemetry-bar">
  <div class="container telemetry-grid">
    <div class="telemetry-item">
      <div class="telemetry-value"><span class="counter">100</span>%</div>
      <div class="telemetry-label">VERIFIED INSPECTION</div>
    </div>
    <div class="telemetry-item">
      <div class="telemetry-value"><span class="text-gold">₹0</span></div>
      <div class="telemetry-label">BROKERAGE COMMISSION</div>
    </div>
    <div class="telemetry-item">
      <div class="telemetry-value"><span class="counter">2,500</span>+</div>
      <div class="telemetry-label">CERTIFIED VEHICLES</div>
    </div>
    <div class="telemetry-item">
      <div class="telemetry-value">24<span class="text-gold">/7</span></div>
      <div class="telemetry-label">CONCIERGE ASSISTANCE</div>
    </div>
  </div>
</section>

<!-- ============================================== -->
<!-- 🏷️ BRANDS / MARQUES SECTION -->
<!-- ============================================== -->
<section class="section-block" id="marques">
  <div class="container">
    
    <div class="section-header-centered">
      <div class="section-eyebrow">DISTINGUISHED MARQUES</div>
      <h2 class="section-title">EXPLORE BY BRAND</h2>
      <div class="gold-accent-line"></div>
    </div>

    <div class="brand-grid">
      <?php
      $brands = [
        ["name" => "Maruti", "origin" => "Suzuki Precision"],
        ["name" => "Tata", "origin" => "Safest In Class"],
        ["name" => "Mahindra", "origin" => "All-Terrain SUVs"],
        ["name" => "Hyundai", "origin" => "Dynamic Mobility"],
        ["name" => "Honda", "origin" => "VTEC Engineering"],
        ["name" => "Toyota", "origin" => "Legendary Reliability"]
      ];

      foreach($brands as $b):
          $name = $b['name'];
          $desc = $b['origin'];
          $link = $isLoggedIn 
              ? "/carconnect/cars.php?brand=" . urlencode($name) . "&role=" . urlencode($role)
              : "/carconnect/auth/login.php";
          
          echo "
          <a href='$link' class='brand-card'>
            <div class='brand-card-inner'>
              <span class='brand-badge'>MARQUE</span>
              <div class='brand-name'>$name</div>
              <div class='brand-desc'>$desc</div>
              <div class='brand-arrow'>
                <svg width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2'>
                  <path d='M5 12h14M12 5l7 7-7 7'/>
                </svg>
              </div>
            </div>
          </a>";
      endforeach;
      ?>
    </div>

  </div>
</section>

<!-- ============================================== -->
<!-- 🚗 FEATURED VEHICLES / LATEST INVENTORY -->
<!-- ============================================== -->
<section class="section-block bg-darker" id="inventory">
  <div class="container">

    <div class="section-header-centered">
      <div class="section-eyebrow">EXCLUSIVE SHOWCASE</div>
      <h2 class="section-title">LATEST ACQUISITIONS</h2>
      <div class="gold-accent-line"></div>
    </div>

    <?php if($isLoggedIn): ?>
      <div class="car-grid">
        <?php
        $stmt = mysqli_prepare($conn, "
          SELECT id, make, model, year, price, image_path, fuel_type, transmission, location
          FROM car_listings
          WHERE status = 'approved'
          ORDER BY created_at DESC
          LIMIT 8
        ");

        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);

        if(mysqli_num_rows($res) > 0):
          while($c = mysqli_fetch_assoc($res)): 
            $img = !empty($c['image_path']) ? $c['image_path'] : "https://images.unsplash.com/photo-1503376780353-7e6692767b70?auto=format&fit=crop&w=900&q=80";
            $detailsUrl = "/carconnect/" . ($role ?: 'buyer') . "/car_details.php?id=" . $c['id'];
        ?>

        <div class="luxury-car-card">
          <div class="luxury-car-media">
            <img src="<?php echo e($img); ?>" alt="<?php echo e($c['make'] . " " . $c['model']); ?>" loading="lazy">
            <span class="status-chip chip-approved">VERIFIED</span>
            <div class="media-overlay"></div>
          </div>

          <div class="luxury-car-body">
            <div class="luxury-car-specs">
              <span class="spec-tag"><?php echo e($c['year']); ?></span>
              <?php if(!empty($c['fuel_type'])): ?>
                <span class="spec-tag"><?php echo e(strtoupper($c['fuel_type'])); ?></span>
              <?php endif; ?>
              <?php if(!empty($c['location'])): ?>
                <span class="spec-tag"><?php echo e(strtoupper($c['location'])); ?></span>
              <?php endif; ?>
            </div>

            <h3 class="luxury-car-title">
              <?php echo e($c['make'] . " " . $c['model']); ?>
            </h3>

            <div class="luxury-car-price-row">
              <div class="price-label">OFFERED AT</div>
              <div class="luxury-car-price">
                ₹<?php echo number_format($c['price']); ?>
              </div>
            </div>

            <a class="btn btn-card-action" href="<?php echo $detailsUrl; ?>">
              <span>INSPECT VEHICLE</span>
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <path d="M5 12h14M12 5l7 7-7 7"/>
              </svg>
            </a>
          </div>
        </div>

        <?php 
          endwhile;
        else:
        ?>
          <div class="empty-inventory-box">
            <p>No listings currently approved. Check back shortly for new acquisitions.</p>
          </div>
        <?php endif; ?>
      </div>

      <div class="text-center mt-40">
        <a class="btn btn-outline-gold" href="<?php echo $browseLink; ?>">
          VIEW ALL VEHICLES IN MARKETPLACE
        </a>
      </div>

    <?php else: ?>
      <!-- Locked Box for Unauthenticated Visitors -->
      <div class="luxury-locked-box">
        <div class="locked-icon-wrap">
          <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#e5a93c" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
          </svg>
        </div>
        <h3 class="locked-title">AUTHENTICATION REQUIRED</h3>
        <p class="locked-desc">
          To inspect verified vehicle profiles, technical specifications, and direct seller contact coordinates, please log in to your account.
        </p>
        <div class="locked-actions">
          <a class="btn btn-gold btn-chamfered" href="/carconnect/auth/login.php">
            <span>CLIENT LOGIN</span>
          </a>
          <a class="btn btn-outline-white btn-chamfered" href="/carconnect/auth/register.php">
            <span>REGISTER ACCOUNT</span>
          </a>
        </div>
      </div>
    <?php endif; ?>

  </div>
</section>

<!-- ============================================== -->
<!-- 🛡️ PILLARS OF PERFORMANCE (WHY CHOOSE US) -->
<!-- ============================================== -->
<section class="section-block">
  <div class="container">

    <div class="section-header-centered">
      <div class="section-eyebrow">ENGINEERING TRUST</div>
      <h2 class="section-title">THE CARCONNECT ADVANTAGE</h2>
      <div class="gold-accent-line"></div>
    </div>

    <div class="pillars-grid">
      
      <!-- Card 01 -->
      <div class="pillar-card">
        <div class="pillar-header">
          <span class="pillar-num">01</span>
          <div class="pillar-icon">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
            </svg>
          </div>
        </div>
        <h3 class="pillar-title">CERTIFIED INTEGRITY</h3>
        <p class="pillar-desc">
          Every vehicle undergoes rigorous verification of title, history, and physical condition. No fraudulent records or manipulated odometers.
        </p>
      </div>

      <!-- Card 02 -->
      <div class="pillar-card">
        <div class="pillar-header">
          <span class="pillar-num">02</span>
          <div class="pillar-icon">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <line x1="12" y1="1" x2="12" y2="23"></line>
              <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
            </svg>
          </div>
        </div>
        <h3 class="pillar-title">PURE TRANSPARENCY</h3>
        <p class="pillar-desc">
          Zero middleman markups, hidden dealership margins, or unsolicited broker fees. Direct transparent price discovery between buyer and seller.
        </p>
      </div>

      <!-- Card 03 -->
      <div class="pillar-card">
        <div class="pillar-header">
          <span class="pillar-num">03</span>
          <div class="pillar-icon">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon>
            </svg>
          </div>
        </div>
        <h3 class="pillar-title">ACCELERATED TRANSFERS</h3>
        <p class="pillar-desc">
          Seamless end-to-end messaging, instant buyer inquiries, verified documentation support, and swift turnaround across major Indian hubs.
        </p>
      </div>

    </div>

  </div>
</section>

<?php require_once __DIR__ . "/includes/footer.php"; ?>