<?php
require_once __DIR__ . "/../core/middleware.php";
requireRole("seller");

require_once __DIR__ . "/../includes/db_connect.php";
require_once __DIR__ . "/../includes/functions.php";
require_once __DIR__ . "/../includes/header.php";

$sellerId = (int)$_SESSION['user_id'];

/* FILTERS */
$keyword      = trim($_GET['q'] ?? '');
$brand        = trim($_GET['brand'] ?? '');
$minPrice     = (int)($_GET['min'] ?? 0);
$maxPrice     = (int)($_GET['max'] ?? 0);

$fuel         = trim($_GET['fuel_type'] ?? '');
$transmission = trim($_GET['transmission'] ?? '');
$status       = trim($_GET['status'] ?? '');
$year         = trim($_GET['year'] ?? '');
$location     = trim($_GET['location'] ?? '');
?>

<div class="dashboard-wrapper">

  <div class="dashboard-header">
    <div class="dashboard-badge">
      <span class="badge-dot-pulse"></span>
      <span>INVENTORY // ADVANCED QUERY</span>
    </div>
    <h1 class="dashboard-title">SEARCH VEHICLE PORTFOLIO</h1>
    <p class="muted">Query and filter your vehicle listings by technical parameters, verification status, and valuation range.</p>
  </div>

  <form method="GET" class="card" style="padding:30px;margin-bottom:35px;">
    <div class="grid" style="grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px;">
      
      <div>
        <label>Keyword</label>
        <input class="input" name="q" value="<?php echo e($keyword); ?>" placeholder="Brand or Model">
      </div>

      <div>
        <label>Marque</label>
        <select class="input" name="brand">
          <option value="">All Marques</option>
          <?php
          $brands = ["Maruti", "Tata", "Mahindra", "Hyundai", "Honda", "Toyota", "Kia", "BMW", "Audi", "Mercedes-Benz", "Porsche"];
          foreach($brands as $b){
            $sel = ($brand == $b) ? "selected" : "";
            echo "<option $sel>$b</option>";
          }
          ?>
        </select>
      </div>

      <div>
        <label>Min Price (₹)</label>
        <input class="input" type="number" name="min" value="<?php echo $minPrice ?: ''; ?>" placeholder="Min">
      </div>

      <div>
        <label>Max Price (₹)</label>
        <input class="input" type="number" name="max" value="<?php echo $maxPrice ?: ''; ?>" placeholder="Max">
      </div>

      <div>
        <label>Fuel Type</label>
        <select class="input" name="fuel_type">
          <option value="">All</option>
          <option <?php if($fuel=="Petrol") echo "selected"; ?>>Petrol</option>
          <option <?php if($fuel=="Diesel") echo "selected"; ?>>Diesel</option>
          <option <?php if($fuel=="CNG") echo "selected"; ?>>CNG</option>
          <option <?php if($fuel=="Electric") echo "selected"; ?>>Electric</option>
          <option <?php if($fuel=="Hybrid") echo "selected"; ?>>Hybrid</option>
        </select>
      </div>

      <div>
        <label>Transmission</label>
        <select class="input" name="transmission">
          <option value="">All</option>
          <option <?php if($transmission=="Manual") echo "selected"; ?>>Manual</option>
          <option <?php if($transmission=="Automatic") echo "selected"; ?>>Automatic</option>
        </select>
      </div>

      <div>
        <label>Status</label>
        <select class="input" name="status">
          <option value="">All Statuses</option>
          <option <?php if($status=="approved") echo "selected"; ?>>Approved</option>
          <option <?php if($status=="pending") echo "selected"; ?>>Pending</option>
          <option <?php if($status=="sold") echo "selected"; ?>>Sold</option>
        </select>
      </div>

      <div>
        <label>Year</label>
        <input class="input" type="number" name="year" value="<?php echo e($year); ?>" placeholder="e.g. 2023">
      </div>

      <div>
        <label>Location</label>
        <input class="input" type="text" name="location" value="<?php echo e($location); ?>" placeholder="City">
      </div>

    </div>

    <div style="display:flex;gap:14px;margin-top:24px;">
      <button class="btn btn-gold btn-chamfered" type="submit">
        <span>EXECUTE FILTER</span>
      </button>
      <a class="btn btn-outline" href="search_cars.php">
        RESET FILTERS
      </a>
    </div>
  </form>

  <div class="car-grid">
  <?php
  $sql = "SELECT id, make, model, year, price, status, image_path FROM car_listings WHERE seller_id = ?";
  $params = [$sellerId];
  $types = "i";

  if($keyword){
    $sql .= " AND (make LIKE ? OR model LIKE ?)";
    $types .= "ss";
    $search = "%$keyword%";
    $params[] = $search;
    $params[] = $search;
  }

  if($brand){
    $sql .= " AND make=?";
    $types .= "s";
    $params[] = $brand;
  }

  if($fuel){
    $sql .= " AND fuel_type=?";
    $types .= "s";
    $params[] = $fuel;
  }

  if($transmission){
    $sql .= " AND transmission=?";
    $types .= "s";
    $params[] = $transmission;
  }

  if($status){
    $sql .= " AND status=?";
    $types .= "s";
    $params[] = $status;
  }

  if($year){
    $sql .= " AND year=?";
    $types .= "i";
    $params[] = (int)$year;
  }

  if($location){
    $sql .= " AND location LIKE ?";
    $types .= "s";
    $params[] = "%".$location."%";
  }

  if($minPrice > 0){
    $sql .= " AND price >= ?";
    $types .= "i";
    $params[] = $minPrice;
  }

  if($maxPrice > 0){
    $sql .= " AND price <= ?";
    $types .= "i";
    $params[] = $maxPrice;
  }

  $sql .= " ORDER BY created_at DESC";

  $stmt = mysqli_prepare($conn, $sql);
  mysqli_stmt_bind_param($stmt, $types, ...$params);
  mysqli_stmt_execute($stmt);
  $res = mysqli_stmt_get_result($stmt);

  if(mysqli_num_rows($res) > 0){
    while($c = mysqli_fetch_assoc($res)){
      $img = $c['image_path'] ?: "https://images.unsplash.com/photo-1503376780353-7e6692767b70?auto=format&fit=crop&w=900&q=80";
      
      $status = e($c['status']);
      $badgeClass = "pending";
      if($c['status'] == "approved") $badgeClass = "approved";
      if($c['status'] == "sold")     $badgeClass = "sold";

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
            <div class='price-label'>VALUATION</div>
            <div class='luxury-car-price'>
              ₹".number_format($c['price'])."
            </div>
          </div>

          <div style='margin-top:12px;'>
            <a class='btn btn-outline' style='width:100%;font-size:12px;padding:10px;' href='edit_car.php?id=".$c['id']."'>
              EDIT DOSSIER
            </a>
          </div>
        </div>
      </div>
      ";
    }
  } else {
    echo "
    <div class='luxury-locked-box' style='grid-column: 1 / -1;margin:30px auto;'>
      <h3 class='locked-title'>NO MATCHING VEHICLES</h3>
      <p class='locked-desc'>No vehicles match your active search filters. Try broadening your criteria or reset the search.</p>
      <a class='btn btn-gold btn-chamfered' href='add_car.php'>
        <span>ADD NEW VEHICLE</span>
      </a>
    </div>
    ";
  }
  ?>
  </div>

</div>

<?php require_once __DIR__ . "/../includes/footer.php"; ?>