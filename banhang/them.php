<?php
include "db.php";

$thongbao = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tensp = trim($_POST["TenSP"]);
    $gia = trim($_POST["Gia"]);
    $soluong = trim($_POST["SoLuong"]);
    $mota = trim($_POST["MoTa"]);

    if (!empty($tensp) && !empty($gia) && !empty($soluong)) {
        $sql = "INSERT INTO SanPham (TenSP, Gia, SoLuong, Size, MoTa) VALUES ('$tensp', '$gia', '$soluong', '', '$mota')";
        if ($conn->query($sql) === TRUE) {
            $thongbao = "Thêm sản phẩm thành công!";
        } else {
            $thongbao = "Lỗi: " . $conn->error;
        }
    } else {
        $thongbao = "Vui lòng điền đầy đủ các thông tin bắt buộc!";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm sản phẩm - MaxxShop</title>
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

        .form-container {
            max-width: 550px;
            margin: 40px auto;
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

        label {
            display: block;
            margin-top: 15px;
            font-weight: 600;
            font-size: 14px;
            color: #444;
        }

        input[type="text"], input[type="number"], textarea {
            width: 100%;
            padding: 10px;
            margin-top: 6px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
            outline: none;
        }

        input[type="text"]:focus, input[type="number"]:focus, textarea:focus {
            border-color: #5d1a29;
        }

        textarea {
            height: 100px;
            resize: vertical;
        }

        .btn-submit {
            background: #27ae60;
            color: white;
            padding: 12px;
            border: none;
            width: 100%;
            border-radius: 4px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 25px;
            transition: background 0.2s;
        }

        .btn-submit:hover {
            background: #219653;
        }

        .msg {
            color: #27ae60;
            text-align: center;
            font-weight: bold;
            margin-bottom: 20px;
            background: #d4edda;
            padding: 10px;
            border-radius: 4px;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            text-decoration: none;
            color: #5d1a29;
            font-weight: 600;
            font-size: 14px;
        }

        .back-link:hover {
            text-decoration: underline;
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

<div class="form-container">
    <h2>Thêm sản phẩm mới</h2>

    <?php if (!empty($thongbao)) { echo "<div class='msg'>$thongbao</div>"; } ?>

    <form method="POST" action="">
        <label>Tên sản phẩm:</label>
        <input type="text" name="TenSP" required>

        <label>Giá (VNĐ):</label>
        <input type="number" name="Gia" step="10000" min="0" value="10000" required>

        <label>Số lượng kho:</label>
        <input type="number" name="SoLuong" min="1" value="10" required>

        <label>Mô tả:</label>
        <textarea name="MoTa"></textarea>

        <button type="submit" class="btn-submit">Thêm sản phẩm</button>
    </form>

    <a href="index.php" class="back-link">&larr; Quay lại trang chủ</a>
</div>

</body>
</html>