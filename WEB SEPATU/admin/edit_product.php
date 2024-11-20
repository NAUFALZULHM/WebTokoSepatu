<?php
session_start();
include('../server/connection.php');

// Memeriksa apakah pengguna telah login sebagai admin
if (!isset($_SESSION['logged_in']) || !isset($_SESSION['user_id']) || $_SESSION['akses'] !== 'admin') {
    header('location: ../login.php');
    exit;
}

// Memeriksa apakah parameter product_id ada di URL
if (!isset($_GET['product_id'])) {
    header('location: admin.php');
    exit;
}

$product_id = $_GET['product_id'];

// Mengambil informasi produk dari database berdasarkan product_id
$stmt = $conn->prepare("SELECT * FROM products WHERE product_id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    header('location: admin.php');
    exit;
}

$product = $result->fetch_assoc();

// Memeriksa jika form telah disubmit untuk update produk
if (isset($_POST['update_product'])) {
    // Ambil nilai dari form
    $product_name = $_POST['product_name'];
    $product_category = $_POST['product_category'];
    $product_description = $_POST['product_description'];
    $product_price = $_POST['product_price'];
    $product_special_offer = $_POST['product_special_offer'];
    $product_color = $_POST['product_color'];

    // Query untuk update produk di database
    $stmt = $conn->prepare("UPDATE products SET product_name=?, product_category=?, product_description=?, product_price=?, product_special_offer=?, product_color=? WHERE product_id=?");
    $stmt->bind_param("ssssssi", $product_name, $product_category, $product_description, $product_price, $product_special_offer, $product_color, $product_id);

    if ($stmt->execute()) {
        header('location: admin.php');
        exit;
    } else {
        $error_msg = "Gagal memperbarui produk. Silakan coba lagi.";
    }
}
?>

<?php include('header_admin.php'); ?>

<!-- Form Edit Produk -->
<section class="my-5 py-5">
    <div class="container">
        <h2 class="text-center mb-4">Edit Produk</h2>
        <?php if (isset($error_msg)) { ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $error_msg; ?>
            </div>
        <?php } ?>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <form method="POST">
                    <div class="mb-3">
                        <label for="product_name">Nama Produk</label>
                        <input type="text" name="product_name" id="product_name" class="form-control" value="<?php echo $product['product_name']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="product_category">Kategori</label>
                        <input type="text" name="product_category" id="product_category" class="form-control" value="<?php echo $product['product_category']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="product_description">Deskripsi</label>
                        <textarea name="product_description" id="product_description" class="form-control" required><?php echo $product['product_description']; ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="product_price">Harga</label>
                        <input type="number" name="product_price" id="product_price" class="form-control" value="<?php echo $product['product_price']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="product_special_offer">Penawaran Khusus</label>
                        <input type="text" name="product_special_offer" id="product_special_offer" class="form-control" value="<?php echo $product['product_special_offer']; ?>">
                    </div>
                    <div class="mb-3">
                        <label for="product_color">Warna</label>
                        <input type="text" name="product_color" id="product_color" class="form-control" value="<?php echo $product['product_color']; ?>">
                    </div>
                    <div class="mb-3 text-center">
                        <input type="submit" name="update_product" class="btn btn-primary" value="Update Produk">
                        <a href="admin.php" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<?php include('footer_admin.php'); ?>