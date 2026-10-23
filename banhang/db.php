
<?php
$conn = new mysqli("localhost", "root", "", "banhang");

if ($conn->connect_error) {
    die("Ket noi that bai: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
?>
