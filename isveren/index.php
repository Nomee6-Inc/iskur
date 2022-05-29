<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: https://iskur.nomee6.xyz/kullanciportal/login.php");
} else if (isset($_SESSION['username'])) {
    header("Location: panel.php");
}
?>