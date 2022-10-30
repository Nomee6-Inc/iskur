<?php
session_start();
date_default_timezone_set('Europe/Istanbul');
include '../config.php';
$getuserid = $_SESSION['user_id'];
if (!isset($_SESSION['user_id'])) {
    header("Location: ../kullanciportal/login.php");
} else {
$user_q = $db->query("SELECT * FROM users WHERE user_id = '{$getuserid}'",PDO::FETCH_ASSOC);
$user_q_query = $user_q->fetch(PDO::FETCH_ASSOC);

if(isset($_POST['submit'])){
$post_get_amount = $_POST['amount'];
$post_get_card_number = str_replace(" ", "",$_POST['card_number']);
$post_get_card_exp_date = $_POST['month_date']."/".$_POST['year_date'];
$post_get_card_cvv = $_POST['cvv'];
if(is_numeric($post_get_amount)) {
$ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,"https://bank.nomee6.xyz/api/v1/create_payment");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,
            "client_id=&secret=&card_number=$post_get_card_number&card_expire=$post_get_card_exp_date&card_cvv=$post_get_card_cvv&amount=$post_get_amount");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec($ch);
        curl_close ($ch);
  		$json_response = json_decode($server_output);
  		$_pay_status = $json_response->status_message;
  		$_pay_code = $json_response->data;
  		if($_pay_status == "Error: User money not enough.") {
        	echo '<script>alert("Kartta ki paranız yeterli değil!")</script>';
        } else if($_pay_status == "Error: User card not found.") {
        	echo '<script>alert("Kart bilgileriniz geçerli değil!")</script>';
        } else if($_pay_status == "success") {
          	$precodegenerate = sha1(rand());
          	$sql1 = $db->prepare("INSERT INTO unapplied_funds SET
				user_id = ?,
				amount = ?,
                pay_code = ?");
			$run_query1 = $sql1->execute(array(
			     $getuserid, $post_get_amount, $_pay_code
			));
            if($run_query1) {
			    header("Refresh:0 url=https://api.nomee6.xyz/v2/pay?pay_code=$_pay_code");
            } else {
                echo "Bir hata oluştu!";
            }   
        } else {
        	echo '<script>alert("Ödemeniz banka kuruluşunuz tarafından reddedildi!")</script>';
        }
} else {
	echo "<script>alert(\"Girdiğiniz tutar geçersiz!\")</script>";
}
};
}
?>

<!doctype html>
<html lang="tr">
  <head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <title>Bakiye Ekle | Nomee6 İşkur</title>
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
	echo("
	<!-- Matomo -->
	  <script>
		var _paq = window._paq = window._paq || [];
		_paq.push(['trackPageView']);
		_paq.push(['enableLinkTracking']);
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
            <a href="?theme=dark" class="nav-link px-0 hide-theme-dark" title="Koyu Temaya geç" data-bs-toggle="tooltip" data-bs-placement="bottom">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 3c.132 0 .263 0 .393 0a7.5 7.5 0 0 0 7.92 12.446a9 9 0 1 1 -8.313 -12.454z" /></svg>
            </a>
            <a href="?theme=light" class="nav-link px-0 hide-theme-light" title="Açık Temaya geç" data-bs-toggle="tooltip" data-bs-placement="bottom">
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
                  İşveren Portalı
                </h2>
              </div>
            </div>
          </div>
        </div>
	<div class="page-body">
          <div class="container-xl">
            <div class="row row-cards">
		 			<form method="POST" action="" class="col-12">
                            <div class="card">
                              <div class="card-body">
								<div class="mb-3">
                                  <div class="form-label">Miktar</div>
                                  <input type="number" name="amount" class="form-control" required/>
                                </div>
                                <div class="mb-3">
                                  <div class="form-label">Kart numarası</div>
                                  <input type="text" name="card_number" class="form-control" data-mask="0000 0000 0000 0000" data-mask-visible="true" autocomplete="off" required/>
                                </div>
                                <div class="row">
                                  <div class="col-8">
                                    <div class="mb-3">
                                      <label class="form-label">Bitiş Tarihi</label>
                                      <div class="row g-2">
                                        <div class="col">
                                          <select class="form-select" name="month_date" required>
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
                                          <select class="form-select" name="year_date" required>
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
                                      <input name="cvv" type="number" class="form-control" required>
                                    </div>
                                  </div>
                                </div>
                                <div class="mt-2">
                                  <button class="btn btn-primary w-100" name="submit">
                                    Bakiye Ekle
                                  </button>
                                </div>
                              </div>
                            </div>
                          </form>
						</div>
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
        </footer>
      </div>
    </div>
    <script src="../dist/js/tabler.min.js"></script>
    <script src="../dist/js/demo.min.js"></script>
  </body>
</html>
