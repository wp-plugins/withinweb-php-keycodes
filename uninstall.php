<?php

//Uninstall script

if ( !defined( 'WP_UNINSTALL_PLUGIN'  ) )
	exit();
	
//Delete option from options table
delete_option( 'withinweb_keycodes_options' );

//Remove any o9ther options and tables

?>