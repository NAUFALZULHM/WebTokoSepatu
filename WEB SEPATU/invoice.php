<?php
session_start();
include('server/connection.php');

if (!isset($_GET['order_id'])) {
    header('Location: index.php');
    exit();
}

$order_id = $_GET['order_id'];
$user_id = $_SESSION['user_id'];

// Ambil detail pesanan dan pembayaran dari database
$stmt = $conn->prepare("SELECT * FROM orders WHERE order_id=? AND user_id=?");
$stmt->bind_param('ii', $order_id, $user_id);
$stmt->execute();
$order_result = $stmt->get_result();
$order = $order_result->fetch_assoc();

$stmt = $conn->prepare("SELECT * FROM payments WHERE order_id=?");
$stmt->bind_param('i', $order_id);
$stmt->execute();
$payment_result = $stmt->get_result();
$payment = $payment_result->fetch_assoc();

if (!$order || !$payment) {
    header('Location: index.php');
    exit();
}
?>

<?php include('layouts/header.php'); ?>

<section class="invoice container my-5 py-5">
    <div class="container mt-5">
        <h2 class="font-weight-bold">Invoice</h2>
        <hr />
        <p>Terima kasih telah melakukan pembayaran. Berikut adalah detail invoice Anda:</p>
        <p>Order ID: <?php echo $order['order_id']; ?></p>
        <p>User ID: <?php echo $user_id; ?></p>
        <p>Transaction ID: <?php echo $payment['transaction_id']; ?></p>
        <p>Total Harga: Rp <?php echo $order['order_total_price']; ?></p>
        <p>Status Pesanan: <?php echo $order['order_status']; ?></p>
        <!-- Tambahkan detail lain yang relevan -->
    </div>
</section>

<?php include('layouts/footer.php'); ?>