<?php
$servername = "localhost";
$username = "root"; // varsayılan MySQL kullanıcı adı
$password = ""; //  
$dbname = "test";

// Bağlantı oluşturma
$conn = new mysqli($servername, $username, $password, $dbname);

// Bağlantıyı kontrol etme
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>