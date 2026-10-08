<?php
require_once __DIR__ . "/../core/middleware.php";
requireRole("seller");

require_once __DIR__ . "/../includes/db_connect.php";
require_once __DIR__ . "/../includes/functions.php";
require_once __DIR__ . "/../includes/car_image_helpers.php";
require_once __DIR__ . "/../includes/header.php";

$sellerId = (int)$_SESSION['user_id'];

$err = "";
$ok  = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $brand        = trim($_POST['brand'] ?? '');
    $model        = trim($_POST['model'] ?? '');
    $year         = (int)($_POST['year'] ?? 0);
    $price        = (float)($_POST['price'] ?? 0);
    $mileage      = (int)($_POST['mileage'] ?? 0);
    $fuelType     = trim($_POST['fuel_type'] ?? '');
    $transmission = trim($_POST['transmission'] ?? '');

    $color       = trim($_POST['color'] ?? '');
    $ownerType   = trim($_POST['owner_type'] ?? '');
    $bodyType    = trim($_POST['body_type'] ?? '');
    $seating     = (int)($_POST['seating_capacity'] ?? 0);
    $location    = trim($_POST['location'] ?? '');

    $description = trim($_POST['description'] ?? '');
    $category_id = (int)($_POST['category_id'] ?? 0);

    if (
        $brand === "" ||
        $model === "" ||
        $year <= 0 ||
        $price <= 0 ||
        $category_id <= 0 ||
        $color === "" ||
        $ownerType === "" ||
        $bodyType === "" ||
        $seating <= 0 ||
        $location === ""
    ) {
        $err = "Please fill all required fields properly.";
    }

    $uploads = [];

    if ($err === "") {
        try {
            $uploads =
                cc_collect_car_uploads(
                    $_FILES['images'] ?? []
                );
        } catch (Throwable $e) {
            $err = $e->getMessage();
        }
    }

    if ($err === "") {

        $imagePath = CC_DEFAULT_CAR_IMAGE;
        $status = "pending";

        $movedFiles = [];

        try {

            mysqli_begin_transaction($conn);

            $stmt = mysqli_prepare(
                $conn,
                "
                INSERT INTO car_listings
                (
                    seller_id,
                    make,
                    model,
                    year,
                    price,
                    mileage,
                    fuel_type,
                    transmission,
                    color,
                    owner_type,
                    body_type,
                    seating_capacity,
                    location,
                    description,
                    image_path,
                    status,
                    category_id
                )
                VALUES
                (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)
                "
            );

            if (!$stmt) {
                throw new Exception(
                    "Could not prepare car insert."
                );
            }

            mysqli_stmt_bind_param(
                $stmt,
                "issidisssssissssi",
                $sellerId,
                $brand,
                $model,
                $year,
                $price,
                $mileage,
                $fuelType,
                $transmission,
                $color,
                $ownerType,
                $bodyType,
                $seating,
                $location,
                $description,
                $imagePath,
                $status,
                $category_id
            );

            if (!mysqli_stmt_execute($stmt)) {
                throw new Exception(
                    "Database error while adding car."
                );
            }

            $carId =
                mysqli_insert_id($conn);

            mysqli_stmt_close($stmt);

            $savedImages =
                cc_store_car_images(
                    $conn,
                    $carId,
                    $uploads,
                    $movedFiles
                );

            if ($savedImages) {

                $imagePath =
                    $savedImages[0];

                $coverStmt =
                    mysqli_prepare(
                        $conn,
                        "
                        UPDATE car_listings
                        SET image_path=?
                        WHERE id=? AND seller_id=?
                        "
                    );

                mysqli_stmt_bind_param(
                    $coverStmt,
                    "sii",
                    $imagePath,
                    $carId,
                    $sellerId
                );

                if (
                    !mysqli_stmt_execute(
                        $coverStmt
                    )
                ) {
                    throw new Exception(
                        "Could not set cover image."
                    );
                }

                mysqli_stmt_close(
                    $coverStmt
                );
            }

            mysqli_commit($conn);

            $ok =
                "Car added successfully. Waiting for admin approval.";

            $_POST = [];

        } catch (Throwable $e) {

            mysqli_rollback($conn);

            foreach (
                $movedFiles
                as $file
            ) {
                if (is_file($file)) {
                    @unlink($file);
                }
            }

            $err =
                $e->getMessage();
        }
    }
}
?>

