<?php
session_start();
include "config.php"; 

if(empty($_SESSION['username']) || $_SESSION['role'] != 'admin'){
    echo "<script>alert('Akses Admin Ditolak!')</script>";
    echo "<meta http-equiv='refresh' content='0; url=index.php'>";
    exit();
}

function sanitize($conn, $input) {
    return mysqli_real_escape_string($conn, $input);
}

$nama_tier = sanitize($conn, $_POST['nama_tier']);
$harga = sanitize($conn, $_POST['harga']);
$deskripsi = sanitize($conn, $_POST['deskripsi_fitur']);

$sql = "INSERT INTO membership_tiers (nama_tier, harga, deskripsi_fitur) 
        VALUES ('$nama_tier', '$harga', '$deskripsi')";
$query = mysqli_query($conn, $sql);

if($query){
    echo "<script>alert('Paket berhasil ditambahkan!')</script>";
    echo "<meta http-equiv='refresh' content='0; url=tiers_crud.php'>";
} else {
    echo "<script>alert('Gagal menyimpan paket: ". mysqli_error($conn) ."')</script>";
    echo "<meta http-equiv='refresh' content='3; url=tiers_crud.php'>";
}
mysqli_close($conn);
?>