<?php
session_start();
include('../server/connection.php');

// Memeriksa apakah pengguna telah login sebagai admin
if (!isset($_SESSION['logged_in']) || !isset($_SESSION['user_id']) || $_SESSION['akses'] !== 'admin') {
    header('location: ../login.php');
    exit;
}

// Ambil data produk dari database
$stmt = $conn->prepare("SELECT * FROM products");
$stmt->execute();
$products = $stmt->get_result();
?>

<?php include('header_admin.php'); ?>

<!-- Admin Panel -->
<section class="my-5 py-5">
    <div class="container">
        <h2 class="text-center mb-2 ">Admin Panel</h2>
        <hr style="width: 20%;">
        <div class="row">
            <div class="col-md-12">
                <div class="text-right mb-3">
                    <a href="add_product.php" class="btn btn-primary">Tambah Produk Baru</a>
                </div>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Produk</th>
                            <th>Kategori</th>
                            <th>Harga</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($products->num_rows > 0) {
                            $count = 1;
                            while ($row = $products->fetch_assoc()) { ?>
                                <tr>
                                    <td><?php echo $count; ?></td>
                                    <td><?php echo $row['product_name']; ?></td>
                                    <td><?php echo $row['product_category']; ?></td>
                                    <td>Rp <?php echo $row['product_price']; ?></td>
                                    <td>
                                        <a href="edit_product.php?product_id=<?php echo $row['product_id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                                        <button class="btn btn-sm btn-danger" onclick="confirmDelete(<?php echo $row['product_id']; ?>)">Hapus</button>
                                    </td>
                                </tr>
                            <?php $count++;
                            }
                        } else { ?>
                            <tr>
                                <td colspan="5">Tidak ada produk yang tersedia.</td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<script>
    function confirmDelete(productId) {
        if (confirm("Apakah Anda yakin ingin menghapus produk ini?")) {
            window.location.href = "delete_product.php?product_id=" + productId;
        }
    }
</script>

<?php include('footer_admin.php'); ?>