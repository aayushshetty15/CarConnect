<?php
require_once __DIR__ . "/../core/middleware.php";
requireRole("seller");

require_once __DIR__ . "/../includes/db_connect.php";
require_once __DIR__ . "/../includes/functions.php";
require_once __DIR__ . "/../includes/header.php";

$sellerId = (int)$_SESSION['user_id'];

/* ===================== */
/* FETCH REVIEWS */
/* ===================== */

$stmt = mysqli_prepare($conn,"
SELECT 
r.rating,
r.comment,
r.created_at,
b.name buyer,
c.make,
c.model
FROM reviews r
JOIN buyers b ON b.id = r.user_id
JOIN car_listings c ON c.id = r.car_id
WHERE c.seller_id=?
ORDER BY r.id DESC
");

mysqli_stmt_bind_param($stmt,"i",$sellerId);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
?>

<div class="dashboard-wrapper">

  <div class="dashboard-header">
    <div class="dashboard-badge">
      <span class="badge-dot-pulse"></span>
      <span>REPUTATION // VERIFIED FEEDBACK</span>
    </div>
    <h1 class="dashboard-title">CLIENT REVIEWS & APPRAISALS</h1>
    <p class="muted">Monitor verified buyer ratings, testimonial commentaries, and seller satisfaction ratings.</p>
  </div>

  <table class="table">
    <thead>
      <tr>
        <th>VEHICLE</th>
        <th>BUYER CLIENT</th>
        <th>APPRAISAL SCORE</th>
        <th>TESTIMONIAL COMMENT</th>
        <th>DATE</th>
      </tr>
    </thead>
    <tbody>
      <?php
      if(mysqli_num_rows($res)>0){
        while($r=mysqli_fetch_assoc($res)){
          $ratingCount = (int)$r['rating'];
          $starsHtml = "";
          for($i=1; $i<=5; $i++){
            $color = ($i <= $ratingCount) ? "var(--primary)" : "rgba(255,255,255,0.15)";
            $starsHtml .= "<span style='color:$color;font-size:16px;'>★</span>";
          }

          $date = !empty($r['created_at']) 
            ? date("d M Y", strtotime($r['created_at'])) 
            : "-";

          echo "
          <tr>
            <td style='font-weight:700;color:#fff;'>".e($r['make'])." ".e($r['model'])."</td>
            <td>".e($r['buyer'])."</td>
            <td>$starsHtml <span style='font-family:var(--font-heading);font-weight:700;color:var(--primary);margin-left:4px;'>($ratingCount/5)</span></td>
            <td style='color:var(--text-secondary);'>".e($r['comment'])."</td>
            <td class='muted'>$date</td>
          </tr>
          ";
        }
      } else {
        echo "<tr><td colspan='5' class='text-center muted' style='padding:40px;'>No client reviews recorded yet.</td></tr>";
      }
      ?>
    </tbody>
  </table>

</div>

<?php require_once __DIR__ . "/../includes/footer.php"; ?>