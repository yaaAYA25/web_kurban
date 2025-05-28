<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
include "../koneksi/koneksi.php";

$username = $_POST['username'];
$psw = $_POST['password'];

$sql = "SELECT * FROM users WHERE username='$username'";
$query = $koneksi->query($sql);

if (mysqli_num_rows($query) == 1) {
    $data = $query->fetch_array();

    if (password_verify($psw, $data['password'])) {
        $_SESSION['id_user'] = $data['id_user'];
        $_SESSION['username'] = $data['username'];
        $_SESSION['warga_id'] = $data['warga_id'];

        // Ambil semua role user
        $user_id = $data['id_user'];
        $resultRoles = $koneksi->query("SELECT role FROM user_roles WHERE user_id = $user_id");
        $roles = [];
        while ($rowRole = $resultRoles->fetch_assoc()) {
            $roles[] = $rowRole['role'];
        }
        $_SESSION['roles'] = $roles;

        // Setelah login, langsung redirect ke home.php
        header("Location: ../view/home.php");
        exit();

    } else {
        die("Password salah. <a href='javascript:history.back()'>Kembali</a>");
    }
} else {
    die("Username tidak ditemukan. <a href='javascript:history.back()'>Kembali</a>");
}