<div class="dashboard-wrapper">

  <div class="dashboard-header">
    <div class="dashboard-badge">
      <span class="badge-dot-pulse"></span>
      <span>INVENTORY // NEW REGISTRATION</span>
    </div>
    <h1 class="dashboard-title">ADD VEHICLE LISTING</h1>
    <p class="muted">Submit comprehensive vehicle technical specifications, pricing, and multi-angle imagery for verification.</p>
  </div>

  <?php if ($err): ?>
  <div class="alert">
    <?php echo e($err); ?>
  </div>
  <?php endif; ?>

  <?php if ($ok): ?>
  <div class="alert success">
    <?php echo e($ok); ?>
  </div>
  <?php endif; ?>

  <form class="card form-wide" method="POST" enctype="multipart/form-data" style="margin:20px auto;padding:40px;">

    <!-- SECTION 1: CORE DETAILS -->
    <h3 style="color:var(--primary);margin-bottom:20px;font-size:18px;border-bottom:1px solid var(--border);padding-bottom:8px;">
      01 // CORE SPECIFICATIONS
    </h3>

    <div class="grid two">
      <div>
        <label>Category</label>
        <select class="input" name="category_id" required>
          <option value="">Select Category</option>
          <?php
          $res = mysqli_query($conn, "SELECT * FROM car_categories ORDER BY name ASC");
          while ($cat = mysqli_fetch_assoc($res)) {
              $selected = ((int)($_POST['category_id'] ?? 0) === (int)$cat['id']) ? "selected" : "";
              echo "<option value='" . (int)$cat['id'] . "' $selected>" . e($cat['name']) . "</option>";
          }
          ?>
        </select>
      </div>

      <div>
        <label>Marque / Brand</label>
        <input class="input" list="brandList" name="brand" placeholder="Type or select brand" value="<?php echo e($_POST['brand'] ?? ''); ?>" required>
        <datalist id="brandList">
          <option value="Maruti">
          <option value="Tata">
          <option value="Mahindra">
          <option value="Hyundai">
          <option value="Honda">
          <option value="Toyota">
          <option value="Kia">
          <option value="BMW">
          <option value="Audi">
          <option value="Mercedes-Benz">
          <option value="Porsche">
        </datalist>
      </div>

      <div>
        <label>Model & Trim Variant</label>
        <input class="input" type="text" name="model" placeholder="e.g. City ZX, Creta SX" value="<?php echo e($_POST['model'] ?? ''); ?>" required>
      </div>

      <div>
        <label>Manufacturing Year</label>
        <input class="input" type="number" name="year" min="1990" max="<?php echo date('Y') + 1; ?>" placeholder="e.g. 2022" value="<?php echo e($_POST['year'] ?? ''); ?>" required>
      </div>

      <div>
        <label>Offered Price (₹)</label>
        <input class="input" type="number" step="1000" min="10000" name="price" placeholder="e.g. 1250000" value="<?php echo e($_POST['price'] ?? ''); ?>" required>
      </div>

      <div>
        <label>Odometer Mileage (km)</label>
        <input class="input" type="number" min="0" name="mileage" placeholder="e.g. 24000" value="<?php echo e($_POST['mileage'] ?? ''); ?>">
      </div>
    </div>

    <!-- SECTION 2: TECHNICAL & ATTRIBUTES -->
    <h3 style="color:var(--primary);margin:35px 0 20px;font-size:18px;border-bottom:1px solid var(--border);padding-bottom:8px;">
      02 // TECHNICAL CONFIGURATION
    </h3>

    <div class="grid two">
      <div>
        <label>Fuel Type</label>
        <select class="input" name="fuel_type">
          <?php
          $fuels = ["Petrol", "Diesel", "CNG", "Electric", "Hybrid"];
          foreach ($fuels as $fuel) {
              $selected = (($_POST['fuel_type'] ?? 'Petrol') === $fuel) ? "selected" : "";
              echo "<option $selected>" . e($fuel) . "</option>";
          }
          ?>
        </select>
      </div>

      <div>
        <label>Transmission</label>
        <select class="input" name="transmission">
          <?php
          $transmissions = ["Manual", "Automatic", "Dual-Clutch / DCT", "CVT"];
          foreach ($transmissions as $transmission) {
              $selected = (($_POST['transmission'] ?? 'Manual') === $transmission) ? "selected" : "";
              echo "<option $selected>" . e($transmission) . "</option>";
          }
          ?>
        </select>
      </div>

      <div>
        <label>Exterior Paint / Color</label>
        <select class="input" name="color" required>
          <option value="">Select Color</option>
          <?php
          $colors = ["White", "Black", "Silver", "Grey", "Blue", "Red", "Brown", "Green", "Yellow", "Gold"];
          foreach ($colors as $color) {
              $selected = (($_POST['color'] ?? '') === $color) ? "selected" : "";
              echo "<option $selected>" . e($color) . "</option>";
          }
          ?>
        </select>
      </div>

      <div>
        <label>Ownership Record</label>
        <select class="input" name="owner_type" required>
          <option value="">Select Ownership</option>
          <?php
          $owners = ["First Owner", "Second Owner", "Third Owner", "Fourth Owner"];
          foreach ($owners as $owner) {
              $selected = (($_POST['owner_type'] ?? '') === $owner) ? "selected" : "";
              echo "<option $selected>" . e($owner) . "</option>";
          }
          ?>
        </select>
      </div>

      <div>
        <label>Body Architecture</label>
        <select class="input" name="body_type" required>
          <option value="">Select Body Type</option>
          <?php
          $bodies = ["SUV", "Sedan", "Hatchback", "MUV", "Coupe", "Convertible"];
          foreach ($bodies as $body) {
              $selected = (($_POST['body_type'] ?? '') === $body) ? "selected" : "";
              echo "<option $selected>" . e($body) . "</option>";
          }
          ?>
        </select>
      </div>

      <div>
        <label>Seating Capacity</label>
        <select class="input" name="seating_capacity" required>
          <option value="">Select Seats</option>
          <?php
          foreach ([2, 4, 5, 6, 7, 8] as $seat) {
              $selected = ((int)($_POST['seating_capacity'] ?? 0) === $seat) ? "selected" : "";
              echo "<option value='$seat' $selected>$seat Occupants</option>";
          }
          ?>
        </select>
      </div>

      <div style="grid-column: 1 / -1;">
        <label>Vehicle Location (City, State)</label>
        <input class="input" type="text" name="location" placeholder="e.g. Mumbai, Maharashtra" value="<?php echo e($_POST['location'] ?? ''); ?>" required>
      </div>
    </div>

    <!-- SECTION 3: DOSSIER & MEDIA -->
    <h3 style="color:var(--primary);margin:35px 0 20px;font-size:18px;border-bottom:1px solid var(--border);padding-bottom:8px;">
      03 // VEHICLE NARRATIVE & PHOTOGRAPHY
    </h3>

    <div style="margin-bottom:20px;">
      <label>Technical Description & Condition Summary</label>
      <textarea class="input" name="description" rows="5" placeholder="Detail the vehicle service history, maintenance records, insurance status, and any upgrades..."><?php echo e($_POST['description'] ?? ''); ?></textarea>
    </div>

    <div style="margin-bottom:30px;">
      <label>Vehicle Gallery Images</label>
      <input class="input" type="file" name="images[]" accept=".jpg,.jpeg,.png,.webp" multiple>
      <p class="muted" style="font-size:12px;margin-top:6px;">Upload up to 10 high-resolution photographs (Max 5MB each). Primary image serves as the catalogue cover.</p>
    </div>

    <div class="text-center">
      <button class="btn btn-gold btn-chamfered" style="padding:16px 40px;font-size:15px;">
        <span>PUBLISH FOR VERIFICATION</span>
      </button>
    </div>

  </form>

</div>

<?php
require_once __DIR__ . "/../includes/footer.php";
?>