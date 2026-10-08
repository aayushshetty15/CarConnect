<?php
require_once __DIR__ . "/../core/middleware.php";
requireRole("seller");

require_once __DIR__ . "/../includes/db_connect.php";
require_once __DIR__ . "/../includes/functions.php";
require_once __DIR__ . "/../includes/car_image_helpers.php";
require_once __DIR__ . "/../includes/header.php";

$id =
    (int)($_GET['id'] ?? 0);

$seller =
    (int)$_SESSION['user_id'];


/* ===================== */
/* FETCH CAR */
/* ===================== */

$stmt =
    mysqli_prepare(
        $conn,
        "
        SELECT *
        FROM car_listings
        WHERE id=? AND seller_id=?
        LIMIT 1
        "
    );

mysqli_stmt_bind_param(
    $stmt,
    "ii",
    $id,
    $seller
);

mysqli_stmt_execute($stmt);

$res =
    mysqli_stmt_get_result($stmt);

$car =
    mysqli_fetch_assoc($res);

mysqli_stmt_close($stmt);


if (!$car) {

    echo
        "<div class='alert'>
        Car not found.
        </div>";

    require_once __DIR__ .
        "/../includes/footer.php";

    exit();
}


/* ===================== */
/* DELETE CAR */
/* ===================== */

if (isset($_POST['delete'])) {

    $imageRows =
        cc_fetch_car_image_rows(
            $conn,
            $id
        );

    $pathsToDelete = [];

    foreach ($imageRows as $image) {
        $pathsToDelete[] =
            $image['image_path'];
    }

    if (
        !empty($car['image_path'])
    ) {
        $pathsToDelete[] =
            $car['image_path'];
    }

    $deleteStmt =
        mysqli_prepare(
            $conn,
            "
            DELETE FROM car_listings
            WHERE id=? AND seller_id=?
            "
        );

    mysqli_stmt_bind_param(
        $deleteStmt,
        "ii",
        $id,
        $seller
    );

    mysqli_stmt_execute(
        $deleteStmt
    );

    $deleted =
        mysqli_stmt_affected_rows(
            $deleteStmt
        );

    mysqli_stmt_close(
        $deleteStmt
    );

    if ($deleted > 0) {

        foreach (
            array_unique($pathsToDelete)
            as $path
        ) {
            cc_delete_uploaded_car_file(
                $path
            );
        }

        header(
            "Location: seller_dashboard.php?msg=deleted"
        );

        exit();
    }
}


$gallery =
    cc_build_car_gallery(
        $conn,
        $id,
        $car['image_path']
    );


/* CATEGORY */

$categoryName = "N/A";

$catStmt =
    mysqli_prepare(
        $conn,
        "
        SELECT name
        FROM car_categories
        WHERE id=?
        LIMIT 1
        "
    );

mysqli_stmt_bind_param(
    $catStmt,
    "i",
    $car['category_id']
);

mysqli_stmt_execute(
    $catStmt
);

$catRes =
    mysqli_stmt_get_result(
        $catStmt
    );

$cat =
    mysqli_fetch_assoc(
        $catRes
    );

if ($cat) {
    $categoryName =
        $cat['name'];
}

mysqli_stmt_close(
    $catStmt
);
?>

