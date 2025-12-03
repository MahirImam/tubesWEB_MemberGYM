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

$username = sanitize($conn, $_POST['username']);
$password = sanitize($conn, $_POST['password']);
$nama_lengkap = sanitize($conn, $_POST['nama_lengkap']);
$no_hp = sanitize($conn, $_POST['no_hp']);
$jenis_member = sanitize($conn, $_POST['jenis_member']);
$tgl_kadaluarsa = sanitize($conn, $_POST['tgl_kadaluarsa']);

$tgl_gabung = date('Y-m-d');

$foto = $_FILES['foto_profil']['name'];
$lokasi = $_FILES['foto_profil']['tmp_name'];
$tipefile = $_FILES['foto_profil']['type'];
$ukuranfile = $_FILES['foto_profil']['size'];
$foto_nama_final = "";
$error = "";

$check_user = mysqli_query($conn, "SELECT username FROM users WHERE username='$username'");
if (mysqli_num_rows($check_user) > 0) {
    $error = "Username sudah digunakan!";
}

if ($error == "" && !empty($foto)) {
    if ($tipefile != "image/jpeg" && $tipefile != "image/jpg" && $tipefile != "image/png") {
        $error = "Tipe file foto tidak didukung.";
    } elseif ($ukuranfile >= 1000000) {
        $error = "Ukuran file foto lebih dari 1 MB.";
    } else {
        $ext = strrchr($foto, '.');
        $foto_nama_final = basename($foto, $ext) . time() . $ext;
        move_uploaded_file($lokasi, "assets/".$foto_nama_final);
    }
}

if ($error == "") {
    $sql_user = "INSERT INTO users (username, password, role) VALUES ('$username', '$password', 'user')";
    $insert_user = mysqli_query($conn, $sql_user);
    
    if ($insert_user) {
        $new_user_id = mysqli_insert_id($conn);
        
        $sql_member = "INSERT INTO members (id_user, nama_lengkap, no_hp, foto_profil, jenis_member, tgl_gabung, tgl_kadaluarsa) 
                       VALUES ('$new_user_id', '$nama_lengkap', '$no_hp', '$foto_nama_final', '$jenis_member', '$tgl_gabung', '$tgl_kadaluarsa')";
        $insert_member = mysqli_query($conn, $sql_member);
        
        if ($insert_member) {
            echo "<script>alert('Member berhasil ditambahkan!')</script>";
            echo "<meta http-equiv='refresh' content='0; url=members_crud.php'>";
        } else {
            mysqli_query($conn, "DELETE FROM users WHERE id_user='$new_user_id'"); 
            $error = "Gagal membuat data member: " . mysqli_error($conn);
        }
    } else {
        $error = "Gagal membuat akun login: " . mysqli_error($conn);
    }
}

if ($error != "") {
    echo "<script>alert('Pendaftaran Gagal: $error')</script>";
    echo "<meta http-equiv='refresh' content='3; url=members_crud.php'>";
}

mysqli_close($conn);
?>
