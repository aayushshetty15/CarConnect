<?php
require_once __DIR__ . "/../includes/db_connect.php";
require_once __DIR__ . "/../includes/functions.php";
require_once __DIR__ . "/../includes/car_image_helpers.php";
require_once __DIR__ . "/../includes/header.php";

$id =
    (int)($_GET['id'] ?? 0);


$stmt =
    mysqli_prepare(
        $conn,
        "
        SELECT
            c.*,
            s.name AS seller_name,
            s.id AS seller_id,
            s.phone
        FROM car_listings c
        LEFT JOIN sellers s
        ON s.id=c.seller_id
        WHERE c.id=?
        LIMIT 1
        "
    );

mysqli_stmt_bind_param(
    $stmt,
    "i",
    $id
);

mysqli_stmt_execute(
    $stmt
);

$res =
    mysqli_stmt_get_result(
        $stmt
    );

$car =
    mysqli_fetch_assoc(
        $res
    );

mysqli_stmt_close(
    $stmt
);


if (!$car) {

    echo
        '<div class="alert">
        Car not found.
        </div>';

    require_once __DIR__ .
        "/../includes/footer.php";

    exit();
}


$gallery =
    cc_build_car_gallery(
        $conn,
        $id,
        $car['image_path']
    );
?>


<style>

body{
    background:#f4f7fb;
}

.car-details-container{
    max-width:1250px;
    margin:40px auto;
    padding:20px;
}

.car-header{
    text-align:center;
    margin-bottom:25px;
}

.car-header h1{
    font-size:38px;
    color:#111827;
    font-weight:700;
}

.car-layout{
    display:grid;
    grid-template-columns:2.2fr 1fr;
    gap:30px;
    align-items:start;
}

.car-image-card,
.info-card{
    background:var(--card);
    border:1px solid var(--border);
    border-radius:4px;
    overflow:hidden;
    box-shadow:var(--shadow-sm);
}

.car-image{
    width:100%;
    height:520px;
    object-fit:cover;
    display:block;
    background:#0d0e12;
}

.car-gallery{
    background:#090a0d;
    display:flex;
    gap:12px;
    padding:14px;
    overflow-x:auto;
    border-top:1px solid var(--border);
}

.car-thumb{
    width:105px;
    height:76px;
    min-width:105px;
    border:2px solid transparent;
    border-radius:2px;
    overflow:hidden;
    cursor:pointer;
    padding:0;
    background:#000;
    transition:var(--transition-fast);
}

.car-thumb img{
    width:100%;
    height:100%;
    object-fit:cover;
    display:block;
}

.car-thumb.active{
    border-color:var(--primary);
    box-shadow:0 0 12px var(--primary-glow);
}

.car-content{
    padding:28px;
}

.price-box{
    background:linear-gradient(135deg, rgba(229,169,60,0.15) 0%, rgba(182,127,27,0.25) 100%);
    border:1px solid var(--border-gold);
    color:#fff;
    border-radius:4px;
    padding:25px;
    text-align:center;
    margin-bottom:25px;
}

.price-box p{
    font-family:var(--font-heading);
    font-size:13px;
    letter-spacing:2px;
    text-transform:uppercase;
    color:var(--text-muted);
    margin:0;
}

.price-box h2{
    font-family:var(--font-heading);
    margin:8px 0 0;
    font-size:42px;
    font-weight:800;
    color:var(--primary);
}

.section-title{
    margin:30px 0 15px;
    font-family:var(--font-heading);
    font-size:22px;
    font-weight:800;
    letter-spacing:1.5px;
    text-transform:uppercase;
    color:#ffffff;
    border-left:4px solid var(--primary);
    padding-left:12px;
}

.quick-specs,
.spec-table{
    display:grid;
    grid-template-columns:repeat(2,1fr);
    gap:15px;
}

.spec,
.spec-item{
    background:#0d0e12;
    border:1px solid var(--border);
    border-radius:2px;
    padding:16px 20px;
    color:var(--text-muted);
    font-size:12px;
    text-transform:uppercase;
    letter-spacing:1px;
    font-weight:600;
}

