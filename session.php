<?php 
  session_start(); // On appelle session_start() APRÈS avoir enregistré l'autoload.
   
  if (isset($_GET['deconnexion']))
  {
    session_destroy();
    header('Location: .');
    exit();
  }
   
  if (isset($_SESSION['perso'])) // Si la session perso existe, on restaure l'objet.
  {
    $perso = $_SESSION['perso'];
  }
?>