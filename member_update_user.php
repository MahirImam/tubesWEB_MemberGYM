<?php
session_start();
include "config.php";

if(empty($_SESSION['username']) || $_SESSION['role'] != 'user'){
    echo "<script>alert('Akses Ditolak. Harap login sebagai Member.')</script>";
    echo "<meta http-equiv='refresh' content='0; url=index.php'>";
    exit();
}

function sanitize($conn, $input) {
    return mysqli_real_escape_string($conn, $input);
}

// 1. Ambil Data Form
$id_member = sanitize($conn, $_POST['id_member']);
$id_user = sanitize($conn, $_POST['id_user']);
$nama_lengkap = sanitize($conn, $_POST['nama_lengkap']);
$no_hp = sanitize($conn, $_POST['no_hp']);
$password_new = trim($_POST['password_new']); // Password baru (bisa kosong)

// Data Foto Lama
$foto_lama = sanitize($conn, $_POST['foto_lama'] ?? ''); // Null Coalescing Operator untuk menghindari error jika foto_lama tidak ada
$foto_nama_final = $foto_lama; // Default menggunakan foto lama
$error = "";

// 2. Proses Upload Foto Baru (jika ada)
if (isset($_FILES['foto_profil_new']) && $_FILES['foto_profil_new']['error'] == 0) {
    $foto = $_FILES['foto_profil_new']['name'];
    $lokasi = $_FILES['foto_profil_new']['tmp_name'];
    $tipefile = $_FILES['foto_profil_new']['type'];
    $ukuranfile = $_FILES['foto_profil_new']['size'];

    if ($tipefile != "image/jpeg" && $tipefile != "image/jpg" && $tipefile != "image/png") {
        $error = "Tipe file foto baru tidak didukung.";
    } elseif ($ukuranfile >= 1000000) {
        $error = "Ukuran file foto baru lebih dari 1 MB.";
    } else {
        // Hapus foto lama di server jika ada
        if (!empty($foto_lama) && file_exists("assets/".$foto_lama)) {
             unlink("assets/".$foto_lama);
        }
        
        $ext = strrchr($foto, '.');
        $foto_nama_final = "user_".$id_user."_".time() . $ext;
        move_uploaded_file($lokasi, "assets/".$foto_nama_final);
    }
}

// 3. Eksekusi Update
if ($error == "") {
    // --- UPDATE TABEL MEMBERS ---
    $sql_member = "UPDATE members SET 
                   nama_lengkap='$nama_lengkap', 
                   no_hp='$no_hp', 
                   foto_profil='$foto_nama_final'
                   WHERE id_member='$id_member'";
    $update_member = mysqli_query($conn, $sql_member);
    
    // --- UPDATE TABEL USERS (hanya jika password baru diisi) ---
    $update_user = true;
    if (!empty($password_new)) {
        $sql_user = "UPDATE users SET 
                     password='".sanitize($conn, $password_new)."'
                     WHERE id_user='$id_user'";
        $update_user = mysqli_query($conn, $sql_user);
    }

    if ($update_member && $update_user) {
        echo "<script>alert('Profil berhasil diperbarui!')</script>";
        echo "<meta http-equiv='refresh' content='0; url=profil_member.php'>";
    } else {
        $error = "Gagal memperbarui data: " . mysqli_error($conn);
    }
}

// 4. Notifikasi Error
if ($error != "") {
    echo "<script>alert('Update Gagal: $error')</script>";
    echo "<meta http-equiv='refresh' content='3; url=profil_member.php'>";
}

mysqli_close($conn);
?>