.spec b,
.spec-item strong{
    display:block;
    margin-top:6px;
    font-family:var(--font-heading);
    font-size:20px;
    font-weight:800;
    color:#ffffff;
}

.seller-box{
    text-align:center;
    background:var(--card);
    border-radius:4px;
    padding:28px;
    border:1px solid var(--border);
}

.seller-box h3{
    font-family:var(--font-heading);
    font-size:22px;
    font-weight:800;
    letter-spacing:1.5px;
    text-transform:uppercase;
    color:#ffffff;
    margin-bottom:10px;
}

.seller-box p{
    margin:15px 0;
    font-size:15px;
    color:var(--text-secondary);
}

.badge{
    display:inline-block;
    background:rgba(16,185,129,0.15);
    border:1px solid rgba(16,185,129,0.3);
    color:#34d399;
    padding:6px 16px;
    border-radius:2px;
    font-family:var(--font-heading);
    font-size:12px;
    font-weight:700;
    letter-spacing:1px;
    text-transform:uppercase;
    margin-top:10px;
}

.action-buttons{
    display:flex;
    flex-direction:column;
    gap:12px;
    margin-top:25px;
}

.action-buttons .btn,
.seller-box .btn{
    width:100%;
    text-align:center;
    padding:14px;
    border-radius:2px;
    font-family:var(--font-heading);
    font-size:14px;
    font-weight:700;
    letter-spacing:1.5px;
    text-transform:uppercase;
}

.description{
    background:#0d0e12;
    padding:22px;
    border-radius:2px;
    border:1px solid var(--border);
    line-height:1.8;
    color:var(--text-secondary);
    font-size:15px;
}

@media(max-width:900px){
    .car-layout{
        grid-template-columns:1fr;
    }
    .car-image{
        height:300px;
    }
    .quick-specs,
    .spec-table{
        grid-template-columns:1fr;
    }
}
</style>

<div class="car-details-container">

<div class="car-header">
  <div class="dashboard-badge">
    <span class="badge-dot-pulse"></span>
    <span>CATALOGUE // VERIFIED SPECIFICATION</span>
  </div>
  <h1 style="font-size:42px;">
    <?php echo e($car['make']); ?> <span class="text-gold"><?php echo e($car['model']); ?></span>
  </h1>
</div>


<div class="car-layout">


<div>


<div class="car-image-card">


<img
id="buyerMainCarImage"
class="car-image"
src="<?php echo e($gallery[0]); ?>"
alt="Car Image"
>


<?php if (count($gallery) > 1): ?>

<div class="car-gallery">

<?php
foreach (
    $gallery
    as $index => $image
):
?>

<button
type="button"
class="car-thumb <?php echo $index === 0 ? 'active' : ''; ?>"
data-image="<?php echo e($image); ?>"
>

<img
src="<?php echo e($image); ?>"
alt="Car Thumbnail"
>

</button>

<?php endforeach; ?>

</div>

<?php endif; ?>


<div class="car-content">


<div class="price-box">

<p>Price</p>

<h2>

₹<?php echo number_format((float)$car['price']); ?>

</h2>

</div>

<div class="price-box">
  <p>CERTIFIED ACQUISITION VALUATION</p>
  <h2>₹<?php echo number_format((float)$car['price']); ?></h2>
</div>

<h3 class="section-title">CORE SPECIFICATIONS</h3>

<div class="quick-specs">
  <div class="spec">
    <div class="spec-label">MANUFACTURING YEAR</div>
    <b><?php echo e($car['year']); ?></b>
  </div>

  <div class="spec">
    <div class="spec-label">ODOMETER MILEAGE</div>
    <b><?php echo number_format((int)$car['mileage']); ?> KM</b>
  </div>

  <div class="spec">
    <div class="spec-label">POWERTRAIN FUEL</div>
    <b><?php echo strtoupper(e($car['fuel_type'])); ?></b>
  </div>

  <div class="spec">
    <div class="spec-label">TRANSMISSION</div>
    <b><?php echo strtoupper(e($car['transmission'])); ?></b>
  </div>
</div>

<h3 class="section-title">TECHNICAL CONFIGURATION</h3>

