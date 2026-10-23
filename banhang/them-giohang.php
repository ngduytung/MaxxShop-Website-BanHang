<?php
session_start();
include "db.php";

$masp = isset($_GET["MaSP"]) ? (int)$_GET["MaSP"] : 0;
$size = isset($_POST["Size"]) ? trim($_POST["Size"]) : "M";

if ($masp > 0) {
    $sql = "SELECT * FROM SanPham WHERE MaSP = $masp";
    $result = $conn->query($sql);
    
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        $cart_key = $masp . "_" . $size;

        if (isset($_SESSION['cart'][$cart_key])) {
            $_SESSION['cart'][$cart_key]['SoLuongMua'] += 1;
        } else {
            $_SESSION['cart'][$cart_key] = [
                'MaSP' => $row['MaSP'],
                'TenSP' => $row['TenSP'],
                'Gia' => $row['Gia'],
                'Size' => $size,
                'SoLuongMua' => 1
            ];
        }
    }
}

header("Location: index.php?them=thanhcong");
exit;
?>