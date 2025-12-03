<?php
session_start();
include "config.php";

if(empty($_SESSION['username']) || $_SESSION['role'] != 'admin'){
    echo "<script>alert('Akses Admin Ditolak!')</script>";
    echo "<meta http-equiv='refresh' content='0; url=index.php'>";
    exit();
}

$id_member = $_GET['id'] ?? null;
$foto_hapus = $_GET['foto'] ?? null;

if (!$id_member) {
    header('location: members_crud.php');
    exit();
}

// 1. Ambil id_user sebelum menghapus record member
$query_member = mysqli_query($conn, "SELECT id_user FROM members WHERE id_member='$id_member'");
$data_member = mysqli_fetch_array($query_member);
$id_user = $data_member['id_user'] ?? null;


// 2. Hapus file foto dari server (jika ada)
if (!empty($foto_hapus) && file_exists("assets/".$foto_hapus)) {
    unlink("assets/".$foto_hapus);
}

// 3. Hapus record dari tabel members
$hapus_member = mysqli_query($conn, "DELETE FROM members WHERE id_member='$id_member'");

// 4. Hapus record dari tabel users (Otentikasi)
$hapus_user = true;
if ($id_user) {
    $hapus_user = mysqli_query($conn, "DELETE FROM users WHERE id_user='$id_user'");
}


if ($hapus_member && $hapus_user) {
    echo "<script>alert('Data member berhasil dihapus!')</script>";
    echo "<meta http-equiv='refresh' content='0; url=members_crud.php'>";
} else {
    echo "<script>alert('Gagal menghapus data: ".mysqli_error($conn)."')</script>";
    echo "<meta http-equiv='refresh' content='3; url=members_crud.php'>";
}

mysqli_close($conn);
?>