<?php
/*
includes the content for editing the style of the tables
*/
	if (isset($_GET['action']) && isset($_GET['settings']) && $_GET['settings'] == 'basic') 
	{
		//Basic settings, select skin
		require_once( 'basic.php' );
	} 
	elseif (isset($_GET['action']) && isset($_GET['settings']) && $_GET['settings'] == 'advanced') 
	{
		//for editing the custom skin
		require_once( 'advanced.php' ); 
	} else 
	{
		//if something goes wrong go to basic.php
		require_once( 'basic.php' ); 
	}
?>