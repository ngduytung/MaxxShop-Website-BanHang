<?php
session_start();
include "db.php";

$masp = isset($_GET["MaSP"]) ? (int)$_GET["MaSP"] : 0;

if ($masp > 0) {
    $sql = "SELECT * FROM SanPham WHERE MaSP = $masp";
    $result = $conn->query($sql);
    
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        if (isset($_SESSION['cart'][$masp])) {
            $_SESSION['cart'][$masp]['SoLuongMua'] += 1;
        } else {
            $_SESSION['cart'][$masp] = [
                'MaSP' => $row['MaSP'],
                'TenSP' => $row['TenSP'],
                'Gia' => $row['Gia'],
                'Size' => $row['Size'] ?? '',
                'SoLuongMua' => 1
            ];
        }
    }
}

header("Location: giohang.php");
exit;
?>