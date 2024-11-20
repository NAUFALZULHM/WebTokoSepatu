<?php
session_start();
include('server/connection.php');

if (!isset($_SESSION['logged_in'])) {
    header('location: login.php');
    exit;
}

if (!isset($_GET['order_id'])) {
    header('location: account.php');
    exit;
}

$order_id = $_GET['order_id'];
$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT * FROM orders WHERE order_id = ? AND user_id = ?");
$stmt->bind_param('ii', $order_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header('location: account.php');
    exit;
}

$order = $result->fetch_assoc();

// Ambil detail produk dari order_items
$stmt = $conn->prepare("SELECT * FROM order_items WHERE order_id = ?");
$stmt->bind_param('i', $order_id);
$stmt->execute();
$items_result = $stmt->get_result();

?>

<?php include('layouts/header.php'); ?>
<!-- Detail Pesanan -->
<section class="my-5 py-5">
    <div class="container text-center mt-3 pt-5">
        <h2 class="font-weight-bold">Detail Pesanan</h2>
        <hr class="mx-auto" />
    </div>

    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Informasi Pesanan</h5>
                        <p><strong>Tanggal Pesanan:</strong> <?php echo $order['order_date']; ?></p>
                        <p><strong>Total Harga:</strong> Rp <?php echo $order['order_cost']; ?></p>
                        <p><strong>Status:</strong> <?php echo $order['order_status']; ?></p>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-body">
                        <h5 class="card-title">Detail Produk</h5>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Produk</th>
                                        <th>Gambar</th>
                                        <th>Harga</th>
                                        <th>Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    while ($item = $items_result->fetch_assoc()) {
                                        echo "<tr>";
                                        echo "<td>" . $no++ . "</td>";
                                        echo "<td>" . $item['product_name'] . "</td>";
                                        echo "<td><img src='aset/img/" . $item['product_image'] . "' alt='" . $item['product_name'] . "' class='img-thumbnail' style='width: 100px; height: auto;'></td>";

                                        echo "<td>Rp " . $item['product_price'] . "</td>";
                                        echo "<td>" . $item['product_quantity'] . "</td>";
                                        echo "</tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                

            </div>
        </div>

    </div>
    <div style="display: flex; justify-content: center; margin-top:2%;">
                    <a href="account.php">
                        <button class="buy-btn">Kembali</button>
                    </a>
                </div>
</section>
<?php include('layouts/footer.php'); ?>