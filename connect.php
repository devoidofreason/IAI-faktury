<?php
	session_start();
	if(!isset($_SESSION['page']))
		$_SESSION['page'] = '1';
	if(isset($_GET['page']))
		$_SESSION['page'] = $_GET['page'];
	// session, pages
	require_once 'dbmodel.php';
	$db = new DBModel();
?>