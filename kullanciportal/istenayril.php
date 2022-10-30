<?php
session_start();
include '../config.php';
$get_user_id = $_SESSION['user_id'];

function getnowDate(){
$gun=date("d");
$ay=date("m")-1;
$yil=date("Y");
$aylar=Array("Ocak","Şubat","Mart","Nisan","Mayıs","Haziran","Temmuz","Ağustos","Eylül","Ekim","Kasım","Aralık");
$get_clock = date("H:i");
return "$gun $aylar[$ay] $yil, $get_clock";
}
$getnow_date = getnowDate();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
} else {
	$worker_q = $db->query("SELECT * FROM workers WHERE user_id = '{$get_user_id}' AND status = 'active'",PDO::FETCH_ASSOC);
	$worker_q_query = $worker_q->fetch(PDO::FETCH_ASSOC);
	$worker_q_count = $worker_q -> rowCount();
	
	$_get_worker_id = $worker_q_query['id'];
	
	if($worker_q_count > 0) {
		$worker_update_q = $db->prepare("UPDATE workers SET
			status = :status,
			leave_date = :leavedate
			WHERE id = :id");
		$worker_update_q_query = $worker_update_q->execute(array(
     		"status" => "inactive",
			"leavedate" => "$getnow_date",
     		"id" => $_get_worker_id
		));
		
		if($worker_update_q_query) {
        	header("Location: https://iskur.nomee6.xyz/kullanciportal");
    	} else {
    	    echo "Bir hata oluştu!";
    	}
	} else {
		header("Location: https://iskur.nomee6.xyz/kullanciportal");
	}
}
?>
