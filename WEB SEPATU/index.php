<?php include('layouts/header.php'); ?>

//home
<section id="home">
  <div class="container">
    <h5>Kenyamanan dan Kualitas Terbaik</h5>
    <h1><span>Promo Terbaik</span> untuk Sepatu Lari Anda</h1>
    <p>
      Temukan berbagai pilihan sepatu lari dengan desain modern dan teknologi terbaru untuk menunjang performa lari Anda.
    </p>
    <a href="shop.php"><button>Beli Sekarang</button></a>
  </div>
</section>

<!-- Brand -->
<section id="brand" class="container">
  <div class="row">
    <img class="img-fluid col-lg-3 col-md-6 col-sm-12" src="aset/img/ortus.jpg" />
    <img class="img-fluid col-lg-3 col-md-6 col-sm-12" src="aset/img/adidas.jpg" />
    <img class="img-fluid col-lg-3 col-md-6 col-sm-12" src="aset/img/nike.jpg" />
    <img class="img-fluid col-lg-3 col-md-6 col-sm-12" src="aset/img/reebok.jpg" />
  </div>
</section>


<!-- Ortus -->
<section id="featured" class="my-5 pb-5">
  <div class="container text-center mt-5 py-5">
    <h3>Ortuseight</h3>
    <hr />
    <p>Kamu dapat melihat produk unggulan kami</p>
  </div>
  <div class="row mx-auto container-fluid">

    <?php include('server/get_ortuseight_products.php'); ?>
    <?php while ($row = $ortuseight_product->fetch_assoc()) { ?>
      <!-- One -->
      <div class="product text-center col-lg-3 col-md-4 col-sm-12">
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
        <a href="<?php echo "single_produk.php?product_id=" . $row['product_id']; ?>"><button class="buy-btn">Beli Sekarang</button></a>
      </div>
    <?php } ?>
  </div>
</section>

<!-- Banner
  <section id="banner" class="my-5 py-5">
    <div class="container">
      <h4>Lorem, ipsum dolor.</h4>
      <h1>
        Lorem, ipsum. <br />
        Diskon Mulai dari 30%
      </h1>
      <button class="text-uppercase">Belanja Sekarang</button>
    </div>
  </section> -->

<!-- Adidas -->
<section id="clothes" class="my-5 pb-5">
  <div class="container text-center mt-5 py-5">
    <h3>Adidas</h3>
    <hr />
    <p>Kamu dapat melihat produk unggulan kami</p>
  </div>
  <div class="row mx-auto container-fluid">

    <?php include('server/get_adidas_products.php'); ?>
    <?php while ($row = $adidas_product->fetch_assoc()) { ?>
      <!-- One -->
      <div class="product text-center col-lg-3 col-md-4 col-sm-12">
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
        <a href="<?php echo "single_produk.php?product_id=" . $row['product_id']; ?>"><button class="buy-btn">Beli Sekarang</button></a>
      </div>
    <?php } ?>
  </div>
</section>

<!-- Nike -->
<section id="shoes" class="my-5">
  <div class="container text-center mt-5 py-5">
    <h3>Nike</h3>
    <hr />
    <p>Kamu dapat melihat produk unggulan kami</p>
  </div>

  <div class="row mx-auto container-fluid">
    <?php include('server/get_nike_products.php'); ?>
    <?php while ($row = $nike_product->fetch_assoc()) { ?>

      <!-- One -->
      <div class="product text-center col-lg-3 col-md-4 col-sm-12">
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
        <a href="<?php echo "single_produk.php?product_id=" . $row['product_id']; ?>"><button class="buy-btn">Beli Sekarang</button></a>
      </div>
    <?php } ?>
  </div>
</section>

<!-- Reebok -->
<section id="watches" class="my-5">
  <div class="container text-center mt-5 py-5">
    <h3>Reebok</h3>
    <hr />
    <p>Kamu dapat melihat produk unggulan kami</p>
  </div>
  <div class="row mx-auto container-fluid">
    <?php include('server/get_reebok_products.php'); ?>
    <?php while ($row = $reebok_product->fetch_assoc()) { ?>
      <!-- One -->
      <div class="product text-center col-lg-3 col-md-4 col-sm-12">
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
        <a href="<?php echo "single_produk.php?product_id=" . $row['product_id']; ?>"><button class="buy-btn">Beli Sekarang</button></a>
      </div>
    <?php } ?>
  </div>
</section>

<?php include('layouts/footer.php'); ?>