<?php
session_start();
include_once 'config.php';
$getusername = $_SESSION['username'];
$getworkid = $_GET['id'];
if(!$getworkid) {
    header("Location: isbul.php");
};
$jsonitem = file_get_contents("works.json");
$objitems = json_decode($jsonitem);
$findcalisansayisi = function($id) use ($objitems) {
foreach ($objitems as $clsnsayisifind) {
    if ($clsnsayisifind->id == $id) return $clsnsayisifind->calisansayisi;
}
return false;
};
$findmaxcalisan = function($id) use ($objitems) {
foreach ($objitems as $clsnsayisifind) {
    if ($clsnsayisifind->id == $id) return $clsnsayisifind->maxcalisan;
}
return false;
};
$getclsnsysi = $findcalisansayisi($getworkid);
$getmaxcalisan = $findmaxcalisan($getworkid);
if (!isset($_SESSION['username'])) {
    header("Location: kullanciportal/login.php");
} else {
if($getclsnsysi+1 > $getmaxcalisan) {
    echo "Bu iş başvurulara kapanmış veya maximum çalışan sayısına ulaşmıştır.";
} else {
$query = mysqli_query($conn,"SELECT * FROM users WHERE username = '$getusername'");
$result = $query->fetch_assoc();
$workid = $result['work'];
if($workid != "") {
echo "Zaten bir işin bulunuyor! Önce ondan çıkış yapmalısın.";
} else {
$getdate = date("M/d/Y");
$sqlis = "UPDATE users SET work = '$getworkid' WHERE username = '$getusername'";
$run_queryis = mysqli_query($conn, $sqlis);
$sqlis2 = "UPDATE users SET workgirisdate = '$getdate' WHERE username = '$getusername'";
$run_queryis2 = mysqli_query($conn, $sqlis2);
    $datao = file_get_contents('works.json');

    $json_arro = json_decode($datao, true);

    foreach ($json_arro as $keyo => $valueo) {
        if ($valueo['id'] == "$getworkid") {
            $json_arro[$keyo]['calisansayisi'] = $getclsnsysi+1;
        }
    }
    file_put_contents('works.json', json_encode($json_arro));
if($run_queryis && $run_queryis2) {
    header("Location: kullanciportal/");
} else {
    echo "Bir hata oluştu!";
}}
}};

?>
