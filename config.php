<?php 

$server = "localhost";
$user = "";
$pass = "";
$database = "";

$conn = mysqli_connect($server, $user, $pass, $database);

if (!$conn) {
    die("<script>alert('Veritabanına bağlanırken bir hata oluştu.')</script>");
}
?>