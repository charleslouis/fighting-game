<?php 

function loadClass($class){
	require 'classes/'. $class . '.class.php';
}
spl_autoload_register('loadClass');
?>