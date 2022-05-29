<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
} else if (isset($_SESSION['username'])) {
    header("Location: panel.php");
}
?>