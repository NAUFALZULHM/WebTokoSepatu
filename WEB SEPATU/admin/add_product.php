<?php
session_start();
include('../server/connection.php');

// Memeriksa apakah pengguna telah login sebagai admin
if (!isset($_SESSION['logged_in']) || !isset($_SESSION['user_id']) || $_SESSION['akses'] !== 'admin') {
    header('location: ../login.php');
    exit;
}

// Inisialisasi variabel untuk nilai default
$product_name = '';
$product_category = '';
$product_description = '';
$product_price = '';
$product_color = '';

// Memeriksa jika form telah disubmit
if (isset($_POST['add_product'])) {
    // Ambil nilai dari form
    $product_name = $_POST['product_name'];
    $product_category = $_POST['product_category'];
    $product_description = $_POST['product_description'];
    $product_price = $_POST['product_price'];
    $product_color = $_POST['product_color'];

    // Mengunggah gambar produk
    $product_image = $_FILES['product_image']['name'];


    // Lokasi target untuk gambar yang diunggah
    $target_dir = "../aset/img/";
    $target_file1 = $target_dir . basename($product_image);


    // Memindahkan gambar ke lokasi target
    if (move_uploaded_file($_FILES['product_image']['tmp_name'], $target_file1)) {

        // Query untuk menyimpan produk ke database
        $stmt = $conn->prepare("INSERT INTO products (product_name, product_category, product_description, product_image, product_image2, product_image3, product_image4, product_price, product_color) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssss", $product_name, $product_category, $product_description, $product_image, $product_image2, $product_image3, $product_image4, $product_price, $product_color);

        if ($stmt->execute()) {
            header('location: admin.php');
            exit;
        } else {
            $error_msg = "Gagal menambah produk. Silakan coba lagi.";
        }
    } else {
        $error_msg = "Gagal mengunggah gambar. Silakan coba lagi.";
    }
}
?>

<?php include('header_admin.php'); ?>

<!-- Form Tambah Produk -->
<section class="my-5 py-5">
    <div class="container">
        <h2 class="text-center mb-4">Tambah Produk Baru</h2>
        <?php if (isset($error_msg)) { ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $error_msg; ?>
            </div>
        <?php } ?>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <form method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="product_name">Nama Produk</label>
                        <input type="text" name="product_name" id="product_name" class="form-control" value="<?php echo $product_name; ?>" required>
                    </div>
                    <div class="mb-3 form-group">
                        <label for="product_category">Kategori</label>
                        <select class="form-control" id="product_category" name="product_category" required>
                            <option value="" selected disabled>Pilih Kategori</option>
                            <option value="Ortuseight">Ortuseight</option>
                            <option value="Adidas">Adidas</option>
                            <option value="Nike">Nike</option>
                            <option value="Reebok">Reebok</option>
                        </select>
                    </div>


                    <div class="mb-3">
                        <label for="product_description">Deskripsi</label>
                        <textarea name="product_description" id="product_description" class="form-control" required><?php echo $product_description; ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="product_price">Harga</label>
                        <input type="number" name="product_price" id="product_price" class="form-control" value="<?php echo $product_price; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="product_color">Warna</label>
                        <input type="text" name="product_color" id="product_color" class="form-control" value="<?php echo $product_color; ?>">
                    </div>
                    <div class="mb-3">
                        <label for="product_image">Gambar Produk</label>
                        <input type="file" name="product_image" id="product_image" class="form-control" required>
                    </div>

                    <div class="mb-3 text-center">
                        <input type="submit" name="add_product" class="btn btn-primary" value="Tambah Produk">
                        <a href="admin.php" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<?php include('footer_admin.php'); ?>