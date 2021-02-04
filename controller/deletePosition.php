<?php
	require_once '../connect.php';
	$db->deletePosition($_GET['id']);
	header('Location: '. 'http://' . $_SERVER['HTTP_HOST'] . '?page=2');
?>