<?php
require_once __DIR__ . "/../core/middleware.php";
requireRole("seller");

require_once __DIR__ . "/../includes/db_connect.php";
require_once __DIR__ . "/../includes/functions.php";
require_once __DIR__ . "/../includes/header.php";

$sellerId = (int)$_SESSION['user_id'];

/* COMPLETE ORDER */
if(isset($_GET['complete'])){
    $orderId = (int)$_GET['complete'];

    mysqli_begin_transaction($conn);

    mysqli_query($conn,"
    UPDATE orders
    SET order_status='completed'
    WHERE id=$orderId
    AND seller_id=$sellerId
    ");

    mysqli_query($conn,"
    UPDATE car_listings
    SET status='sold'
    WHERE id=(
        SELECT car_id
        FROM orders
        WHERE id=$orderId
    )
    ");

    mysqli_commit($conn);

    header("Location: view_orders.php");
    exit();
}
?>

<div class="dashboard-wrapper">

  <div class="dashboard-header">
    <div class="dashboard-badge">
      <span class="badge-dot-pulse"></span>
      <span>TRANSACTIONS // CLIENT ORDERS</span>
    </div>
    <h1 class="dashboard-title">CLIENT PURCHASE ORDERS</h1>
    <p class="muted">Track incoming vehicle purchase commitments, payment verification records, and contract completion.</p>
  </div>

  <table class="table">
    <thead>
      <tr>
        <th>ORDER ID</th>
        <th>VEHICLE</th>
        <th>BUYER CLIENT</th>
        <th>TRANSACTION VALUE</th>
        <th>ORDER STATUS</th>
        <th>PAYMENT STATUS</th>
        <th>TIMESTAMP</th>
        <th>ACTIONS</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $stmt = mysqli_prepare($conn,"
      SELECT
      o.id,
      o.total_price,
      o.order_status,
      o.created_at,
      o.car_id,
      o.buyer_id,
      c.make,
      c.model,
      b.name buyer,
      p.payment_status
      FROM orders o
      JOIN car_listings c ON c.id=o.car_id
      JOIN buyers b ON b.id=o.buyer_id
      LEFT JOIN payments p ON p.order_id=o.id
      WHERE o.seller_id=?
      ORDER BY o.id DESC
      ");

      mysqli_stmt_bind_param($stmt,"i",$sellerId);
      mysqli_stmt_execute($stmt);
      $res = mysqli_stmt_get_result($stmt);

      if(mysqli_num_rows($res)>0){
        while($r=mysqli_fetch_assoc($res)){
          $status = $r['order_status'];
          $badgeClass = "pending";
          if($status == "completed") $badgeClass = "approved";
          if($status == "cancelled") $badgeClass = "sold";

          $payStatus = $r['payment_status'] ?? 'pending';
          $payClass = "pending";
          if($payStatus == "paid") $payClass = "approved";
          if($payStatus == "failed") $payClass = "sold";

          echo "
          <tr>
            <td style='font-family:var(--font-heading);font-weight:700;color:var(--primary);'>#".intval($r['id'])."</td>
            <td style='font-weight:700;color:#fff;'>".e($r['make'])." ".e($r['model'])."</td>
            <td>".e($r['buyer'])."</td>
            <td style='color:var(--primary);font-weight:800;font-family:var(--font-heading);font-size:18px;'>₹".number_format((float)$r['total_price'])."</td>
            <td><span class='badge $badgeClass'>".strtoupper(e($status))."</span></td>
            <td><span class='badge $payClass'>".strtoupper(e($payStatus))."</span></td>
            <td class='muted'>".date("d M Y", strtotime($r['created_at']))."</td>
            <td>
              <div style='display:flex;gap:8px;align-items:center;'>
                <a class='btn btn-outline' style='padding:6px 12px;font-size:12px;'
                   href='chat.php?buyer=".$r['buyer_id']."&car_id=".$r['car_id']."'>
                  CHAT
                </a>
          ";

          if($r['order_status']=="pending"){
            echo "
                <a class='btn btn-gold' style='padding:6px 12px;font-size:12px;'
                   href='view_orders.php?complete=".$r['id']."'
                   onclick=\"return confirm('Confirm completion and mark vehicle as sold?')\">
                  COMPLETE
                </a>
            ";
          } else {
            echo "<span style='color:#10b981;font-weight:700;font-size:12px;'>VERIFIED</span>";
          }

          echo "
              </div>
            </td>
          </tr>
          ";
        }
      } else {
        echo "<tr><td colspan='8' class='text-center muted' style='padding:40px;'>No orders received yet.</td></tr>";
      }
      ?>
    </tbody>
  </table>

</div>

<?php require_once __DIR__ . "/../includes/footer.php"; ?>