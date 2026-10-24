<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../connection/koneksi.php';

if (!isset($_SESSION['username'])) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'Belum login']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SERVER['CONTENT_TYPE']) && strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['action']) && $data['action'] === 'checkout') {
        $uang = (int)$data['uang'];
        $kembalian = (int)$data['kembalian'];
        $pajak = (int)($data['pajak'] ?? 0);
        $nama = trim($data['nama'] ?? '');
        $meja = (int)($data['meja'] ?? 0);
        $metode = trim($data['metode'] ?? '');
        $cart = $data['cart'];

        mysqli_begin_transaction($koneksi);
        try {
            foreach ($cart as $id => $item) {
                $id_barang = (int)$id;
                $qty = (int)$item['qty'];
                $harga_produk = (int)$item['price'];
                $total = $qty * $harga_produk;

                $stmt_transaksi = mysqli_prepare($koneksi, "INSERT INTO transaksi (id_barang, harga_produk, metode, total, uang, kembalian, nama, meja, pajak) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                mysqli_stmt_bind_param($stmt_transaksi, "iisiiisis", $id_barang, $harga_produk, $metode, $total, $uang, $kembalian, $nama, $meja, $pajak);
                mysqli_stmt_execute($stmt_transaksi);
                mysqli_stmt_close($stmt_transaksi);

                $stmt_stok = mysqli_prepare($koneksi, "UPDATE produk SET stok = stok - ? WHERE id_barang = ? AND stok >= ?");
                mysqli_stmt_bind_param($stmt_stok, "iii", $qty, $id_barang, $qty);
                mysqli_stmt_execute($stmt_stok);
                mysqli_stmt_close($stmt_stok);
            }
            mysqli_commit($koneksi);
            echo json_encode(['status' => 'success']);
        } catch (Exception $e) {
            mysqli_rollback($koneksi);
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        exit();
    }
}
