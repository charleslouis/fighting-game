<?php 
	require 'connect.php';
	require 'functions.php';

	$perso = new Personnage(array(
	  'nom' => 'Victor',
	  'forcePerso' => 5,
	  'degats' => 0,
	  'niveau' => 1,
	  'experience' => 0
	));

	var_dump($perso);

	// $db = new PDO('mysql:host=localhost;dbname=fighting_game', 'root', '');
	$db = $connexion;

	$manager = new PersonnagesManager($db);
	     
	$manager->add($perso);

?>