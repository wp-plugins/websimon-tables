<?php
/*
includes the content for editing the style of the tables
*/
	if ($_GET['action'] && $_GET['settings'] == 'basic') {
		require_once( 'basic.php' );//Basic settings, select sking
	} elseif ($_GET['action'] && $_GET['settings'] == 'advanced') {
		require_once( 'advanced.php' ); //for editing the custom skin
	} else {
		require_once( 'basic.php' ); //if something goes wrong go to basic.php
	}
?>