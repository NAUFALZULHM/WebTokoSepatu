<?php
session_start();
include('../server/connection.php');

if (!isset($_SESSION['logged_in']) || $_SESSION['akses'] !== 'admin') {
    header('location: ../login.php');
    exit;
}

if (isset($_GET['logout'])) {
    session_destroy();
    header('location: ../login.php');
    exit;
}

if (isset($_POST['change_password'])) {
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];
    $user_email = $_SESSION['user_email'];

    // Jika password tidak cocok
    if ($password !== $confirmPassword) {
        header('location: account_admin.php?error=password tidak cocok');
        exit();
    }

    // Jika password kurang dari 6 karakter
    if (strlen($password) < 6) {
        header('location: account_admin.php?error=password minimal berisi 6 karakter');
        exit();
    } else {
        // Menggunakan md5 untuk meng-hash password
        $hashed_password = md5($password);

        $stmt = $conn->prepare("UPDATE users SET user_password=? WHERE user_email=?");
        $stmt->bind_param('ss', $hashed_password, $user_email);

        if ($stmt->execute()) {
            header('location: account_admin.php?message=Update password berhasil');
        } else {
            header('location: account_admin.php?error=Update password gagal');
        }
    }
}

?>

<?php include('header_admin.php'); ?>
<!-- Akun Admin -->
<section class="my-5 py-5">
    <div class="row container mx-auto">
        <div class="text-center mt-3 pt-5 col-lg col-md-12 col-sm-12">
            <h3 class="font-weight-bold">Informasi Akun</h3>
            <hr class="mx-auto" />
            <div class="account-info">
                <p>Nama : <span><?php if (isset($_SESSION['user_name'])) {
                                    echo $_SESSION['user_name'];
                                } ?></span></p>
                <p>Email : <span><?php if (isset($_SESSION['user_email'])) {
                                    echo $_SESSION['user_email'];
                                } ?></span></p>
                <p><a href="account_admin.php?logout=1" id="logout-btn">Logout</a></p>
            </div>
        
    </div>
</section>

<?php include('footer_admin.php'); ?>