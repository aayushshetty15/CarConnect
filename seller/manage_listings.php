<?php
require_once __DIR__ . "/../core/middleware.php";
requireRole("seller");

require_once __DIR__ . "/../includes/db_connect.php";
require_once __DIR__ . "/../includes/functions.php";
require_once __DIR__ . "/../includes/header.php";

$sellerId = (int)$_SESSION['user_id'];

/* ===================== */
/* DELETE */
/* ===================== */

if(isset($_GET['delete'])){
  $id = (int)$_GET['delete'];

  $stmt = mysqli_prepare($conn,"
  DELETE FROM car_listings 
  WHERE id=? AND seller_id=?
  ");
  mysqli_stmt_bind_param($stmt,"ii",$id,$sellerId);
  mysqli_stmt_execute($stmt);

  echo "<div class='alert success'>Vehicle listing removed from portfolio successfully.</div>";
}
?>

<div class="dashboard-wrapper">

  <div class="dashboard-header">
    <div class="dashboard-badge">
      <span class="badge-dot-pulse"></span>
      <span>INVENTORY // PORTFOLIO MANAGEMENT</span>
    </div>
    <div style="display:flex;justify-content:space-between;align-items:flex-end;flex-wrap:wrap;gap:16px;">
      <div>
        <h1 class="dashboard-title">MANAGE VEHICLE LISTINGS</h1>
        <p class="muted">Review verification statuses, update specifications, or retire vehicles from the active marketplace.</p>
      </div>
      <a class="btn btn-gold btn-chamfered" href="/carconnect/seller/add_car.php">
        <span>+ REGISTER NEW CAR</span>
      </a>
    </div>
  </div>

  <div class="car-grid" style="margin-top:30px;">

  <?php
  $stmt = mysqli_prepare($conn,"
  SELECT id,make,model,year,status,price,image_path
  FROM car_listings
  WHERE seller_id=?
  ORDER BY created_at DESC
  ");

  mysqli_stmt_bind_param($stmt,"i",$sellerId);
  mysqli_stmt_execute($stmt);
  $res = mysqli_stmt_get_result($stmt);

  if(mysqli_num_rows($res)>0){
    while($c=mysqli_fetch_assoc($res)){
      $img = $c['image_path'] ?: "https://images.unsplash.com/photo-1503376780353-7e6692767b70?auto=format&fit=crop&w=900&q=80";
      
      $status = e($c['status']);
      $badgeClass = "pending";
      if($c['status']=="approved") $badgeClass = "approved";
      if($c['status']=="sold")     $badgeClass = "sold";

      echo "
      <div class='luxury-car-card'>
        <div class='luxury-car-media'>
          <img src='".e($img)."' alt='".e($c['make']." ".$c['model'])."'>
          <span class='status-chip chip-$badgeClass'>".strtoupper($status)."</span>
          <div class='media-overlay'></div>
        </div>

        <div class='luxury-car-body'>
          <div class='luxury-car-specs'>
            <span class='spec-tag'>".e($c['year'])."</span>
          </div>

          <h3 class='luxury-car-title'>
            ".e($c['make']." ".$c['model'])."
          </h3>

          <div class='luxury-car-price-row'>
            <div class='price-label'>OFFERED VALUE</div>
            <div class='luxury-car-price'>
              ₹".number_format($c['price'])."
            </div>
          </div>

          <div style='display:flex;gap:10px;margin-top:10px;'>
            <a class='btn btn-outline' style='flex:1;padding:10px;font-size:12px;' href='/carconnect/seller/edit_car.php?id=".$c['id']."'>
              EDIT
            </a>
            <a class='btn btn-logout' style='padding:10px 14px;font-size:12px;'
               onclick=\"return confirm('Confirm deletion of this vehicle dossier?')\"
               href='?delete=".$c['id']."'>
              DELETE
            </a>
          </div>
        </div>
      </div>
      ";
    }
  } else {
    echo "
    <div class='luxury-locked-box' style='grid-column: 1 / -1;margin-top:40px;'>
      <h3 class='locked-title'>NO REGISTERED VEHICLES</h3>
      <p class='locked-desc'>Your seller portfolio is currently empty. List your first performance or luxury automobile now.</p>
      <a class='btn btn-gold btn-chamfered' href='/carconnect/seller/add_car.php'>
        <span>ADD FIRST VEHICLE</span>
      </a>
    </div>
    ";
  }
  ?>

  </div>

</div>

<?php require_once __DIR__ . "/../includes/footer.php"; ?>