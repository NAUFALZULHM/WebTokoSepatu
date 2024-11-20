<?php
session_start();
include('server/connection.php');

// Mendapatkan ID pesanan dari URL
$order_id = isset($_GET['order_id']) ? $_GET['order_id'] : null;

if ($order_id) {
  // Mendapatkan informasi pesanan dari database
  $stmt = $conn->prepare("SELECT * FROM orders WHERE order_id = ?");
  $stmt->bind_param('i', $order_id);
  $stmt->execute();
  $order = $stmt->get_result()->fetch_assoc();

  // Menampilkan informasi pesanan
  if ($order) {
    $order_cost = $order['order_cost'];
    $order_date = $order['order_date'];
    $order_status = $order['order_status'];
  }
} else {
  header('location: index.php');
  exit();
}
?>

<?php include('layouts/header.php'); ?>

<!-- Payment -->
<section class="my-5 py-5">
  <div class="container text-center mt-3 pt-5">
    <h2 class="form-weight-bold">Payment</h2>
    <div>
      <hr class="mx-auto" />
    </div>

  </div>
  <div class="mx-auto container text-center">

    <?php if (isset($_SESSION['error'])) { ?>
      <p style="color: red;"><?php echo $_SESSION['error'];
                              unset($_SESSION['error']); ?></p>
    <?php } ?>

    <p>Total pembayaran: Rp <?php echo $order_cost; ?></p>
    <form method="POST" action="process_payment.php">
      <input type="hidden" name="order_total_price" value="<?php echo $order_cost; ?>">
      <input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
      <div class="form-group">
        <label for="amount">Masukkan jumlah yang dibayar:</label>
        <input type="number" class="form-control custom-amount" id="amount" name="amount" required autofocus>
      </div>
      <input class="btn btn-primary" type="submit" value="Bayar Sekarang">
    </form>
  </div>
</section>

<?php include('layouts/footer.php'); ?>