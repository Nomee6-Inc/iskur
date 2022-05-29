<?php
session_start();
include '../config.php';
$getusername = $_SESSION['username'];
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
} else {
    $query = mysqli_query($conn, "SELECT * FROM users WHERE username = '$getusername'");
    $result = $query->fetch_assoc();
    $work = $result['work'];
    $jsonitem = file_get_contents("../works.json");
    $objitems = json_decode($jsonitem);
    $findcalisansayisi = function($id) use ($objitems) {
        foreach ($objitems as $clsnsayisifind) {
            if ($clsnsayisifind->id == $id) return $clsnsayisifind->calisansayisi;
        }
        return false;
    };
    $getclsnsysi = $findcalisansayisi($work);
    $datao = file_get_contents('../works.json');

    $json_arro = json_decode($datao, true);

    foreach ($json_arro as $keyo => $valueo) {
        if ($valueo['id'] == "$work") {
            $json_arro[$keyo]['calisansayisi'] = $getclsnsysi-1;
        }
    }
    file_put_contents('../works.json', json_encode($json_arro));
    $sql3 = "UPDATE users SET work = '' WHERE username = '$getusername'";
    $run_query1 = mysqli_query($conn, $sql3);
    $sql31 = "UPDATE users SET workgirisdate = '' WHERE username = '$getusername'";
    $run_query2 = mysqli_query($conn, $sql31);
    if($run_query1 && $run_query2) {
        header("Location: https://iskur.nomee6.xyz/kullanciportal");
    } else {
        echo "Bir hata oluştu!";
    }
}
?>