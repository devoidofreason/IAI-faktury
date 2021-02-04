<?php
	require_once '../connect.php';
	$pozycje = array();
	foreach ($_GET['pozycje'] as $key => $value)
		if($value != '')
			$pozycje[] = array($key => $value);
	$db->insertFaktura($_GET['imie'], $_GET['nazwisko'], $_GET['adres'], $_GET['kod'], $_GET['miasto'], $_GET['nip'], $_GET['telefon'], $_GET['email'], $pozycje);
	header('Location: '. 'http://' . $_SERVER['HTTP_HOST'] . '?page=3');
?>