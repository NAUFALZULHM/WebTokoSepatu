<?php
session_start();
include('connection.php');
// jika user belum/tidak login
if (!isset($_SESSION['logged_in'])) {
    header('location: ../checkout.php?message=Silahkan login/daftar untuk melakukan pemesanan');
    exit;
    // jika user sudah login
} else {
    if (isset($_POST['place_order'])) {
        // 1. Dapatkan info pengguna dan simpan di database
        $name = $_POST['name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $city = $_POST['city'];
        $order_cost = $_SESSION['total'];
        $order_status = "belum dibayar";
        $user_id = $_SESSION['user_id'];
        $order_date = date('Y-m-d H:i:s');

        // Masukkan order ke database
        $stmt = $conn->prepare("INSERT INTO orders (order_cost, order_status, user_id, user_phone, user_city, order_date) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('isiiss', $order_cost, $order_status, $user_id, $phone, $city, $order_date);

        $stmt_status = $stmt->execute();

        if (!$stmt_status) {
            header('location: ../index.php');
            exit;
        }

        // 2. Mengeluarkan pesanan baru dan menyimpan info pesanan di database
        $order_id = $stmt->insert_id;

        // 3. Dapatkan produk dari keranjang (dari sesi)
        foreach ($_SESSION['cart'] as $key => $value) {
            $product = $_SESSION['cart'][$key];
            $product_id = $product['product_id'];
            $product_name = $product['product_name'];
            $product_image = $product['product_image'];
            $product_price = $product['product_price'];
            $product_quantity = $product['product_quantity'];

            // 4. Simpan setiap item di database order_items
            $stmt1 = $conn->prepare("INSERT INTO order_items (order_id, product_id, product_name, product_image, product_price, product_quantity, user_id, order_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt1->bind_param('iissiiis', $order_id, $product_id, $product_name, $product_image, $product_price, $product_quantity, $user_id, $order_date);
            $stmt1->execute();
        }

        // 5. Keluarkan semuanya dari keranjang --> tunda sampai pembayaran selesai
        // unset($_SESSION['cart']);

        // 6. Beri tahu pengguna apakah semuanya baik-baik saja atau ada masalah
        header('location: ../payment.php?order_status=Pesanan telah perhasil');
    }
}
