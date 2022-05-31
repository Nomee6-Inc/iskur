<?php
session_start();
date_default_timezone_set('Europe/Istanbul');
include '../config.php';
$getusername = $_SESSION['username'];
$getworkid = $_GET['id'];
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
}

$result = mysqli_query($conn, "SELECT * FROM employee");
$query = mysqli_query($conn, "SELECT * FROM users WHERE username = '$getusername'");
$result = $query->fetch_assoc();

$isilanlari = $result['isilanlari'];
$isilanlariarray = explode(",", $isilanlari);

$odenenisilanlari = $result['odenenisilanlari'];
$odenenisilanlariarray = explode(",", $odenenisilanlari);

$jsonitems = file_get_contents("../works.json");
$objitemss = json_decode($jsonitems);

$findworkmaasgun = function($id) use ($objitemss) {
    foreach ($objitemss as $role) {
        if ($role->id == $id) return $role->maasgun;
    }
};
$findworkmaas = function($id) use ($objitemss) {
    foreach ($objitemss as $role) {
        if ($role->id == $id) return $role->maas;
    }
};
$findworkclsnsayisi = function($id) use ($objitemss) {
    foreach ($objitemss as $role) {
        if ($role->id == $id) return $role->calisansayisi;
    }
};

if(!strstr($isilanlari, "$getworkid")) {
    header("Location: panel.php");
}

if(!$findworkmaasgun($getworkid) == date("d")) {
    header("Location: panel.php");
}

if (isset($_POST['submit'])) {
$getcardnumber = $_POST['cardnumber'];
$getcardowner = $_POST['cardowner'];
$getcardexpdate = $_POST['mdate']."/".$_POST['ydate'];
$getcardcvv = $_POST['cardcvv'];

$serveredevlet = "localhost";
$useredevlet = "";
$passedevlet = "";
$databaseedevlet = "";
$connedevlet = mysqli_connect($serveredevlet, $useredevlet, $passedevlet, $databaseedevlet);

$query2 = mysqli_query($connedevlet,"SELECT * FROM cards WHERE owner = '$getcardowner'");
$result2 = $query2->fetch_assoc();

$cardnumber = $result2['cardnumber'];
$cardcvv = $result2['cvv'];
$cardend = $result2['end'];
$cardowner = $result2['owner'];
$cardmoney = $result2['money'];
$cardid = $result2['cardid'];

$gettotalamountmaas = $findworkclsnsayisi($getworkid)*$findworkmaas($getworkid);
if($getcardnumber == $cardnumber && $getcardowner == $cardowner && $getcardexpdate == $cardend && $getcardcvv == $cardcvv) {
    if($cardmoney < $gettotalamountmaas) {
        echo "Kartınız da ki para bu işlem için yetersiz!";
    } else {
    $newcardmoney = $cardmoney - $gettotalamountmaas;
    $sqlcard = "UPDATE cards SET money = '$newcardmoney' WHERE cardid = '$cardid'";
    $run_query = mysqli_query($connedevlet, $sqlcard);
    $getdate = date("M/d/Y H:i");
    $sql = "INSERT INTO maasodemeler (workid, date, cardid)
					VALUES ('$getworkid', '$getdate', '$cardid')";
	$run_query1 = mysqli_query($conn, $sql);
	
	if(!$odenenisilanlari || $odenenisilanlari == "") {
        $newisilanlari = $getworkid;
    } else {
        $newisilanlari = "$odenenisilanlari,$getworkid";
    }
	
	$sql3 = "UPDATE users SET odenenisilanlari = '$newisilanlari' WHERE username = '$getusername'";
	$run_query2 = mysqli_query($conn, $sql3);
	
	$sqlmaasyat = "SELECT * FROM users";
    $querymaasyat = mysqli_query($conn, $sqlmaasyat);
	
	while($row = mysqli_fetch_assoc($querymaasyat)){
	    $userworkw = $row['work'];
	    $useriskconnid = $row['dvacid'];
	    if($userworkw == $getworkid) {
            $query3131 = mysqli_query($connedevlet, "SELECT * FROM users WHERE iskconnid = '$useriskconnid'");
            $result3131 = $query3131->fetch_assoc();
            $getumoney = $result3131['money'];
            $findumaas = $findworkmaas($getworkid);
            $findnewmoneytotalu = $findumaas+$getumoney;
            
            $sql3131 = "UPDATE users SET money = '$findnewmoneytotalu' WHERE iskconnid = '$useriskconnid'";
            $run_query3131 = mysqli_query($connedevlet, $sql3131);
	    } else {
	        //
	    }
    }
	
	
    if($run_query && $run_query1 && $run_query2) {
        header("Location: panel.php");
        echo "Ödeme Gerçekleştirildi!";
    } else {
        echo "Bir hata meydana geldi!";
    }
    }
} else {
    echo "Girdiğiniz kart bilgileri yanlış!";
};
};
?>

