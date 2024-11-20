<?php
session_start();
include('server/connection.php');

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
  header('location: index.php');
  exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['place_order'])) {
  $name = $_POST['name'];
  $email = $_POST['email'];
  $phone = $_POST['phone'];
  $city = $_POST['city'];
  $user_id = $_SESSION['user_id'];
  $order_date = date('Y-m-d H:i:s');
  $order_cost = $_SESSION['total'];

  // Insert order into orders table
  $stmt = $conn->prepare("INSERT INTO orders (order_cost, user_id, user_phone, user_city, order_date) VALUES (?, ?, ?, ?, ?)");
  $stmt->bind_param('disss', $order_cost, $user_id, $phone, $city, $order_date);

  if ($stmt->execute()) {
    $order_id = $stmt->insert_id;

    // Insert order details into order_items table
    foreach ($_SESSION['cart'] as $product) {
      $product_id = $product['product_id'];
      $product_name = $product['product_name'];
      $product_image = $product['product_image'];
      $product_price = $product['product_price'];
      $product_quantity = $product['product_quantity'];

      $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, product_name, product_image, product_price, product_quantity, user_id, order_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
      $stmt->bind_param('iissdiis', $order_id, $product_id, $product_name, $product_image, $product_price, $product_quantity, $user_id, $order_date);
      $stmt->execute();
    }

    // Clear cart session after order is placed
    unset($_SESSION['cart']);

    header('location: payment.php?order_id=' . $order_id);
    exit();
  } else {
    $error_message = 'Gagal memproses pesanan Anda. Silakan coba lagi.';
  }
}
?>

<?php include('layouts/header.php'); ?>

<!-- Checkout -->
<section class="my-5 py-5">
  <div class="container text-center mt-3 pt-5">
    <h2 class="form-weight-bold">Check Out</h2>
    <hr class="mx-auto" />
  </div>
  <div class="mx-auto container">
    <form id="checkout-form" method="post" action="checkout.php">
      <p class="text-center" style="color: red;">
        <?php if (isset($error_message)) echo $error_message; ?>
      </p>
      <div class="form-group checkout-small-element">
        <p class="tengah">Nama</p>
        <input type="text" class="form-control" id="checkout-name" name="name" placeholder="Nama" required />
      </div>
      <div class="form-group checkout-small-element">
        <p class="tengah">Email</p>
        <input type="email" class="form-control" id="checkout-email" name="email" placeholder="Email" required />
      </div>
      <div class="form-group checkout-small-element">
        <p class="tengah">Nomor Telepon</p>
        <input type="tel" class="form-control" id="checkout-phone" name="phone" placeholder="Nomor Telepon" required />
      </div>
      <div class="form-group checkout-small-element">
        <p class="tengah">Alamat</p>
        <input type="text" class="form-control" id="checkout-city" name="city" placeholder="Alamat" required />
      </div>
      <div class="form-group checkout-btn-container">
        <p class="tengah">Jumlah total: Rp <?php echo $_SESSION['total']; ?></p>
        <input type="hidden" name="order_total_price" value="<?php echo $_SESSION['total']; ?>">
        <input type="hidden" name="order_status" value="belum dibayar">
        <input type="submit" class="btn btn-primary" id="checkout-btn" name="place_order" value="Bayar Sekarang" />
      </div>
    </form>
  </div>
</section>

<?php include('layouts/footer.php'); ?>