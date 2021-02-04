<?php
	require_once '../connect.php';
	$db->deleteFaktura($_GET['id']);
	header('Location: '. 'http://' . $_SERVER['HTTP_HOST'] . '?page=3');
?>