<!doctype html>
<html lang="tr">
  <head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <title>İşveren Portalı | NOMEE6 İŞKUR</title>
    <link href="../dist/css/tabler.min.css" rel="stylesheet"/>
    <link href="../dist/css/tabler-flags.min.css" rel="stylesheet"/>
    <link href="../dist/css/tabler-payments.min.css" rel="stylesheet"/>
    <link href="../dist/css/tabler-vendors.min.css" rel="stylesheet"/>
    <link href="../dist/css/demo.min.css" rel="stylesheet"/>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <meta property="og:title" content="NOMEE6 İŞKUR" />
    <meta property="og:url" content="https://iskur.nomee6.xyz" />
    <meta property="og:image" content="https://nomee6.xyz/assets/A.png" />
    <meta property="og:description" content="İş mi arıyorsunuz? Hemen girin ve kolayca işinizi bulun." />
	<?php 
	$username = $_SESSION['username'];
	echo("
	<!-- Matomo -->
	  <script>
		var _paq = window._paq = window._paq || [];
		_paq.push(['trackPageView']);
		_paq.push(['enableLinkTracking']);
		_paq.push(['setUserId', '$username']);
		_paq.push(['enableHeartBeatTimer']);
		(function() {
			var u=\"https://matomo.aliyasin.org/\";
		  _paq.push(['setTrackerUrl', u+'matomo.php']);
		  _paq.push(['setSiteId', '15']);
		  var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
		  g.async=true; g.src=u+'matomo.js'; s.parentNode.insertBefore(g,s);
		})();
	  </script>
	  <!-- End Matomo Code -->
	");
	?>
  </head>
  <body>
    <div class="page">
      <aside class="navbar navbar-vertical navbar-expand-lg navbar-transparent">
        <div class="container-fluid">
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu">
            <span class="navbar-toggler-icon"></span>
          </button>
          <h1 class="navbar-brand navbar-brand-autodark">
            <a href=".">
              <img src="../static/logo.svg" width="110" height="32" alt="İŞKUR" class="navbar-brand-image">
            </a>
          </h1>
          <div class="navbar-nav flex-row d-lg-none">
            <a href="?theme=dark" class="nav-link px-0 hide-theme-dark" title="Enable dark mode" data-bs-toggle="tooltip" data-bs-placement="bottom">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 3c.132 0 .263 0 .393 0a7.5 7.5 0 0 0 7.92 12.446a9 9 0 1 1 -8.313 -12.454z" /></svg>
            </a>
            <a href="?theme=light" class="nav-link px-0 hide-theme-light" title="Enable light mode" data-bs-toggle="tooltip" data-bs-placement="bottom">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="4" /><path d="M3 12h1m8 -9v1m8 8h1m-9 8v1m-6.4 -15.4l.7 .7m12.1 -.7l-.7 .7m0 11.4l.7 .7m-12.1 -.7l-.7 .7" /></svg>
            </a>
          </div>
          <div class="collapse navbar-collapse" id="navbar-menu">
            <ul class="navbar-nav pt-lg-3">
              <li class="nav-item">
                <a class="nav-link" href="../index.php" >
                  <span class="nav-link-icon d-md-none d-lg-inline-block">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><polyline points="5 12 3 12 12 3 21 12 19 12" /><path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7" /><path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6" /></svg>
                  </span>
                  <span class="nav-link-title">
                    Ana Sayfa
                  </span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="../kullanciportal/panel.php" >
                  <span class="nav-link-icon d-md-none d-lg-inline-block">
	                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="7" r="4" /><path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /></svg>
                  </span>
                  <span class="nav-link-title">
                    Kullanıcı Paneli
                  </span>
                </a>
              </li>
              <li class="nav-item active">
                <a class="nav-link" href="../isveren/" >
                <span class="nav-link-icon d-md-none d-lg-inline-block">
	                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="9" /><circle cx="12" cy="10" r="3" /><path d="M6.168 18.849a4 4 0 0 1 3.832 -2.849h4a4 4 0 0 1 3.834 2.855" /></svg>
                  </span>
                  <span class="nav-link-title">
                    İşveren Portalı
                  </span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="../isbul.php" >
                  <span class="nav-link-title">
                    İş Bul
                  </span>
                </a>
              </li>
            </ul>
          </div>
        </div>
      </aside>
      <div class="page-wrapper">
        <div class="container-xl">
          <div class="page-header d-print-none">
            <div class="row align-items-center">
              <div class="col">
                <div class="page-pretitle">
                  NOMEE6 İŞKUR
                </div>
                <h2 class="page-title">
                  Çalışan Ödemesi
                </h2>
              </div>
            </div>
          </div>
        </div>
        <div class="page-body">
           <div class="container-xl">
             <form enctype="multipart/form-data" action="" method="POST">
              <div class="col-12">
                <div class="card">
                  <div class="card-body">
                    <div class="mb-3">
                      <div class="form-label">Kart Numarası</div>
                      <input id="cardnumber" name="cardnumber" type="text" class="form-control" autocomplete="off" required/>
                    </div>
                    <div class="mb-3">
                      <div class="form-label">Kart Sahibi</div>
                      <input id="cardowner" name="cardowner" type="text" class="form-control" required>
                    </div>
                    <div class="row">
                      <div class="col-8">
                        <div class="mb-3">
                          <label class="form-label">Son Kullanım Tarihi</label>
                          <div class="row g-2">
                            <div class="col">
                              <select id="mdate" name="mdate" class="form-select" required>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                                <option value="8">8</option>
                                <option value="9">9</option>
                                <option value="10">10</option>
                                <option value="11">11</option>
                                <option value="12">12</option>
                              </select>
                            </div>
                            <div class="col">
                              <select id="ydate" name="ydate" class="form-select" required>
                                <option value="22">2022</option>
                                <option value="23">2023</option>
                                <option value="24">2024</option>
                                <option value="25">2025</option>
                                <option value="26">2026</option>
                                <option value="27">2027</option>
                                <option value="28">2028</option>
                                <option value="29">2029</option>
                                <option value="30">2030</option>
                              </select>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col">
                        <div class="mb-3">
                          <div class="form-label">CVV</div>
                          <input id="cardcvv" name="cardcvv" type="number" class="form-control" maxlength="3" required>
                        </div>
                      </div>
                    </div>
                    <div class="mt-2">
                      <button name="submit" class="btn btn-primary w-100">Ödemeyi Gerçekleştir</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </form>
    </div>
</div>
        <footer class="footer footer-transparent d-print-none">
          <div class="container-xl">
            <div class="row text-center align-items-center flex-row-reverse">
              <div class="col-lg-auto ms-lg-auto">
                <ul class="list-inline list-inline-dots mb-0">
                  <li class="list-inline-item"><a href="https://github.com/Nomee6-Inc" target="_blank" class="link-secondary" rel="noopener">Kaynak Kodu</a></li>
                  </li>
                </ul>
              </div>
              <div class="col-12 col-lg-auto mt-3 mt-lg-0">
                <ul class="list-inline list-inline-dots mb-0">
                  <li class="list-inline-item">
                    Copyright &copy; 2022
                    <a href="." class="link-secondary">NOMEE6 Inc</a>.
                    Tüm hakları saklıdır.
                  </li>
                  <li class="list-inline-item">
                    <a href="." class="link-secondary" rel="noopener">
                      Bu site tamamen mizah amaçlı yapılmıştır.
                    </a>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </footer>
      </div>
    </div>
    <script src="../dist/libs/apexcharts/dist/apexcharts.min.js"></script>
    <script src="../dist/libs/jsvectormap/dist/js/jsvectormap.min.js"></script>
    <script src="../dist/libs/jsvectormap/dist/maps/world.js"></script>
    <script src="../dist/js/tabler.min.js"></script>
    <script src="../dist/js/demo.min.js"></script>
  </body>
</html>
