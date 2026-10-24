<?php
session_start();
require '../connection/koneksi.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
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
$query_produk = "SELECT id_barang, nama_barang, jenis, harga_barang, stok, status, foto FROM produk";
$result_produk = mysqli_query($koneksi, $query_produk);

$produk_bubur = [];
$produk_toping = [];
$produk_minuman = [];

if ($result_produk && mysqli_num_rows($result_produk) > 0) {
    while ($row = mysqli_fetch_assoc($result_produk)) {
        $jenis = strtolower(trim($row['jenis']));
        if ($jenis == 'bubur') {
            $produk_bubur[] =$row;
        } elseif ($jenis == 'toping') {
            $produk_toping[] =$row;
        } elseif ($jenis == 'minuman') {
            $produk_minuman[] =$row;
        } elseif ($jenis == 'snack') {
            $produk_snack[] =$row;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Main Menu - Kasir Bubur Racing</title>
    <?php include '../components/head_assets.php'; ?>
</head>
<body class="bg-[#f0eef1] h-screen font-body overflow-hidden flex flex-col text-gray-800 m-0 relative">

    <?php include '../components/header.php'; ?>

    <div class="flex flex-1 overflow-hidden p-6 gap-6">

        <?php include '../components/sidebar.php'; ?>

        <?php include '../components/product_content.php'; ?>

        <?php include '../components/cart_sidebar.php'; ?>
    </div>

    <?php include '../components/modals.php'; ?>

    <script src="../assets/js/produk.js"></script>
</body>
</html>