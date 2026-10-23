<?php
include "db.php";

$thongbao = "";
$masp = isset($_GET["MaSP"]) ? (int)$_GET["MaSP"] : 0;

// Lấy thông tin sản phẩm khách chọn mua
$sql_sp = "SELECT * FROM SanPham WHERE MaSP = $masp";
$result_sp = $conn->query($sql_sp);
$sp = ($result_sp->num_rows > 0) ? $result_sp->fetch_assoc() : null;

if (!$sp) {
    echo "Sản phẩm không tồn tại! <a href='index.php'>Quay lại trang chủ</a>";
    exit;
}

// Xử lý khi khách bấm nút Xác nhận đặt hàng
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $hoten = trim($_POST["HoTen"]);
    $sdt = trim($_POST["SoDienThoai"]);
    $diachi = trim($_POST["DiaChi"]);
    $soluongmua = (int)$_POST["SoLuongMua"];

    if (!empty($hoten) && !empty($sdt) && !empty($diachi) && $soluongmua > 0) {
        // 1. Lưu thông tin đơn hàng vào bảng DonHang
        $sql_dh = "INSERT INTO DonHang (HoTenKhach, SoDienThoai, DiaChi) VALUES ('$hoten', '$sdt', '$diachi')";
        
        if ($conn->query($sql_dh) === TRUE) {
            $madh_moi = $conn->insert_id; // Lấy mã đơn hàng vừa tạo
            $giaban = $sp["Gia"];

            // 2. Lưu chi tiết đơn hàng vào bảng ChiTietDonHang
            $sql_ct = "INSERT INTO ChiTietDonHang (MaDH, MaSP, SoLuongMua, GiaMua) VALUES ($madh_moi, $masp, $soluongmua, $giaban)";
            $conn->query($sql_ct);

            $thongbao = "Đặt hàng thành công! Cảm ơn bạn đã mua sắm.";
            $dat_hang_xong = true;
        } else {
            $thongbao = "Lỗi đặt hàng: " . $conn->error;
            $dat_hang_xong = false;
        }
    } else {
        $thongbao = "Vui lòng điền đầy đủ thông tin giao hàng hợp lệ!";
        $dat_hang_xong = false;
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thanh toán đơn hàng</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f2f2f2;
            margin: 0;
            padding: 0;
        }

        header {
            background: #2471a3;
            color: white;
            padding: 20px;
            text-align: center;
        }

        .navbar {
            text-align: center;
            background: #1b4f72;
            padding: 10px;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            margin: 0 15px;
            font-weight: bold;
        }

        .container {
            max-width: 600px;
            margin: 30px auto;
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 8px #ccc;
        }

        h2 {
            color: #2471a3;
            text-align: center;
        }

        .product-info {
            background: #f9f9f9;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            border-left: 4px solid #2471a3;
        }

        label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
        }

        input[type="text"], input[type="tel"], input[type="number"], textarea {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            background: #27ae60;
            color: white;
            padding: 12px;
            border: none;
            width: 100%;
            border-radius: 4px;
            margin-top: 20px;
            font-size: 16px;
            cursor: pointer;
            font-weight: bold;
        }

        button:hover {
            background: #219653;
        }

        .msg {
            color: green;
            text-align: center;
            font-weight: bold;
            font-size: 18px;
            margin-bottom: 20px;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 15px;
            text-decoration: none;
            color: #2471a3;
            font-weight: bold;
        }
    </style>
</head>
<body>

<header>
    <h1>WEBSITE BÁN HÀNG</h1>
    <p>Xác nhận mua hàng</p>
</header>

<div class="navbar">
    <a href="index.php">Trang chủ</a>
    <a href="them.php">Thêm sản phẩm</a>
    <a href="lienhe.php">Liên hệ</a>
</div>

<div class="container">
    <h2>Thông tin thanh toán</h2>

    <?php if (!empty($thongbao)) { echo "<p class='msg'>$thongbao</p>"; } ?>

    <?php if (isset($dat_hang_xong) && $dat_hang_xong === true) { ?>
        <a href="index.php" class="back-link" style="font-size: 18px; background: #2471a3; color: white; padding: 10px; border-radius: 5px;">Quay về trang chủ</a>
    <?php } else { ?>
        <div class="product-info">
            <p><strong>Sản phẩm:</strong> <?php echo htmlspecialchars($sp["TenSP"]); ?></p>
            <p><strong>Đơn giá:</strong> <span style="color: red; font-weight: bold;"><?php echo number_format($sp["Gia"], 0, ',', '.'); ?> VNĐ</span></p>
            <?php if (!empty($sp["Size"])) { ?>
                <p><strong>Size:</strong> <?php echo htmlspecialchars($sp["Size"]); ?></p>
            <?php } ?>
        </div>

        <form method="POST" action="">
            <label>Họ và tên người nhận:</label>
            <input type="text" name="HoTen" required>

            <label>Số điện thoại:</label>
            <input type="tel" name="SoDienThoai" required>

            <label>Địa chỉ nhận hàng:</label>
            <textarea name="DiaChi" rows="3" required></textarea>

            <label>Số lượng mua:</label>
            <input type="number" name="SoLuongMua" value="1" min="1" max="<?php echo $sp["SoLuong"]; ?>" required>

            <button type="submit">Xác nhận đặt hàng</button>
        </form>

        <a href="index.php" class="back-link">Hủy và quay lại trang chủ</a>
    <?php } ?>
</div>

</body>
</html>