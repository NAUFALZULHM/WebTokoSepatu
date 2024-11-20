<?php
session_start();

include('server/connection.php');

//jika user sudah registrasi, maka user masuk ke halaman akun
if (isset($_SESSION['logged_in'])) {
  header('location: account.php');
  exit;
}

$name = "";
$email = "";
$error = "";

if (isset($_POST['register'])) {

  $name = $_POST['name'];
  $email = $_POST['email'];
  $password = $_POST['password'];
  $confirmPassword = $_POST['confirm-password'];

  // Jika password tidak cocok
  if ($password !== $confirmPassword) {
    $error = "Password tidak cocok";
  }

  // Jika password kurang dari 6 karakter
  else if (strlen($password) < 6) {
    $error = "Password minimal berisi 6 karakter";
  }

  // Validasi format email
  else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error = "Email tidak valid";
  }

  // Jika tidak ada error
  else {

    // Periksa apakah ada user dengan email ini atau tidak
    $stmt1 = $conn->prepare("SELECT count(*) FROM users WHERE user_email=?");
    $stmt1->bind_param('s', $email);
    $stmt1->execute();
    $stmt1->bind_result($num_rows);
    $stmt1->store_result();
    $stmt1->fetch();

    // Jika ada pengguna yang sudah terdaftar dengan email ini
    if ($num_rows != 0) {
      $error = "User with this email already exists";
    } else {
      // Buat user baru
      $stmt = $conn->prepare("INSERT INTO users (user_name, user_email, user_password) VALUES (?, ?, ?)");
      $stmt->bind_param('sss', $name, $email, md5($password));

      if ($stmt->execute()) {
        $user_id = $stmt->insert_id;
        $_SESSION['user_id'] = $user_id;
        $_SESSION['user_email'] = $email;
        $_SESSION['user_name'] = $name;
        $_SESSION['logged_in'] = true;
        header('location: account.php?register_success=Registrasi telah berhasil');
        exit();
      } else {
        $error = "Tidak dapat membuat akun saat ini";
      }
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Home</title>

  <!-- copy dari bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous" />

  <!-- copy dari font awesome cdn -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.2/css/fontawesome.min.css" integrity="sha384-BY+fdrpOd3gfeRvTSMT+VUZmA728cfF9Z2G42xpaRkUGu2i3DyzpTURDo5A6CaLK" crossorigin="anonymous" />

  <link rel="stylesheet" href="aset/css/style.css" />
</head>

<body>
  <!-- set up kit dari font awesome -->
  <script src="https://kit.fontawesome.com/1e4871b53e.js" crossorigin="anonymous"></script>

  <!--Navbar-->
  <nav class="navbar navbar-expand-lg navbar-light bg-white py-3 fixed-top">
    <div class="container">
      <img class="logo" src="aset/img/Logo.jpg" alt="" />
      <h2 class="brand">Paceshop</h2>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

    </div>
  </nav>

  <!-- Register -->
  <section class="my-5 py-5">
    <div class="container text-center mt-3 pt-5">
      <h2 class="form-weight-bold">Register</h2>
      <hr class="mx-auto" />
    </div>
    <div class="mx-auto container">
      <form id="register-form" method="POST" action="register.php">
        <p style="color: red"><?php if ($error) {
                                echo $error;
                              } ?></p>
        <div class="form-group">
          <label>Username</label>
          <input type="text" class="form-control" id="register-username" name="name" placeholder="Username" value="<?php echo htmlspecialchars($name); ?>" required />
        </div>
        <div class="form-group">
          <label>Email</label>
          <input type="email" class="form-control" id="register-email" name="email" placeholder="Email" value="<?php echo htmlspecialchars($email); ?>" required />
        </div>
        <div class="form-group">
          <label>Password</label>
          <input type="password" class="form-control" id="register-password" name="password" placeholder="Password" required />
        </div>
        <div class="form-group">
          <label>Konfirmasi Password</label>
          <input type="password" class="form-control" id="register-confirm-password" name="confirm-password" placeholder="Konfirmasi Password" required />
        </div>
        <div class="form-group">
          <input type="submit" class="btn" id="register-btn" name="register" value="Register" />
        </div>
        <div class="form-group">
          <a id="login-url" href="login.php" class="btn">Sudah punya akun? Login</a>
        </div>
      </form>
    </div>
  </section>

  <!-- Footer -->
  <footer class="mt-5 py-5">
    <div class="row container mx-auto pt-5">
      <div class="footer-one col-lg-3 col-md-6 col-sm-12">
        <img class="logo" src="aset/img/logo.jpg" />
        <p class="pt-3">
          Menyediakan sepatu lari terbaik dengan kualitas premium untuk menunjang performa olahraga Anda.
        </p>
      </div>

      <div class="footer-one col-lg-3 col-md-6 col-sm-12">
        <h5 class="pb-2">Kategori Utama</h5>
        <ul class="text-uppercase">
          <li><a href="#">Pria</a></li>
          <li><a href="#">Wanita</a></li>
          <li><a href="#">Anak Laki-laki</a></li>
          <li><a href="#">Anak Perempuan</a></li>
          <li><a href="#">Pemula</a></li>
          <li><a href="#">Promo</a></li>
        </ul>
      </div>

      <div class="footer-one col-lg-3 col-md-6 col-sm-12">
        <h5 class="pb-2">Hubungi Kami</h5>
        <div>
          <h6 class="text-uppercase">Alamat</h6>
          <p>Jl. Contoh Alamat No. 123, Kota</p>
        </div>
        <div>
          <h6 class="text-uppercase">Telepon</h6>
          <p>0858-4353-8241</p>
        </div>
        <div>
          <h6 class="text-uppercase">Email</h6>
          <p>paceshop@pace.co.id</p>
        </div>
      </div>
      <div class="footer-one col-lg-3 col-md-6 col-sm-12">
        <h5 class="pb-2">Instagram</h5>
        <div class="row">
          <img src="aset/img/nike1.jpg" class="img-fluid w-25 h-100 m-2" />
          <img src="aset/img/nike3.jpg" class="img-fluid w-25 h-100 m-2" />
          <img src="aset/img/ortus1.jpg" class="img-fluid w-25 h-100 m-2" />
          <img src="aset/img/reebok1.jpg" class="img-fluid w-25 h-100 m-2" />
          <img src="aset/img/ortus4.jpg" class="img-fluid w-25 h-100 m-2" />
        </div>
      </div>
    </div>

    <div class="copyright mt-5">
      <div class="row container mx-auto">
        <div class="col-lg-3 col-md-5 col-sm-12 mb-4">
          <img src="aset/img/dana.jpg" />
        </div>
        <div class="col-lg-3 col-md-5 col-sm-12 mb-4 text-nowrap mb-2">
          <p>&copy; 2024 PaceShop. All Rights Reserved.</p>
        </div>
        <div class="col-lg-3 col-md-5 col-sm-12 mb-4">
          <a href="#"><i class="fab fa-facebook"></i></a>
          <a target="_blank" href="https://www.instagram.com/n_z_h_m?igsh=MW02ZDBkeHp0YWp3MQ=="><i class="fab fa-instagram"></i></a>
          <a href="#"><i class="fab fa-twitter"></i></a>
        </div>
      </div>
    </div>
  </footer>

  <!-- copy dari bootstrap -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>

</html>