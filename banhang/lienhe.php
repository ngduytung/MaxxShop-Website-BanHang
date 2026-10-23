<?php
include "db.php";

$thongbao = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $hoten = trim($_POST["HoTen"]);
    $email = trim($_POST["Email"]);
    $noidung = trim($_POST["NoiDung"]);

    if (!empty($hoten) && !empty($email) && !empty($noidung)) {
        $sql = "INSERT INTO LienHe (HoTen, Email, NoiDung) VALUES ('$hoten', '$email', '$noidung')";
        if ($conn->query($sql) === TRUE) {
            $thongbao = "Gửi liên hệ thành công! Chúng tôi sẽ phản hồi sớm nhất.";
        } else {
            $thongbao = "Lỗi: " . $conn->error;
        }
    } else {
        $thongbao = "Vui lòng điền đầy đủ tất cả các trường!";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Ý kiến khách hàng - MaxxShop</title>
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

        input[type="text"], input[type="email"], textarea {
            width: 100%;
            padding: 10px;
            margin-top: 6px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
            outline: none;
        }

        input[type="text"]:focus, input[type="email"]:focus, textarea:focus {
            border-color: #5d1a29;
        }

        textarea {
            height: 120px;
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
    </style>
</head>
<body>

<header>
    <h1>MAXXSHOP</h1>
    <p>Trang thông tin và hỗ trợ khách hàng</p>
</header>

<div class="navbar">
    <a href="index.php">Trang chủ</a>
    <a href="giohang.php">🛒 Giỏ hàng</a>
    <a href="lienhe.php">Ý kiến khách hàng</a>
</div>

<div class="container">
    <h2>Gửi ý kiến khách hàng</h2>

    <?php if (!empty($thongbao)) { echo "<div class='msg'>$thongbao</div>"; } ?>

    <form method="POST" action="">
        <label>Họ và tên:</label>
        <input type="text" name="HoTen" required>

        <label>Email:</label>
        <input type="email" name="Email" required>

        <label>Nội dung lời nhắn:</label>
        <textarea name="NoiDung" required></textarea>

        <button type="submit" class="btn-submit">Gửi ý kiến</button>
    </form>
</div>

</body>
</html>