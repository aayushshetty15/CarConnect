<?php
require_once __DIR__ . "/../core/middleware.php";
requireRole("seller");

require_once __DIR__ . "/../includes/db_connect.php";
require_once __DIR__ . "/../includes/functions.php";
require_once __DIR__ . "/../includes/header.php";

$sellerId = (int)$_SESSION['user_id'];

/* ===================== */
/* STATS */
/* ===================== */

$totalCars = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) c FROM car_listings WHERE seller_id=$sellerId
"))['c'];

$approvedCars = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) c FROM car_listings WHERE seller_id=$sellerId AND status='approved'
"))['c'];

$pendingCars = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) c FROM car_listings WHERE seller_id=$sellerId AND status='pending'
"))['c'];

$soldCars = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) c FROM car_listings WHERE seller_id=$sellerId AND status='sold'
"))['c'];

$totalOrders = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) c FROM orders WHERE seller_id=$sellerId
"))['c'];

$totalEarnings = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT IFNULL(SUM(total_price),0) s FROM orders WHERE seller_id=$sellerId
"))['s'];
?>

<div class="dashboard-wrapper">

  <!-- DASHBOARD HEADER -->
  <div class="dashboard-header">
    <div class="dashboard-badge">
      <span class="badge-dot-pulse"></span>
      <span>SELLER PORTAL // INVENTORY CONSOLE</span>
    </div>
    <h1 class="dashboard-title">SELLER DASHBOARD</h1>
    <p class="muted">Manage your vehicle listings, client purchase orders, verified inquiries, and earnings telemetry.</p>
  </div>

  <!-- ACTION NAVIGATION -->
  <div class="dashboard-nav">
    <a class="dashboard-nav-btn active" href="/carconnect/seller/add_car.php">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
        <line x1="12" y1="5" x2="12" y2="19"></line>
        <line x1="5" y1="12" x2="19" y2="12"></line>
      </svg>
      <span>ADD VEHICLE</span>
    </a>

    <a class="dashboard-nav-btn" href="/carconnect/seller/manage_listings.php">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <line x1="8" y1="6" x2="21" y2="6"></line>
        <line x1="8" y1="12" x2="21" y2="12"></line>
        <line x1="8" y1="18" x2="21" y2="18"></line>
        <line x1="3" y1="6" x2="3.01" y2="6"></line>
        <line x1="3" y1="12" x2="3.01" y2="12"></line>
        <line x1="3" y1="18" x2="3.01" y2="18"></line>
      </svg>
      <span>MANAGE LISTINGS</span>
    </a>

    <a class="dashboard-nav-btn" href="/carconnect/seller/search_cars.php">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <circle cx="11" cy="11" r="8"></circle>
        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
      </svg>
      <span>SEARCH INVENTORY</span>
    </a>

    <a class="dashboard-nav-btn" href="/carconnect/seller/view_orders.php">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
      </svg>
      <span>ORDERS</span>
    </a>

    <a class="dashboard-nav-btn" href="/carconnect/seller/view_reviews.php">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
      </svg>
      <span>REVIEWS</span>
    </a>

    <a class="dashboard-nav-btn" href="/carconnect/seller/messages.php">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
      </svg>
      <span>MESSAGES</span>
    </a>
  </div>

  <!-- METRICS TELEMETRY ROW -->
  <div class="metrics-row">
    <div class="metric-card">
      <div class="metric-title">TOTAL VEHICLES</div>
      <div class="metric-value"><?php echo intval($totalCars); ?></div>
    </div>

    <div class="metric-card">
      <div class="metric-title">VERIFIED APPROVED</div>
      <div class="metric-value green"><?php echo intval($approvedCars); ?></div>
    </div>

    <div class="metric-card">
      <div class="metric-title">PENDING REVIEW</div>
      <div class="metric-value amber"><?php echo intval($pendingCars); ?></div>
    </div>

    <div class="metric-card">
      <div class="metric-title">ACQUISITIONS SOLD</div>
      <div class="metric-value blue"><?php echo intval($soldCars); ?></div>
    </div>

    <div class="metric-card">
      <div class="metric-title">ORDERS LOGGED</div>
      <div class="metric-value"><?php echo intval($totalOrders); ?></div>
    </div>

    <div class="metric-card">
      <div class="metric-title">TOTAL REVENUE</div>
      <div class="metric-value gold">₹<?php echo number_format((float)$totalEarnings); ?></div>
    </div>
  </div>

  <!-- RECENT LISTINGS -->
  <div class="dashboard-subhead">
    <h2>RECENT VEHICLE LISTINGS</h2>
    <a class="btn btn-outline" style="font-size:12px;padding:8px 16px" href="/carconnect/seller/manage_listings.php">VIEW ALL</a>
  </div>

  <table class="table">
    <thead>
      <tr>
        <th>VEHICLE SPECIFICATION</th>
        <th>OFFERED PRICE</th>
        <th>VERIFICATION STATUS</th>
        <th>ACTION</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $stmt = mysqli_prepare($conn,"
      SELECT id,make,model,price,status
      FROM car_listings
      WHERE seller_id=?
      ORDER BY created_at DESC
      LIMIT 6
      ");

      mysqli_stmt_bind_param($stmt,"i",$sellerId);
      mysqli_stmt_execute($stmt);
      $res = mysqli_stmt_get_result($stmt);

      if(mysqli_num_rows($res) > 0){
        while($c=mysqli_fetch_assoc($res)){
          $status = e($c['status']);
          $badgeClass = "pending";
          if($c['status'] === "approved") $badgeClass = "approved";
          if($c['status'] === "sold") $badgeClass = "sold";

          echo "
          <tr>
            <td style='font-weight:700;color:#fff;'>".e($c['make'])." ".e($c['model'])."</td>
            <td style='color:var(--primary);font-weight:800;font-family:var(--font-heading);font-size:18px;'>₹".number_format((float)$c['price'])."</td>
            <td>
              <span class='badge $badgeClass'>
                ".strtoupper($status)."
              </span>
            </td>
            <td>
              <a class='btn btn-outline' style='padding:6px 14px;font-size:12px;' href='/carconnect/seller/edit_car.php?id=".intval($c['id'])."'>
                EDIT SPEC
              </a>
            </td>
          </tr>
          ";
        }
      } else {
        echo "<tr><td colspan='4' class='text-center muted' style='padding:30px;'>No active vehicle listings recorded in your console.</td></tr>";
      }
      ?>
    </tbody>
  </table>

  <!-- RECENT ORDERS -->
  <div class="dashboard-subhead">
    <h2>ACQUISITION ORDERS</h2>
    <a class="btn btn-outline" style="font-size:12px;padding:8px 16px" href="/carconnect/seller/view_orders.php">ALL ORDERS</a>
  </div>

  <table class="table">
    <thead>
      <tr>
        <th>ORDER ID</th>
        <th>VEHICLE</th>
        <th>BUYER CLIENT</th>
        <th>TRANSACTION VALUE</th>
        <th>STATUS</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $stmt = mysqli_prepare($conn,"
      SELECT 
      o.id,
      o.total_price,
      o.order_status,
      c.make,
      c.model,
      b.name buyer_name
      FROM orders o
      JOIN car_listings c ON c.id=o.car_id
      JOIN buyers b ON b.id=o.buyer_id
      WHERE o.seller_id=?
      ORDER BY o.id DESC
      LIMIT 5
      ");

      mysqli_stmt_bind_param($stmt,"i",$sellerId);
      mysqli_stmt_execute($stmt);
      $orders = mysqli_stmt_get_result($stmt);

      if(mysqli_num_rows($orders) > 0){
        while($o=mysqli_fetch_assoc($orders)){
          echo "
          <tr>
            <td style='font-family:var(--font-heading);font-weight:700;color:var(--primary);'>#".intval($o['id'])."</td>
            <td style='font-weight:700;color:#fff;'>".e($o['make'])." ".e($o['model'])."</td>
            <td>".e($o['buyer_name'])."</td>
            <td style='color:var(--primary);font-weight:800;font-family:var(--font-heading);font-size:18px;'>₹".number_format((float)$o['total_price'])."</td>
            <td><span class='badge approved'>".strtoupper(e($o['order_status']))."</span></td>
          </tr>
          ";
        }
      } else {
        echo "<tr><td colspan='5' class='text-center muted' style='padding:30px;'>No order transactions registered yet.</td></tr>";
      }
      ?>
    </tbody>
  </table>

  <!-- BUYER MESSAGES -->
  <div class="dashboard-subhead">
    <h2>CLIENT INQUIRIES & CONVERSATIONS</h2>
    <a class="btn btn-outline" style="font-size:12px;padding:8px 16px" href="/carconnect/seller/messages.php">ALL MESSAGES</a>
  </div>

  <table class="table">
    <thead>
      <tr>
        <th>PROSPECTIVE BUYER</th>
        <th>VEHICLE INTEREST</th>
        <th>LATEST CORRESPONDENCE</th>
        <th>ACTION</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $stmt = mysqli_prepare($conn,"
      SELECT 
      b.id AS buyer_id,
      b.name AS buyer_name,
      c.id AS car_id,
      c.make,
      c.model,
      m.message,
      MAX(m.created_at) AS last_time
      FROM messages m
      JOIN car_listings c ON c.id = m.car_id
      JOIN buyers b ON b.id = IF(m.sender_id=?, m.receiver_id, m.sender_id)
      WHERE c.seller_id = ?
      AND (m.sender_id = ? OR m.receiver_id = ?)
      GROUP BY c.id, b.id
      ORDER BY last_time DESC
      LIMIT 5
      ");

      mysqli_stmt_bind_param($stmt,"iiii",$sellerId,$sellerId,$sellerId,$sellerId);
      mysqli_stmt_execute($stmt);
      $res = mysqli_stmt_get_result($stmt);

      if(mysqli_num_rows($res)>0){
        while($row = mysqli_fetch_assoc($res)){
          echo "
          <tr>
            <td style='font-weight:700;color:#fff;'>".e($row['buyer_name'])."</td>
            <td style='color:var(--primary);font-weight:600;'>".e($row['make'])." ".e($row['model'])."</td>
            <td class='muted'>".mb_strimwidth(e($row['message']),0,45,'...')."</td>
            <td>
              <a class='btn btn-gold' style='padding:6px 14px;font-size:12px;'
              href='/carconnect/seller/chat.php?buyer=".$row['buyer_id']."&car_id=".$row['car_id']."'>
                OPEN DOSSIER
              </a>
            </td>
          </tr>
          ";
        }
      } else {
        echo "<tr><td colspan='4' class='text-center muted' style='padding:30px;'>No pending buyer inquiries.</td></tr>";
      }
      ?>
    </tbody>
  </table>

</div>

<?php require_once __DIR__ . "/../includes/footer.php"; ?>