<?php
$uname = "root";
$dbpass = "123456";
$host = "127.0.0.1:3366";
$db = "shop_db";

try {
    $conn = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $uname, $dbpass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
