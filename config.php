<?php 

$edevletapitoken = "";

$server = "localhost";
$user = "";
$pass = "";
$database = "";

$conn = mysqli_connect($server, $user, $pass, $database);

try {
     $db = new PDO("mysql:host=localhost;dbname=$database;charset=utf8", "$user", "$pass");
} catch ( PDOException $e ){
     print $e->getMessage();
}

if (!$conn) {
    die("<script>alert('Veritabanına bağlanırken bir hata oluştu.')</script>");
}
?>