<div class="dashboard-wrapper">

  <div class="dashboard-header">
    <div class="dashboard-badge">
      <span class="badge-dot-pulse"></span>
      <span>INVENTORY // VEHICLE DOSSIER</span>
    </div>
    <div style="display:flex;justify-content:space-between;align-items:flex-end;flex-wrap:wrap;gap:16px;">
      <div>
        <h1 class="dashboard-title">
          <?php echo e($car['make']); ?> <span class="text-gold"><?php echo e($car['model']); ?></span>
        </h1>
        <p class="muted">Technical specification sheet, verified gallery photography, and listing status.</p>
      </div>
      <div style="display:flex;gap:10px;">
        <a class="btn btn-gold btn-chamfered" href="edit_car.php?id=<?php echo (int)$car['id']; ?>">
          <span>EDIT SPECIFICATIONS</span>
        </a>
        <form method="POST" onsubmit="return confirm('Permanently remove this vehicle listing?')">
          <button type="submit" name="delete" value="1" class="btn btn-logout" style="border-color:rgba(239,68,68,0.5);">
            DELETE
          </button>
        </form>
      </div>
    </div>
  </div>

  <div class="card" style="max-width:1100px;margin:20px auto;overflow:hidden;">
    
    <!-- Gallery -->
    <div style="position:relative;background:#0d0e12;">
      <img id="sellerMainCarImage" class="car-gallery-main" src="<?php echo e($gallery[0]); ?>" alt="<?php echo e($car['make']." ".$car['model']); ?>" style="height:500px;width:100%;object-fit:cover;">
      <span class="status-chip chip-<?php echo ($car['status'] == 'approved' ? 'approved' : ($car['status'] == 'sold' ? 'sold' : 'pending')); ?>" style="top:20px;left:20px;">
        <?php echo strtoupper(e($car['status'])); ?>
      </span>
    </div>

    <?php if (count($gallery) > 1): ?>
    <div class="car-gallery-thumbs" style="background:#090a0d;display:flex;gap:12px;padding:16px;overflow-x:auto;border-top:1px solid var(--border);">
      <?php foreach ($gallery as $index => $image): ?>
      <button type="button" class="car-gallery-thumb <?php echo $index === 0 ? 'active' : ''; ?>" data-image="<?php echo e($image); ?>" style="width:110px;height:75px;min-width:110px;border-radius:2px;overflow:hidden;background:#000;cursor:pointer;padding:0;">
        <img src="<?php echo e($image); ?>" alt="Thumbnail" style="width:100%;height:100%;object-fit:cover;">
      </button>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <div style="padding:36px;">

      <!-- Valuation Strip -->
      <div class="price-box" style="margin-bottom:30px;">
        <p>CURRENT MARKET VALUATION</p>
        <h2>₹<?php echo number_format((float)$car['price']); ?></h2>
      </div>

      <!-- Technical Grid -->
      <h3 class="section-title-dossier">TECHNICAL SPECIFICATIONS</h3>
      
      <div class="quick-specs" style="margin-bottom:35px;">
        <div class="spec">
          <div class="spec-label">MANUFACTURING YEAR</div>
          <div class="spec-val"><?php echo e($car['year']); ?></div>
        </div>

        <div class="spec">
          <div class="spec-label">ODOMETER MILEAGE</div>
          <div class="spec-val"><?php echo number_format((int)$car['mileage']); ?> KM</div>
        </div>

        <div class="spec">
          <div class="spec-label">POWERTRAIN FUEL</div>
          <div class="spec-val"><?php echo strtoupper(e($car['fuel_type'])); ?></div>
        </div>

        <div class="spec">
          <div class="spec-label">TRANSMISSION</div>
          <div class="spec-val"><?php echo strtoupper(e($car['transmission'])); ?></div>
        </div>

        <div class="spec">
          <div class="spec-label">EXTERIOR COLOR</div>
          <div class="spec-val"><?php echo strtoupper(e($car['color'])); ?></div>
        </div>

        <div class="spec">
          <div class="spec-label">OWNERSHIP HISTORY</div>
          <div class="spec-val"><?php echo strtoupper(e($car['owner_type'])); ?></div>
        </div>

        <div class="spec">
          <div class="spec-label">BODY ARCHITECTURE</div>
          <div class="spec-val"><?php echo strtoupper(e($car['body_type'])); ?></div>
        </div>

        <div class="spec">
          <div class="spec-label">SEATING CAPACITY</div>
          <div class="spec-val"><?php echo e($car['seating_capacity']); ?> OCCUPANTS</div>
        </div>

        <div class="spec" style="grid-column: 1 / -1;">
          <div class="spec-label">VEHICLE LOCATION</div>
          <div class="spec-val"><?php echo strtoupper(e($car['location'])); ?></div>
        </div>
      </div>

      <!-- Description -->
      <h3 class="section-title-dossier">SELLER DOSSIER & CONDITION</h3>
      <div class="description-box" style="margin-bottom:30px;">
        <?php echo nl2br(e($car['description'] ?: 'No detailed narrative entered for this vehicle.')); ?>
      </div>

    </div>

  </div>

</div>


<script>

document
.querySelectorAll('.car-gallery-thumb')
.forEach(function(button){

    button.addEventListener(
        'click',
        function(){

            const image =
                this.getAttribute(
                    'data-image'
                );

            document
                .getElementById(
                    'sellerMainCarImage'
                )
                .src = image;

            document
                .querySelectorAll(
                    '.car-gallery-thumb'
                )
                .forEach(function(item){
                    item.classList.remove(
                        'active'
                    );
                });

            this.classList.add(
                'active'
            );
        }
    );
});

</script>


<?php
require_once __DIR__ .
    "/../includes/footer.php";
?>