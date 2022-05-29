<?php
session_start();
include '../config.php';
$getlogincode = $_GET['logincode'];

if(!$getlogincode || $getlogincode == "") {
    echo "Oops! Giriş Kodunuz geçerli değil veya method desteklenmiyor.";
} else {
    $sql = "SELECT * FROM logincodes WHERE code='$getlogincode'";
	$result = mysqli_query($conn, $sql);
	
	$connedevlet = mysqli_connect("localhost", "", "", "");
	
	if ($result->num_rows > 0) {
	$query1 = mysqli_query($conn, "SELECT * FROM logincodes WHERE code = '$getlogincode'");
    $result1 = $query1->fetch_assoc();
    
	$getoldaccid = $result1['oldaccid'];
	$getnewaccid = $result1['accid'];
	
	$sqledvlt = "SELECT * FROM users WHERE iskconnid='$getnewaccid'";
	$resultedvlt = mysqli_query($connedevlet, $sqledvlt);
	
	$sql2 = "SELECT * FROM users WHERE dvacid='$getoldaccid'";
	$result2 = mysqli_query($conn, $sql2);
	if ($result2->num_rows > 0) {
	    $row = mysqli_fetch_assoc($resultedvlt);
	    $sql31 = "UPDATE users SET dvacid = '$getnewaccid' WHERE dvacid = '$getoldaccid'";
        $run_query1 = mysqli_query($conn, $sql31);
        $_SESSION['username'] = $row['username'];
        if($run_query1) {
            header("Location: panel.php");
            echo "İşlem başarılı!";
        } else {
            echo "Bir hata oluştu!";
        }
	} else {
	    $row = mysqli_fetch_assoc($resultedvlt);
	    $edevletusername = $row['username'];
	    $sql9 = "INSERT INTO users (dvacid, username)
					VALUES ('$getnewaccid', '$edevletusername')";
		$registrquery = mysqli_query($conn, $sql9);
		$_SESSION['username'] = $edevletusername;
        if($registrquery) {
            header("Location: panel.php");
            echo "İşlem başarılı!";
        } else {
            echo "Bir hata oluştu!";
        }
	}
	} else {
	    echo "Oops! Giriş kodunuz geçerli değil.";
	}
}

?>