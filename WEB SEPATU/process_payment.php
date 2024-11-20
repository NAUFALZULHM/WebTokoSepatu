<?php
session_start();
include('server/connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['order_total_price']) && isset($_POST['amount']) && isset($_POST['order_id'])) {
        $order_total_price = $_POST['order_total_price'];
        $amount_paid = $_POST['amount'];
        $order_id = $_POST['order_id']; // Pastikan order_id diambil dengan benar dari form POST

        // Proses verifikasi pembayaran
        if ($amount_paid >= $order_total_price) {
            // Update status pesanan menjadi 'sudah dibayar'
            $stmt = $conn->prepare("UPDATE orders SET order_status='sudah dibayar' WHERE order_id=?");
            $stmt->bind_param('i', $order_id);

            if ($stmt->execute()) {
                // Hapus total dari session jika sudah dibayar
                unset($_SESSION['total']);
                // Redirect ke halaman sukses pembayaran atau halaman terkait
                header('Location: success.php');
                exit();
            } else {
                $_SESSION['error'] = 'Gagal mengupdate status pesanan';
                header('Location: payment.php?order_id=' . $order_id);
                exit();
            }
        } else {
            $_SESSION['error'] = 'Jumlah yang dibayar tidak mencukupi';
            header('Location: payment.php?order_id=' . $order_id);
            exit();
        }
    } else {
        header('Location: payment.php');
        exit();
    }
}
