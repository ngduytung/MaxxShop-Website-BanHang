<?php
include "db.php";

// Kiểm tra xem có nhận được MaSP được gửi lên trên URL hay không
if (isset($_GET["MaSP"])) {
    $masp = (int)$_GET["MaSP"];

    // Câu lệnh SQL xóa sản phẩm theo MaSP
    $sql = "DELETE FROM SanPham WHERE MaSP = $masp";

    if ($conn->query($sql) === TRUE) {
        // Xóa thành công thì chuyển hướng về trang chủ
        header("Location: index.php");
        exit;
    } else {
        echo "Lỗi khi xóa sản phẩm: " . $conn->error;
    }
} else {
    // Nếu không có MaSP thì quay về trang chủ
    header("Location: index.php");
    exit;
}

$conn->close();
?>