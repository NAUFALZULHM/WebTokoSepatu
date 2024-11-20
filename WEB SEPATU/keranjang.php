<?php
session_start();

// Inisialisasi session 'total' jika belum ada
if (!isset($_SESSION['total'])) {
  $_SESSION['total'] = 0;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

  // Menangani penambahan produk ke keranjang
  if (isset($_POST['add_to_cart'])) {
    // Jika user sudah menambahkan produk ke keranjang
    if (isset($_SESSION['cart'])) {
      $products_array_ids = array_column($_SESSION['cart'], "product_id");

      // Jika produk sudah ditambahkan ke keranjang atau tidak
      if (!in_array($_POST['product_id'], $products_array_ids)) {
        $product_array = array(
          'product_id' => $_POST['product_id'],
          'product_name' => $_POST['product_name'],
          'product_price' => $_POST['product_price'],
          'product_image' => $_POST['product_image'],
          'product_quantity' => $_POST['product_quantity'],
        );

        $_SESSION['cart'][$_POST['product_id']] = $product_array;
      } else {
        echo '<script>alert("Produk sudah ditambahkan ke keranjang");</script>';
      }
    } else {
      $product_id = $_POST['product_id'];
      $product_name = $_POST['product_name'];
      $product_price = $_POST['product_price'];
      $product_image = $_POST['product_image'];
      $product_quantity = $_POST['product_quantity'];

      $product_array = array(
        'product_id' => $product_id,
        'product_name' => $product_name,
        'product_price' => $product_price,
        'product_image' => $product_image,
        'product_quantity' => $product_quantity,
      );

      $_SESSION['cart'][$product_id] = $product_array;
    }
  }

  // Menangani penghapusan produk dari keranjang
  if (isset($_POST['remove_product'])) {
    $product_id = $_POST['product_id'];
    unset($_SESSION['cart'][$product_id]);
  }

  // Menangani pembaruan jumlah produk di keranjang
  if (isset($_POST['edit_quantity'])) {
    $product_id = $_POST['product_id'];
    $product_quantity = $_POST['product_quantity'];
    if ($product_quantity > 0) {
      $_SESSION['cart'][$product_id]['product_quantity'] = $product_quantity;
    } else {
      unset($_SESSION['cart'][$product_id]);
    }
  }

  // Menangani permintaan AJAX untuk memperbarui jumlah produk
  if (isset($_POST['ajax']) && $_POST['ajax'] == '1') {
    $product_id = $_POST['product_id'];
    $product_quantity = $_POST['product_quantity'];

    if ($product_quantity > 0) {
      $_SESSION['cart'][$product_id]['product_quantity'] = $product_quantity;
    } else {
      unset($_SESSION['cart'][$product_id]);
    }

    // Kalkulasi ulang total
    calculateTotalCart();
    echo json_encode(['total' => $_SESSION['total']]);
    exit();
  }

  //kalkulasi total
  calculateTotalCart();
} else {
  // header('location: index.php');
  // exit();
}

function calculateTotalCart()
{
  $total = 0;

  if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $key => $value) {
      $product = $_SESSION['cart'][$key];
      $price = $product['product_price'];
      $quantity = $product['product_quantity'];

      $total += ($price * $quantity);
    }
  }

  $_SESSION['total'] = $total;
}
?>

<?php include('layouts/header.php'); ?>

<section class="cart container my-5 py-5">
  <div class="container mt-5">
    <h2 class="font-weight-bold">Keranjangmu</h2>

    <hr class="hrkhusus" />

  </div>

  <table class="mt-5 pt-5">
    <tr>
      <th>Produk</th>
      <th>Jumlah</th>
      <th>Subtotal</th>
    </tr>

    <?php
    // Memastikan array 'cart' diinisialisasi sebelum diakses
    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
      foreach ($_SESSION['cart'] as $key => $value) { ?>
        <tr>
          <td>
            <div class="product-info">
              <img src="aset/img/<?php echo $value['product_image']; ?>" />
              <div>
                <p><?php echo $value['product_name']; ?></p>
                <small><span>Rp </span><?php echo $value['product_price']; ?></small>
                <br />
                <form method="POST" action="keranjang.php">
                  <input type="hidden" name="product_id" value="<?php echo $value['product_id']; ?>" />
                  <input type="submit" name="remove_product" class="remove-btn" value="remove" />
                </form>
              </div>
            </div>
          </td>

          <td>
            <input type="hidden" name="product_id" value="<?php echo $value['product_id']; ?>" />
            <input type="number" name="product_quantity" value="<?php echo $value['product_quantity']; ?>" min="1" class="quantity-input" />
          </td>

          <td>
            <span>Rp </span>
            <span class="product-price" data-price-per-item="<?php echo $value['product_price']; ?>"><?php echo $value['product_price'] * $value['product_quantity']; ?></span>
          </td>
        </tr>
    <?php }
    } else {
      echo '<tr><td colspan="3">Keranjang Anda kosong.</td></tr>';
    }
    ?>
  </table>

  <div class="cart-total">
    <table>
      <!-- <tr>
        <td>Subtotal</td>
        <td class="total-price">Rp <?php echo isset($_SESSION['total']) ? $_SESSION['total'] : '0'; ?></td>
      </tr> -->
      <tr>
        <td>Total</td>
        <td class="total-price">Rp <?php echo isset($_SESSION['total']) ? $_SESSION['total'] : '0'; ?></td>
      </tr>
    </table>
  </div>

  <div class="checkout-container">
    <form method="post" action="checkout.php">
      <input type="submit" class="btn checkout-btn" value="Checkout" name="checkout" />
    </form>
  </div>

</section>
<!-- Hubungkan file JavaScript -->
<script src="aset/js/cart.js"></script>
<?php include('layouts/footer.php'); ?>