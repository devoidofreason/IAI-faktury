<?php
	require_once '../connect.php';
	$db->insertPosition($_GET['netto'], $_GET['vat'], $_GET['opis']);
	header('Location: '. 'http://' . $_SERVER['HTTP_HOST'] . '?page=2');
?>