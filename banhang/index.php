<?php
session_start();
include "db.php";

$tukhoa = isset($_GET['tukhoa']) ? trim($_GET['tukhoa']) : "";

if ($tukhoa != "") {
    $tukhoa_sql = $conn->real_escape_string($tukhoa);
    $sql = "SELECT * FROM sanpham WHERE TenSP LIKE '%$tukhoa_sql%'";
} else {
    $sql = "SELECT * FROM sanpham";
}

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>MaxxShop - Website bán hàng</title>
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

        .top-bar {
            background: #4a131f;
            padding: 10px 30px;
            display: flex;
            justify-content: flex-end;
            align-items: center;
        }

        .search-form {
            display: flex;
            align-items: center;
        }

        .search-form input[type="text"] {
            padding: 6px 12px;
            border: none;
            border-radius: 4px 0 0 4px;
            outline: none;
            font-size: 14px;
            width: 220px;
        }

        .search-form button {
            background: #27ae60;
            color: white;
            border: none;
            padding: 6px 14px;
            border-radius: 0 4px 4px 0;
            cursor: pointer;
            font-weight: bold;
            font-size: 14px;
        }

        .search-form button:hover {
            background: #219653;
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

        .alert-success {
            background: #d4edda;
            color: #155724;
            padding: 12px;
            text-align: center;
            font-weight: 600;
            border-bottom: 1px solid #c3e6cb;
        }

        .actions-container {
            max-width: 1200px;
            margin: 25px auto 10px;
            padding: 0 20px;
            display: flex;
            justify-content: flex-end;
        }

        .btn-them {
            background: #27ae60;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            display: inline-block;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: background 0.2s;
            cursor: pointer;
        }

        .btn-them:hover {
            background: #219653;
        }

        h2.title-sec {
            text-align: center;
            color: #444;
            margin: 20px 0 30px;
            font-size: 22px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .products {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 25px;
            padding: 0 20px 40px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .product {
            background: white;
            width: 240px;
            padding: 15px;
            border-radius: 6px;
            text-align: center;
            border: 1px solid #eaeaea;
            box-shadow: 0 2px 6px rgba(0,0,0,0.04);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .product:hover {
            transform: translateY(-4px);
            box-shadow: 0 6px 15px rgba(0,0,0,0.08);
        }

        .product img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-radius: 4px;
            margin-bottom: 10px;
            border: 1px solid #eee;
        }

        .product h3 {
            font-size: 16px;
            color: #2c3e50;
            margin: 10px 0 5px;
        }

        .price {
            color: #c0392b;
            font-weight: bold;
            font-size: 16px;
            margin: 8px 0;
        }

        .desc {
            font-size: 13px;
            color: #666;
            margin: 5px 0 10px;
            min-height: 35px;
        }

        .stock {
            font-size: 12px;
            color: #888;
            margin-bottom: 8px;
        }

        .buy-form {
            margin-bottom: 10px;
        }

        .buy-form select {
            padding: 5px;
            border-radius: 4px;
            border: 1px solid #ccc;
            margin-right: 5px;
            font-size: 13px;
        }

        .btn-giohang {
            background: #5d1a29;
            color: white;
            border: none;
            padding: 7px 12px;
            border-radius: 4px;
            font-weight: 600;
            font-size: 13px;
            cursor: pointer;
            transition: background 0.2s;
        }
        .btn-giohang:hover {
            background: #4a131f;
        }

        .btn-group {
            display: flex;
            justify-content: space-between;
            gap: 6px;
            border-top: 1px solid #eee;
            padding-top: 10px;
        }

        .btn-sua, .btn-xoa {
            text-decoration: none;
            padding: 5px 0;
            border-radius: 3px;
            font-size: 13px;
            font-weight: 600;
            color: white;
            flex: 1;
            text-align: center;
        }

        .btn-sua { background: #f39c12; }
        .btn-sua:hover { background: #d68910; }

        .btn-xoa { background: #e74c3c; }
        .btn-xoa:hover { background: #c0392b; }
    </style>

    <script>
        function kiemTraChuShop(event) {
            event.preventDefault(); 
            let matkhau = prompt("Vui lòng nhập mật khẩu chủ shop (1903):");
            if (matkhau === "1903") {
                window.location.href = "them.php"; 
            } else if (matkhau !== null) {
                alert("Mật khẩu không đúng! Bạn không có quyền thêm sản phẩm.");
            }
        }
    </script>
</head>
<body>

<header>
    <h1>MAXXSHOP</h1>
    <p>Hệ thống thời trang và phụ kiện trực tuyến</p>
</header>

<div class="top-bar">
    <form class="search-form" method="GET" action="index.php">
        <input type="text" name="tukhoa" placeholder="Tìm sản phẩm..." value="<?php echo htmlspecialchars($tukhoa); ?>">
        <button type="submit">Tìm</button>
    </form>
</div>

<div class="navbar">
    <a href="index.php">Trang chủ</a>
    <a href="giohang.php">🛒 Giỏ hàng 
        <?php 
            $soluong_gh = 0;
            if (isset($_SESSION['cart'])) {
                foreach ($_SESSION['cart'] as $item) {
                    $soluong_gh += $item['SoLuongMua'];
                }
            }
            if ($soluong_gh > 0) {
                echo "($soluong_gh)";
            }
        ?>
    </a>
    <a href="lienhe.php">Ý kiến khách hàng</a>
</div>

<?php if (isset($_GET['them']) && $_GET['them'] == 'thanhcong') { ?>
    <div class="alert-success">Đã thêm sản phẩm vào giỏ hàng thành công!</div>
<?php } ?>

<div class="actions-container">
    <a href="#" onclick="kiemTraChuShop(event)" class="btn-them">+ Thêm sản phẩm mới</a>
</div>

<h2 class="title-sec">
    <?php if ($tukhoa != "") { ?>
        Kết quả tìm kiếm cho: "<?php echo htmlspecialchars($tukhoa); ?>"
    <?php } else { ?>
        Sản phẩm nổi bật
    <?php } ?>
</h2>

<div class="products">
    <?php if ($result && $result->num_rows > 0) { ?>
        <?php while ($row = $result->fetch_assoc()) { 
            $hinh = $row["HinhAnh"] ?? ($row["Image"] ?? "");
        ?>
            <div class="product">
                <div>
                    <?php if (!empty($hinh)) { ?>
                        <img src="uploads/<?php echo htmlspecialchars(trim($hinh)); ?>" alt="<?php echo htmlspecialchars($row["TenSP"]); ?>">
                    <?php } else { ?>
                        <img src="uploads/default.png" alt="No image">
                    <?php } ?>

                    <h3><?php echo htmlspecialchars($row["TenSP"]); ?></h3>

                    <p class="price">
                        <?php echo number_format($row["Gia"], 0, ',', '.'); ?> ₫
                    </p>

                    <div class="stock">Kho: <?php echo $row["SoLuong"]; ?> sản phẩm</div>

                    <div class="desc"><?php echo htmlspecialchars($row["MoTa"] ?? ""); ?></div>
                </div>

                <div>
                    <form class="buy-form" method="GET" action="them-giohang.php">
                        <input type="hidden" name="MaSP" value="<?php echo (int)$row['MaSP']; ?>">
                        <select name="Size">
                            <option value="S">Size S</option>
                            <option value="M" selected>Size M</option>
                            <option value="L">Size L</option>
                            <option value="XL">Size XL</option>
                        </select>
                        <button type="submit" class="btn-giohang">Mua hàng</button>
                    </form>

                    <div class="btn-group">
                        <a href="sua.php?MaSP=<?php echo (int)$row['MaSP']; ?>" class="btn-sua">Sửa</a>
                        <a href="xoa.php?MaSP=<?php echo (int)$row['MaSP']; ?>" class="btn-xoa" onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này không?');">Xóa</a>
                    </div>
                </div>
            </div>
        <?php } ?>
    <?php } else { ?>
        <p style="text-align: center; width: 100%; color: #666; font-size: 16px;">Không tìm thấy sản phẩm nào phù hợp.</p>
    <?php } ?>
</div>

</body>
</html>
<footer style="background: #4a131f; color: #ffffff; padding: 40px 20px 20px; margin-top: 50px; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
    <div style="max-width: 1200px; margin: 0 auto; display: flex; flex-wrap: wrap; justify-content: space-between; gap: 30px; border-bottom: 1px solid rgba(255,255,255,0.15); padding-bottom: 30px;">
        
        <div style="flex: 1; min-width: 250px;">
            <h3 style="color: #fff; font-size: 18px; margin-bottom: 12px; text-transform: uppercase; letter-spacing: 0.5px;">Công Ty TNHH Chớp</h3>
            <p style="font-size: 14px; color: #ddd; line-height: 1.6; margin: 0;">
                MaxxShop - Hệ thống thời trang và phụ kiện trực tuyến hàng đầu, mang đến phong cách trẻ trung, hiện đại và chất lượng tốt nhất cho khách hàng.
            </p>
        </div>

        <div style="flex: 1; min-width: 250px;">
            <h3 style="color: #fff; font-size: 18px; margin-bottom: 12px; text-transform: uppercase; letter-spacing: 0.5px;">Liên hệ với chúng tôi</h3>
            <p style="font-size: 14px; color: #ddd; margin: 6px 0;">📍 <strong>Địa chỉ:</strong> Hà Nội, Việt Nam</p>
            <p style="font-size: 14px; color: #ddd; margin: 6px 0;">📞 <strong>Điện thoại:</strong> <a href="tel:0963089210" style="color: #fff; text-decoration: none;">0963 089 210</a></p>
            <p style="font-size: 14px; color: #ddd; margin: 6px 0;">📧 <strong>Email:</strong> <a href="mailto:ngduytung1903tm@gmail.com" style="color: #fff; text-decoration: none;">ngduytung1903tm@gmail.com</a></p>
        </div>

        <div style="flex: 1; min-width: 250px;">
            <h3 style="color: #fff; font-size: 18px; margin-bottom: 12px; text-transform: uppercase; letter-spacing: 0.5px;">Kết nối & Hỗ trợ</h3>
            <p style="font-size: 14px; color: #ddd; margin: 6px 0;">
                🌐 <strong>Facebook:</strong> <a href="https://www.facebook.com/share/1c3CjtJRZc/" target="_blank" style="color: #f39c12; text-decoration: none; font-weight: 600;">Fanpage MaxxShop</a>
            </p>
            <p style="font-size: 14px; color: #ddd; margin: 6px 0;">
                💬 <strong>Đóng góp ý kiến:</strong> <a href="lienhe.php" style="color: #f39c12; text-decoration: none; font-weight: 600;">Gửi phản hồi tại đây</a>
            </p>
        </div>

    </div>

    <div style="text-align: center; font-size: 13px; color: #bbb; margin-top: 20px;">
        &copy; 2026 MaxxShop - Thuộc sở hữu của Công Ty TNHH Chớp. Bảo lưu mọi quyền.
    </div>
</footer>
<?php
if (isset($conn) && $conn) {
    $conn->close();
}
?>