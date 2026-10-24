<?php
session_start();
require '../connection/koneksi.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$filter_date = $_GET['date'] ?? '';
$sort = $_GET['sort'] ?? 'waktu_desc';

$whereDate = "";
if (!empty($filter_date)) {
    $safe_date = mysqli_real_escape_string($koneksi, $filter_date);
    $whereDate = "WHERE DATE(t.tgl) = '$safe_date'";
}

$query = "SELECT t.id_transaksi, t.id_barang, t.harga_produk, t.total, t.uang, t.kembalian, t.nama, t.meja, t.pajak, t.metode, t.tgl, p.nama_barang, p.jenis 
          FROM transaksi t 
          JOIN produk p ON t.id_barang = p.id_barang 
          $whereDate
          ORDER BY t.tgl DESC, t.id_transaksi DESC";

$result = mysqli_query($koneksi, $query);
$riwayat_transaksi = [];

if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $nama_pelanggan = empty($row['nama']) ? 'Tanpa Nama' : trim($row['nama']);
        
        $waktu_db = !empty($row['tgl']) ? strtotime($row['tgl']) : time();
        $waktu_jam = date('H:i', $waktu_db);
        $waktu_hari = date('d M Y', $waktu_db);
        $tanggal_lengkap = date('d F Y, H:i', $waktu_db);
        
        $group_key = md5($nama_pelanggan . '_' . $row['tgl'] . '_' . $row['uang'] . '_' . $row['kembalian'] . '_' . $row['meja']);
        
        if (!isset($riwayat_transaksi[$group_key])) {
            $riwayat_transaksi[$group_key] = [
                'raw_id' => (int)$row['id_transaksi'],
                'raw_waktu' => $waktu_db,
                'order_id' => '#BR-' . str_pad($row['id_transaksi'], 4, '0', STR_PAD_LEFT),
                'waktu_jam' => $waktu_jam,
                'waktu_hari' => $waktu_hari,
                'tanggal' => $tanggal_lengkap,
                'metode' => !empty($row['metode']) ? strtoupper($row['metode']) : 'CASH',
                'barang_utama' => [],
                'barang_toping' => [],
                'subtotal' => 0,
                'total' => 0,
                'pajak' => (int)$row['pajak'],
                'uang' => (int)$row['uang'],
                'kembali' => (int)$row['kembalian'],
                'status' => 'SELESAI',
                'qty' => 0,
                'detail_produk' => []
            ];
        }

        $qty = ($row['harga_produk'] > 0) ? floor($row['total'] / $row['harga_produk']) : 1;
        if ($qty == 0) $qty = 1;

        $riwayat_transaksi[$group_key]['qty'] += $qty;
        $riwayat_transaksi[$group_key]['subtotal'] += $row['total'];
        $jenis = strtolower(trim($row['jenis']));
        $is_toping = ($jenis == 'toping' || $jenis == 'minuman');
        
        if ($is_toping) {
            $riwayat_transaksi[$group_key]['barang_toping'][] = "+ " . $qty . " " . $row['nama_barang'];
        } else {
            $riwayat_transaksi[$group_key]['barang_utama'][] = $qty . "x " . $row['nama_barang'];
        }

        $riwayat_transaksi[$group_key]['detail_produk'][] = [
            'nama' => ($is_toping ? '+ ' : '') . $row['nama_barang'],
            'qty' => $qty,
            'harga' => (int)$row['harga_produk'],
            'total_harga' => (int)$row['total'],
            'is_toping' => $is_toping
        ];
    }
}

foreach ($riwayat_transaksi as $key => $val) {
    $riwayat_transaksi[$key]['total'] = $val['subtotal'] + $val['pajak'];
}

$riwayat_transaksi = array_values($riwayat_transaksi);

usort($riwayat_transaksi, function($a, $b) use ($sort) {
    if ($sort === 'id_asc') return $a['raw_id'] <=> $b['raw_id'];
    if ($sort === 'id_desc') return $b['raw_id'] <=> $a['raw_id'];
    if ($sort === 'total_asc') return $a['total'] <=> $b['total'];
    if ($sort === 'total_desc') return $b['total'] <=> $a['total'];
    if ($sort === 'waktu_asc') return $a['raw_waktu'] <=> $b['raw_waktu'];
    return $b['raw_waktu'] <=> $a['raw_waktu'];
});

$url_date = !empty($filter_date) ? "&date=" . urlencode($filter_date) : "";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat - Kasir Bubur Racing</title>
    <?php include '../components/head_assets.php'; ?>
</head>
<body class="bg-[#f0eef1] h-screen font-body overflow-hidden flex flex-col text-gray-800 m-0 relative">

    <?php 
    $page_title = 'RIWAYAT'; 
    $search_placeholder = 'Cari ID transaksi...';
    include '../components/header.php'; 
    ?>

    <div class="flex flex-1 overflow-hidden p-6 gap-6">

        <?php 
        $active_page = 'riwayat'; 
        include '../components/sidebar.php'; 
        ?>

        <?php include '../components/riwayat_content.php'; ?>
    </div>

    <?php include '../components/riwayat_modal.php'; ?>

    <script src="../assets/js/riwayat.js"></script>
</body>
</html>