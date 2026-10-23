<?php
session_start();
include "db.php";

$thongbao = "";

if (isset($_GET['action'])) {
    $cart_key = isset($_GET['Key']) ? $_GET['Key'] : "";

    if ($_GET['action'] == 'xoa' && isset($_SESSION['cart'][$cart_key])) {
        unset($_SESSION['cart'][$cart_key]);
    }

    if ($_GET['action'] == 'tang' && isset($_SESSION['cart'][$cart_key])) {
        $_SESSION['cart'][$cart_key]['SoLuongMua'] += 1;
    }

    if ($_GET['action'] == 'giam' && isset($_SESSION['cart'][$cart_key])) {
        $_SESSION['cart'][$cart_key]['SoLuongMua'] -= 1;
        if ($_SESSION['cart'][$cart_key]['SoLuongMua'] <= 0) {
            unset($_SESSION['cart'][$cart_key]);
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    $hoten = trim($_POST["HoTen"]);
    $sdt = trim($_POST["SoDienThoai"]);
    $diachi = trim($_POST["DiaChi"]);

    if (!empty($hoten) && !empty($sdt) && !empty($diachi)) {
        $sql_dh = "INSERT INTO DonHang (HoTenKhach, SoDienThoai, DiaChi) VALUES ('$hoten', '$sdt', '$diachi')";
        
        if ($conn->query($sql_dh) === TRUE) {
            $madh_moi = $conn->insert_id;

            foreach ($_SESSION['cart'] as $item) {
                $masp = $item['MaSP'];
                $soluong = $item['SoLuongMua'];
                $gia = $item['Gia'];
                $size = $item['Size'];
                
                $sql_ct = "INSERT INTO ChiTietDonHang (MaDH, MaSP, SoLuongMua, GiaMua, Size) VALUES ($madh_moi, $masp, $soluong, $gia, '$size')";
                $conn->query($sql_ct);
            }

            unset($_SESSION['cart']);
            $thongbao = "Đặt hàng thành công! Mã đơn hàng của bạn là: #$madh_moi";
            $dat_hang_thanh_cong = true;
        } else {
            $thongbao = "Lỗi đặt hàng: " . $conn->error;
        }
    } else {
        $thongbao = "Vui lòng điền đầy đủ thông tin giao hàng!";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Giỏ hàng - MaxxShop</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f8f9fa;
            margin: 0;
            color: #333;
        }

        header {
            background: #5d1a29;
            color: white;
            padding: 15px 20px;
            text-align: center;
        }

        header h1 {
            margin: 0;
            font-size: 26px;
            letter-spacing: 1px;
        }

        header p {
            margin: 5px 0 0;
            font-size: 14px;
            color: #ddd;
        }

        .navbar {
            display: flex;
            justify-content: flex-start;
            align-items: center;
            background: #ffffff;
            padding: 12px 30px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            gap: 25px;
            border-bottom: 1px solid #e5e5e5;
        }

        .navbar a {
            color: #5d1a29;
            text-decoration: none;
            font-weight: 600;
            font-size: 15px;
            transition: color 0.2s;
        }

        .navbar a:hover {
            color: #c0392b;
        }

        .container {
            max-width: 850px;
            margin: 30px auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            border-top: 4px solid #5d1a29;
        }

        h2 {
            color: #5d1a29;
            text-align: center;
            margin-top: 0;
            margin-bottom: 25px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #e5e5e5;
            padding: 12px;
            text-align: center;
            vertical-align: middle;
            font-size: 14px;
        }

        th {
            background: #5d1a29;
            color: white;
            font-weight: 600;
        }

        .total {
            text-align: right;
            font-weight: bold;
            font-size: 18px;
            margin-top: 20px;
            color: #444;
        }

        .btn-soluong {
            display: inline-block;
            background: #f1f1f1;
            color: #333;
            padding: 4px 10px;
            text-decoration: none;
            font-weight: bold;
            border-radius: 3px;
            margin: 0 5px;
            border: 1px solid #ccc;
        }

        .btn-soluong:hover {
            background: #e2e2e2;
        }

        .btn-xoa {
            background: #e74c3c;
            color: white;
            padding: 6px 12px;
            text-decoration: none;
            border-radius: 4px;
            font-size: 13px;
            font-weight: bold;
        }

        .btn-xoa:hover {
            background: #c0392b;
        }

        .form-tt {
            margin-top: 30px;
            border-top: 2px solid #f1f1f1;
            padding-top: 20px;
        }

        .form-tt h3 {
            color: #5d1a29;
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-top: 12px;
            font-weight: 600;
            font-size: 14px;
        }

        input[type="text"], input[type="tel"], textarea {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
            outline: none;
        }

        input[type="text"]:focus, input[type="tel"]:focus, textarea:focus {
            border-color: #5d1a29;
        }

        .btn-dat-hang {
            background: #27ae60;
            color: white;
            padding: 12px;
            border: none;
            width: 100%;
            border-radius: 4px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 20px;
            transition: background 0.2s;
        }

        .btn-dat-hang:hover {
            background: #219653;
        }

        .msg {
            color: #27ae60;
            text-align: center;
            font-weight: bold;
            font-size: 16px;
            margin-bottom: 20px;
            background: #d4edda;
            padding: 12px;
            border-radius: 4px;
        }
    </style>
</head>
<body>

<header>
    <h1>MAXXSHOP</h1>
    <p>Hệ thống thời trang và phụ kiện trực tuyến</p>
</header>

<div class="navbar">
    <a href="index.php">Trang chủ</a>
    <a href="giohang.php">🛒 Giỏ hàng</a>
    <a href="lienhe.php">Liên hệ</a>
</div>

<div class="container">
    <h2>Chi tiết giỏ hàng</h2>

    <?php if (!empty($thongbao)) { echo "<div class='msg'>$thongbao</div>"; } ?>

    <?php if (isset($dat_hang_thanh_cong) && $dat_hang_thanh_cong === true) { ?>
        <div style="text-align: center; margin-top: 20px;">
            <a href="index.php" style="background: #5d1a29; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-weight: bold;">Tiếp tục mua sắm</a>
        </div>
    <?php } elseif (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) { ?>
        <table>
            <tr>
                <th>STT</th>
                <th>Tên sản phẩm</th>
                <th>Size</th>
                <th>Giá</th>
                <th>Số lượng</th>
                <th>Thành tiền</th>
                <th>Thao tác</th>
            </tr>
            <?php 
            $stt = 1;
            $tongtien = 0;
            foreach ($_SESSION['cart'] as $key => $item) {
                $thanhtien = $item['Gia'] * $item['SoLuongMua'];
                $tongtien += $thanhtien;
            ?>
            <tr>
                <td><?php echo $stt++; ?></td>
                <td><?php echo htmlspecialchars($item['TenSP']); ?></td>
                <td><span style="background: #f8d7da; color: #721c24; padding: 3px 8px; border-radius: 3px; font-weight: bold; font-size: 12px;"><?php echo htmlspecialchars($item['Size']); ?></span></td>
                <td><?php echo number_format($item['Gia'], 0, ',', '.'); ?> ₫</td>
                <td>
                    <a href="giohang.php?action=giam&Key=<?php echo $key; ?>" class="btn-soluong">[-]</a>
                    <span style="font-weight: bold; font-size: 15px; margin: 0 5px;"><?php echo $item['SoLuongMua']; ?></span>
                    <a href="giohang.php?action=tang&Key=<?php echo $key; ?>" class="btn-soluong">[+]</a>
                </td>
                <td style="color: #c0392b; font-weight: bold;"><?php echo number_format($thanhtien, 0, ',', '.'); ?> ₫</td>
                <td>
                    <a href="giohang.php?action=xoa&Key=<?php echo $key; ?>" class="btn-xoa" onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này khỏi giỏ hàng?');">Xóa</a>
                </td>
            </tr>
            <?php } ?>
        </table>

        <div class="total">
            Tổng cộng: <span style="color: #c0392b;"><?php echo number_format($tongtien, 0, ',', '.'); ?> ₫</span>
        </div>

        <div class="form-tt">
            <h3>Thông tin thanh toán và giao hàng</h3>
            <form method="POST" action="">
                <label>Họ và tên người nhận:</label>
                <input type="text" name="HoTen" required>

                <label>Số điện thoại:</label>
                <input type="tel" name="SoDienThoai" required>

                <label>Địa chỉ nhận hàng:</label>
                <textarea name="DiaChi" rows="3" required></textarea>

                <button type="submit" class="btn-dat-hang">Xác nhận đặt hàng</button>
            </form>
        </div>
    <?php } else { ?>
        <p style="text-align: center; color: #666; font-size: 16px;">Giỏ hàng của bạn đang trống.</p>
        <div style="text-align: center; margin-top: 20px;">
            <a href="index.php" style="background: #5d1a29; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-weight: bold;">Quay lại mua sắm</a>
        </div>
    <?php } ?>
</div>

</body>
</html>