<?php
session_start();
include('server/connection.php');

if (!isset($_SESSION['logged_in'])) {
  header('location: login.php');
  exit;
}

if (isset($_GET['logout'])) {
  if (isset($_SESSION['logged_in'])) {
    unset($_SESSION['logged_in']);
    unset($_SESSION['user_email']);
    unset($_SESSION['user_name']);
    header('location: login.php');
    exit;
  }
}

if (isset($_POST['change_password'])) {
  $password = $_POST['password'];
  $confirmPassword = $_POST['confirmPassword'];
  $user_email = $_SESSION['user_email'];

  // Jika password tidak cocok
  if ($password !== $confirmPassword) {
    header('location: account.php?error=password tidak cocok');
    exit();
  }

  // Jika password kurang dari 6 karakter
  if (strlen($password) < 6) {
    header('location: account.php?error=password minimal berisi 6 karakter');
    exit();
  } else {
    // Menggunakan md5 untuk meng-hash password
    $hashed_password = md5($password);

    $stmt = $conn->prepare("UPDATE users SET user_password=? WHERE user_email=?");
    $stmt->bind_param('ss', $hashed_password, $user_email);

    if ($stmt->execute()) {
      header('location: account.php?message=Update password berhasil');
    } else {
      header('location: account.php?error=Update password gagal');
    }
  }
}

?>

<?php include('layouts/header.php'); ?>
<!-- Akun -->
<section class="my-5 py-5">
  <div class="row container mx-auto">
    <div class="text-center mt-3 pt-5 col-lg col-md-12 col-sm-12">
      <p class="text-center" style="color:green"><?php if (isset($_GET['register_success'])) {
                                                    echo $_GET['register_success'];
                                                  } ?></p>
      <p class="text-center" style="color:green"><?php if (isset($_GET['login_success'])) {
                                                    echo $_GET['login_success'];
                                                  } ?></p>
      <h3 class="font-weight-bold">Informasi Akun</h3>
      <hr class="mx-auto" />
      <div class="account-info">
        <p>Nama : <span><?php if (isset($_SESSION['user_name'])) {
                          echo $_SESSION['user_name'];
                        } ?></span></p>
        <p>Email : <span><?php if (isset($_SESSION['user_email'])) {
                            echo $_SESSION['user_email'];
                          } ?></span></p>

        <p><a href="account.php?logout=1" id="logout-btn">Logout</a></p>
      </div>
    </div>
    <div class="col-lg-6 col-md-12 col-sm-12">
      <form id="account-form" method="POST" action="account.php">
        <p class="text-center" style="color:red"><?php if (isset($_GET['error'])) {
                                                    echo $_GET['error'];
                                                  } ?></p>
        <p class="text-center" style="color:green"><?php if (isset($_GET['message'])) {
                                                      echo $_GET['message'];
                                                    } ?></p>
        <h3>Ubah Password</h3>
        <hr class="mx-auto" />
        <div class="form-group">
          <label>Password</label>
          <input type="password" class="form-control" id="account-password" name="password" placeholder="Password" required />
        </div>
        <div class="form-group">
          <label>Konfirmasi Password</label>
          <input type="password" class="form-control" id="account-password-confirm" name="confirmPassword" placeholder="Konfirmasi Password" required />
        </div>
        <div class="form-group">
          <input type="submit" value="Ubah Password" name="change_password" class="btn" id="change-pass-btn" />
        </div>
      </form>
    </div>
  </div>

  <!-- Invoice section -->
  <div class="row container mx-auto mt-5">
    <div class="text-center mt-3 pt-5 col-lg col-md-12 col-sm-12">
      <div class="invoice-section">
        <h4>Invoice History</h4>
        <table class="table">
          <thead>
            <tr>
              <th>No</th>
              <th>Tanggal Pesanan</th>
              <th>Total Harga</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $user_id = $_SESSION['user_id'];
            $stmt = $conn->prepare("SELECT * FROM orders WHERE user_id = ?");
            $stmt->bind_param('i', $user_id);
            $stmt->execute();
            $result = $stmt->get_result();

            $no = 1;
            while ($row = $result->fetch_assoc()) {
              echo "<tr>";
              echo "<td>" . $no++ . "</td>";
              echo "<td>" . $row['order_date'] . "</td>";
              echo "<td>Rp " . $row['order_cost'] . "</td>";
              echo "<td>" . $row['order_status'] . "</td>";
              echo "<td><a href='order_details.php?order_id=" . $row['order_id'] . "' class='btn btn-info btn-sm' style='background-color: coral; color:white;'>Detail</a></td>";
              echo "</tr>";
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <!-- End of invoice section -->
</section>

<?php include('layouts/footer.php'); ?>