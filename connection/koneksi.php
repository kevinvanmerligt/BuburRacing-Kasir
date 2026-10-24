<?php

$username = "root";
$password = "";
$host = "localhost";
$database = "db_kasirbubur";

$koneksi = new mysqli($host, $username, $password, $database);
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

?>