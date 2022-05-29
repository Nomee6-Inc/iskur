<?php
include_once '../config.php';
session_start();
if (isset($_SESSION['username'])) {
    header("Location: panel.php");
};

$rand = md5(rand());
$getlogincode = $_GET['logincode'];
if(!$getlogincode || $getlogincode == "" || strlen($_GET['logincode']) != 32) {
    header("Location: login.php?logincode=$rand");
}


if (isset($_POST['submit'])) {
    $sql = "INSERT INTO logincodes (code)
					VALUES ('$getlogincode')";
	$run_query = mysqli_query($conn, $sql);
    if($run_query) {
        header("Location: https://devlet.nomee6.xyz/api/v1/iskur/login.php?logincode=$getlogincode");
        echo "Yönlendiriliyorsunuz...";
    } else {
        echo "E-Devlet API Hizmetine bağlanılırken bir hata oluştu! Lütfen daha sonra tekrar deneyiniz.";
    }
}
?>
<!doctype html>
<html lang="tr">
  <head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <title>NOMEE6 İŞKUR GİRİŞ</title>
    <link href="../dist/css/tabler.min.css" rel="stylesheet"/>
    <link href="../dist/css/tabler-flags.min.css" rel="stylesheet"/>
    <link href="../dist/css/tabler-payments.min.css" rel="stylesheet"/>
    <link href="../dist/css/tabler-vendors.min.css" rel="stylesheet"/>
    <link href="../dist/css/demo.min.css" rel="stylesheet"/>
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
  <body class=" border-top-wide border-primary d-flex flex-column">
  <form enctype="multipart/form-data" action="" method="POST">
    <div class="page page-center">
      <div class="container-tight py-4">
        <div class="text-center mb-4">
          <a href="https://iskur.nomee6.xyz" class="navbar-brand navbar-brand-autodark"><img src="../static/logo.svg" height="36" alt=""></a>
        </div>
        <div class="card card-md">
          <div class="card-body">
            <h2 class="card-title text-center mb-4">İşkur Hesabına Giriş Yap</h2>
            <div class="form-footer">
              <button name="submit" class="btn btn-primary w-100">E-Devlet Hesabınla devam et</button>
            </div>
          </div>
      </div>
    </div>
</form>
    <script src="../dist/js/tabler.min.js"></script>
    <script src="../dist/js/demo.min.js"></script>
  </body>
</html>