<div class="spec-table">
  <div class="spec-item">
    <strong>MARQUE</strong>
    <?php echo e($car['make']); ?>
  </div>

  <div class="spec-item">
    <strong>MODEL VARIANT</strong>
    <?php echo e($car['model']); ?>
  </div>

  <div class="spec-item">
    <strong>EXTERIOR FINISH</strong>
    <?php echo strtoupper(e($car['color'])); ?>
  </div>

  <div class="spec-item">
    <strong>OWNERSHIP RECORD</strong>
    <?php echo strtoupper(e($car['owner_type'])); ?>
  </div>

  <div class="spec-item">
    <strong>BODY ARCHITECTURE</strong>
    <?php echo strtoupper(e($car['body_type'])); ?>
  </div>

  <div class="spec-item">
    <strong>SEATING OCCUPANCY</strong>
    <?php echo e($car['seating_capacity']); ?> OCCUPANTS
  </div>

  <div class="spec-item" style="grid-column:1 / -1;">
    <strong>REGISTERED LOCATION</strong>
    <?php echo strtoupper(e($car['location'])); ?>
  </div>
</div>

<h3 class="section-title">VEHICLE DOSSIER & CONDITION</h3>

<div class="description">
  <?php echo nl2br(e($car['description'] ?: 'No detailed condition commentary entered for this vehicle.')); ?>
</div>

<div style="margin-top:24px;">
  <a class="btn btn-outline" href="add_review.php?car_id=<?php echo (int)$car['id']; ?>" style="font-size:12px;">
    SUBMIT CLIENT APPRAISAL / REVIEW
  </a>
</div>

</div>
</div>
</div>

<div>
  <!-- SELLER CARD -->
  <div class="info-card">
    <div class="car-content">
      <h3 class="section-title" style="margin-top:0;">VERIFIED SELLER</h3>
      
      <div class="seller-box">
        <div style="display:inline-flex;align-items:center;justify-content:center;width:64px;height:64px;border-radius:50%;background:rgba(229,169,60,0.1);border:1px solid var(--border-gold);margin-bottom:12px;">
          <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2">
            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
            <circle cx="12" cy="7" r="4"></circle>
          </svg>
        </div>

        <h3 style="font-size:20px;"><?php echo e($car['seller_name'] ?? 'Certified Partner'); ?></h3>
        
        <?php if (!empty($car['phone'])): ?>
          <p class="muted" style="font-family:var(--font-heading);font-size:16px;letter-spacing:1px;">
            +91 <?php echo e($car['phone']); ?>
          </p>
          <a class="btn btn-gold btn-chamfered" style="width:100%;margin-top:12px;font-size:13px;" href="tel:<?php echo e($car['phone']); ?>">
            <span>CONNECT VIA PHONE</span>
          </a>
        <?php endif; ?>

        <div style="margin-top:16px;">
          <span class="badge approved">VERIFIED CONSIGNER</span>
        </div>
      </div>
    </div>
  </div>

  <!-- ACTION BUTTONS -->
  <div class="action-buttons">
    <a class="btn btn-gold btn-chamfered" style="padding:16px;font-size:15px;" href="/carconnect/buyer/checkout.php?id=<?php echo (int)$car['id']; ?>">
      <span>INITIATE ACQUISITION</span>
    </a>

    <a class="btn btn-outline" style="padding:14px;font-size:13px;" href="/carconnect/buyer/chat.php?car_id=<?php echo (int)$car['id']; ?>&seller=<?php echo (int)$car['seller_id']; ?>">
      <span>INQUIRE VIA SECURE CHAT</span>
    </a>

    <a class="btn btn-outline" style="padding:12px;font-size:12px;" href="/carconnect/buyer/wishlist.php?add=<?php echo (int)$car['id']; ?>">
      <span>+ ADD TO SAVED COLLECTION</span>
    </a>
  </div>

</div>
</div>
</div>�� Chat
</a>


</div>


</div>


</div>

</div>


<script>

document
.querySelectorAll('.car-thumb')
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
                    'buyerMainCarImage'
                )
                .src = image;

            document
                .querySelectorAll(
                    '.car-thumb'
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