<?php
include('server/connection.php');

// Inisialisasi variabel
$products = null;

// Menggunakan section pencarian / search
if (isset($_POST['search'])) {
  // Dapatkan nilai dari form
  $category = isset($_POST['category']) ? $_POST['category'] : null;
  $min_price = isset($_POST['min_price']) ? (int)$_POST['min_price'] : 0;
  $max_price = isset($_POST['max_price']) && $_POST['max_price'] != 0 ? (int)$_POST['max_price'] : PHP_INT_MAX;

  // Persiapkan query berdasarkan apakah ada kategori yang dipilih
  if ($category && $max_price != PHP_INT_MAX) {
    // Jika kategori dan harga dipilih
    $stmt = $conn->prepare("SELECT * FROM products WHERE product_category=? AND product_price BETWEEN ? AND ?");
    $stmt->bind_param("sii", $category, $min_price, $max_price);
  } elseif ($category) {
    // Jika hanya kategori yang dipilih
    $stmt = $conn->prepare("SELECT * FROM products WHERE product_category=?");
    $stmt->bind_param("s", $category);
  } elseif ($max_price != PHP_INT_MAX) {
    // Jika hanya rentang harga yang dipilih
    $stmt = $conn->prepare("SELECT * FROM products WHERE product_price BETWEEN ? AND ?");
    $stmt->bind_param("ii", $min_price, $max_price);
  } else {
    // Jika tidak ada filter, ambil semua produk
    $stmt = $conn->prepare("SELECT * FROM products");
  }

  // Eksekusi query
  $stmt->execute();
  $products = $stmt->get_result();
} else {
  // Jika tidak ada pencarian, ambil semua produk
  $stmt = $conn->prepare("SELECT * FROM products");
  $stmt->execute();
  $products = $stmt->get_result();
}
?>

<?php include('layouts/header.php'); ?>

<!-- Search and Shop -->
<section id="shop" class="my-5 py-5">
  <div class="container mt-5 py-5">
    <h3 style="text-align:center">Produk Kami</h3>
    <hr />
    <div class="row">
      <!-- Search -->
      <div class="col-lg-3 col-md-4 col-sm-12" id="search">
        <h3>Cari Produk</h3>
        <form action="shop.php" method="POST">
          <div class="mb-3">
            <p>Kategori</p>
            <div class="form-check">
              <input class="form-check-input" value="Ortuseight" type="radio" name="category" id="category_one" />
              <label class="form-check-label" for="category_one">Ortuseight</label>
            </div>
            <div class="form-check">
              <input class="form-check-input" value="Adidas" type="radio" name="category" id="category_adidas" />
              <label class="form-check-label" for="category_adidas">Adidas</label>
            </div>
            <div class="form-check">
              <input class="form-check-input" value="Nike" type="radio" name="category" id="category_nike" />
              <label class="form-check-label" for="category_nike">Nike</label>
            </div>
            <div class="form-check">
              <input class="form-check-input" value="Reebok" type="radio" name="category" id="category_reebok" />
              <label class="form-check-label" for="category_reebok">Reebok</label>
            </div>
          </div>
          <div class="mb-3">
            <p>Harga</p>
            <div class="input-group">
              <span class="input-group-text">Min</span>
              <input type="number" class="form-control" name="min_price" value="0" min="0" />
            </div>
            <div class="input-group mt-2">
              <span class="input-group-text">Max</span>
              <input type="number" class="form-control" name="max_price" value="0" min="0" />
            </div>
          </div>
          <div class="form-group my-3 text-center">
            <input style="width: 100%;" type="submit" name="search" value="Cari" class="btn btn-primary" />
          </div>
        </form>
      </div>
      <!-- Shop -->
      <div class="col-lg-9 col-md-8 col-sm-12">
        <div class="row">
          <?php if ($products && $products->num_rows > 0) {
            while ($row = $products->fetch_assoc()) { ?>
              <div onclick="window.location.href='single_produk.php?product_id=<?php echo $row['product_id']; ?>';" class="product text-center col-lg-4 col-md-6 col-sm-12">
                <img class="img-fluid mb-3" src="aset/img/<?php echo $row['product_image']; ?>" />
                <div class="star">
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                </div>
                <h5 class="p-name"><?php echo $row['product_name']; ?></h5>
                <h4 class="p-price">Rp <?php echo $row['product_price']; ?></h4>
                <button class="btn shop-buy-btn">Beli Sekarang</button>
              </div>
          <?php }
          } else {
            echo "<p>Tidak ada produk yang ditemukan.</p>";
          } ?>
        </div>
         
      </div>
    </div>
  </div>
</section>

<?php include('layouts/footer.php'); ?>