<?php
session_start();

// Hapus session 'cart' setelah pembayaran selesai
unset($_SESSION['cart']);

// Tampilkan halaman sukses pembayaran atau informasi lainnya
?>

<?php include('layouts/header.php'); ?>

<section class="success container my-5 py-5">
    <div class="container mt-5 successmid" >
        <h2 class="font-weight-bold">Pembayaran Sukses</h2>
        <hr /><br>
        <p>Terima kasih telah melakukan pembayaran.</p>

        <p>Pesanan Anda telah berhasil diproses.</p>

        <p>Silahkan lihat detail pesanan di halaman akun</p>
    </div>
</section>

<?php include('layouts/footer.php'); ?>