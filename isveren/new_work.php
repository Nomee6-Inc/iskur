<?php
session_start();
$_get_user_id = $_SESSION['user_id'];
include '../config.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: ../kullanciportal/login.php");
} else {

$gnrterandomilanid = rand(100000, 999999);

if (isset($_POST['submit'])) {
$uploaddir = '../workphotos/';
$uploadfile = $uploaddir . "$gnrterandomilanid.png";
$target_file = $uploaddir . basename($_FILES["workphoto"]["name"]);
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
$check = getimagesize($_FILES["workphoto"]["tmp_name"]);
	
if($check !== false) {
  $uploadOk = 1;
} else {
  echo "Yüklemeye çalıştığınız dosya bir resim değil.";
  $uploadOk = 0;
}
if($imageFileType != "png") {
  echo "Üzgünüm, sadece PNG formatları desteklenmektedir.";
  $uploadOk = 0;
}

if ($uploadOk == 0) {
    //
} else {
  if (move_uploaded_file($_FILES["workphoto"]["tmp_name"], $uploadfile)) {
    //
  } else {
    echo "İş fotoğrafı yüklenirken bir hata oluştu.";
  }
}
$get_work_name = htmlentities($_POST['workname']);
$get_work_desc = htmlentities($_POST['workdesc']);
$get_work_maas = $_POST['maas'];
$get_work_firmaname = htmlentities($_POST['firmaname']);
$get_work_maasgun = $_POST['maasgun'];
$get_work_maxcalisan = $_POST['maxcalisan'];
if(is_numeric($get_work_maxcalisan) && is_numeric($get_work_maasgun) && is_numeric($get_work_maas)) {
$get_work_desc = str_replace("&lt;br&gt;","<br>",$get_work_desc);	

$save_work_q_p = $db->prepare("INSERT INTO works SET
workname = ?,
workid = ?,
workdesc = ?,
workthumbnail = ?,
earn = ?,
workcompany = ?,
maxworkers = ?,
workowner = ?,
earndate = ?
");
$save_work_q_p_query = $save_work_q_p->execute(array(
     $get_work_name, $gnrterandomilanid, $get_work_desc, "$gnrterandomilanid.png", $get_work_maas, $get_work_firmaname, $get_work_maxcalisan, $_get_user_id, $get_work_maasgun
));	
if($save_work_q_p_query) {
	header("Refresh:1 url=panel.php");
	echo "<script>alert(\"İşlem başarılı\")</script>";
} else {
	echo "<script>alert(\"Bir hata oluştu!\")</script>";
}
} else {
	echo "<script>alert(\"Girdiğiniz bilgiler geçersiz!\")</script>";
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
    <title>İş İlanı Oluştur | Nomee6 İşkur</title>
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
            <a href="?theme=dark" class="nav-link px-0 hide-theme-dark" title="Koyu temaya geç" data-bs-toggle="tooltip" data-bs-placement="bottom">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 3c.132 0 .263 0 .393 0a7.5 7.5 0 0 0 7.92 12.446a9 9 0 1 1 -8.313 -12.454z" /></svg>
            </a>
            <a href="?theme=light" class="nav-link px-0 hide-theme-light" title="Açık temaya geç" data-bs-toggle="tooltip" data-bs-placement="bottom">
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
               <div class="col-12">
                 <div class="card">
                    <div class="card-body">
                        <h3 class="card-title">İş İlanı Oluştur</h3>
                        <div class="col-md-6">
              <div class="card">
                <div class="card-body">
                  <form enctype="multipart/form-data" action="" method="POST">
                    <div class="form-group mb-3">
                      <label class="form-label">Firma Adınız</label>
                      <div>
                        <input name="firmaname" type="text" class="form-control" maxlength="18" required>
                      </div>
                    </div>
                    <div class="form-group mb-3">
                      <label class="form-label">İş İlanınızın İsmi</label>
                      <div>
                        <input name="workname" type="text" class="form-control" maxlength="18" required>
                        <small class="form-hint">
                          İş ilanınızın başlığı olarak gözükecektir.
                        </small>
                      </div>
                    </div>
                    <div class="form-group mb-3">
                      <label class="form-label">İş İlanınızın Açıklaması</label>
                      <div>
                        <textarea name="workdesc" type="text" class="form-control" rows="6" required></textarea>
                        <small class="form-hint">
                          İş ilanınızın detaylarında detaylı açıklama olarak gözükecektir. Alt satıra geçmek istediğiniz yere &lt;br&gt; etiketini yerleştirebilirsiniz.
                        </small>
                      </div>
                    </div>
                    <div class="form-group mb-3">
                      <label class="form-label">Maaş</label>
                      <div>
                        <input name="maas" type="number" class="form-control" required>
                        <small class="form-hint">
                          Aylık olarak ödeyeceğiniz maaş miktarıdır.
                        </small>
                      </div>
                    </div>
                    <div class="form-group mb-3">
                      <label class="form-label">Alınacak İşçi Sayısı</label>
                      <div>
                        <input name="maxcalisan" type="number" class="form-control" required>
                        <small class="form-hint">
                          İlanınıza belirlediğiniz çalışan sayısından daha fazla kişi başvuramaz.
                        </small>
                      </div>
                    </div>
                    <div class="form-group mb-3">
                        <div class="form-label">İş Resmi</div>
                        <input name="workphoto" type="file" class="form-control" required/>
                    </div>
                    <div class="form-group mb-3">
                      <label class="form-label">Maaş Günü Seçin</label>
                      <div>
                        <select name="maasgun" class="form-select" required>
                          <option value="01">01</option>
                          <option value="02">02</option>
                          <option value="03">03</option>
                          <option value="04">04</option>
                          <option value="05">05</option>
                          <option value="06">06</option>
                          <option value="07">07</option>
                          <option value="08">08</option>
                          <option value="09">09</option>
                        </select>
                      </div>
                    </div>
                    </div>
                    <div class="form-footer">
                      <button name="submit" type="submit" class="btn btn-primary">İş İlanı Oluştur</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
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
