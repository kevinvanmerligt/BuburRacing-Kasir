<?php
session_start();
require '../connection/koneksi.php';
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$categories = ['Bubur', 'Toping', 'Minuman'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $nama   = trim($_POST['nama_barang'] ?? '');
    $jenis  = in_array($_POST['jenis'] ?? '', $categories) ? $_POST['jenis'] : 'Bubur';
    
    $harga_raw = $_POST['harga_barang'] ?? '0';
    $harga     = (int)preg_replace('/[^0-9]/', '', $harga_raw);
    
    $stok   = (int)($_POST['stok'] ?? 0);
    
    $status = ($stok <= 0) ? 0 : (((int)($_POST['status'] ?? 1) === 1) ? 1 : 0);

    $foto_b64 = $_POST['foto_b64'] ?? '';
    $foto_name = '';

    if (!empty($foto_b64)) {
        $image_parts = explode(";base64,", $foto_b64);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1] ?? 'png';
        $image_base64 = base64_decode($image_parts[1]);
        
        $foto_name = uniqid() . '.' . $image_type;
        $file = '../uploads/' . $foto_name; 
        file_put_contents($file, $image_base64);
    }

    if ($nama !== '') {
        if ($action === 'add') {
            $stmt = mysqli_prepare($koneksi, "INSERT INTO produk (nama_barang, jenis, harga_barang, stok, status, foto) VALUES (?, ?, ?, ?, ?, ?)");
            mysqli_stmt_bind_param($stmt, "ssiiis", $nama, $jenis, $harga, $stok, $status, $foto_name);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        } elseif ($action === 'edit') {
            $id = (int)($_POST['id_barang'] ?? 0);
            
            if (!empty($foto_name)) {
                $stmt = mysqli_prepare($koneksi, "UPDATE produk SET nama_barang = ?, jenis = ?, harga_barang = ?, stok = ?, status = ?, foto = ? WHERE id_barang = ?");
                mysqli_stmt_bind_param($stmt, "ssiiisi", $nama, $jenis, $harga, $stok, $status, $foto_name, $id);
            } else {
                $stmt = mysqli_prepare($koneksi, "UPDATE produk SET nama_barang = ?, jenis = ?, harga_barang = ?, stok = ?, status = ? WHERE id_barang = ?");
                mysqli_stmt_bind_param($stmt, "ssiiii", $nama, $jenis, $harga, $stok, $status, $id);
            }
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
    }

    header("Location: kelolaproduk.php?success=1");
    exit();
}

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = mysqli_prepare($koneksi, "DELETE FROM produk WHERE id_barang = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header("Location: kelolaproduk.php?deleted=1");
    exit();
}

$produk_by_jenis = [];
$result = mysqli_query($koneksi, "SELECT * FROM produk ORDER BY jenis, nama_barang");
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $produk_by_jenis[$row['jenis']][] = $row;
    }
}
$ada_produk = !empty($produk_by_jenis);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Produk - Kasir Bubur Racing</title>
    <?php include '../components/head_assets.php'; ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
    <style>
        .cropper-container { width: 100% !important; max-height: 400px; }
    </style>
</head>
<body class="bg-[#f0eef1] h-screen font-body overflow-hidden flex flex-col text-gray-800 m-0 relative">

    <?php $page_title = 'KELOLA PRODUK'; ?>
    <?php include '../components/header.php'; ?>

    <div class="flex flex-1 overflow-hidden p-6 gap-6">

        <?php $active_page = 'kelolaproduk'; ?>
        <?php include '../components/sidebar.php'; ?>

        <?php include '../components/kelola_content.php'; ?>
    </div>

    <?php include '../components/kelola_modals.php'; ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
    <script src="../assets/js/kelolaproduk.js"></script>
</body>
</html>