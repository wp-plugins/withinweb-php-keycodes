<?php
/*
Plugin Name: WithinWeb PHP-KeyCodes
Plugin URI:  http://www.withinweb.com/wordpresskeycodes/
Description: Sell software licence codes, key codes or PIN numbers using PayPal IPN.
Author: Paul Gibbs
Version: 1.0.0
Author URI: http://www.withinweb.com/wordpresskeycodes/
*/

//==================================================================================================



//--------------------------------------------------------------
//Activate the plugin
register_activation_hook(__FILE__, 'withinweb_keycodes_install' );

//--------------------------------------------------------------
//Deactivate the plugin
register_deactivation_hook(__FILE__, 'withinweb_keycodes_deactivate' );

//--------------------------------------------------------------
//Create the menus
add_action( 'admin_menu', 'withinweb_keycodes_create_menu' );

//Create process hooks
add_action( 'admin_post_withinweb_keycodes_settings', 'process_withinweb_keycodes_settings' );
add_action( 'admin_post_withinweb_keycodes_environment', 'process_withinweb_keycodes_environment' );
add_action( 'admin_post_withinweb_keycodes_smtp', 'process_withinweb_keycodes_smtp' );
add_action( 'admin_post_withinweb_keycodes_createitem', 'process_withinweb_keycodes_createitem' );
add_action( 'admin_post_withinweb_keycodes_edititem', 'process_withinweb_keycodes_edititem' );
add_action( 'admin_post_withinweb_keycodes_localtest', 'process_withinweb_keycodes_localtest' );
add_action( 'admin_post_withinweb_keycodes_salesdetails', 'process_withinweb_keycodes_salesdetails' );


//Custom post type of item
//add_action( 'init', 'create_post_type' );


//Create session
//http://silvermapleweb.com/using-the-php-session-in-wordpress/
add_action('init', 'myStartSession', 1);		//the 1 is the highest priority
add_action('wp_logout', 'myEndSession');
add_action('wp_login', 'myEndSession');

function myStartSession() {
    if(!session_id()) {
        session_start();
    }
}

function myEndSession() {
    session_destroy ();
}




//==================================================================================================
/**
* Create the menus
*/
function withinweb_keycodes_create_menu() {	
	//main menu
	add_menu_page( 'PHP-KeyCodes', 'KeyCodes', 'manage_options', __FILE__, 'withinweb_keycodes_help_page');	
	
	//sub menu
	add_submenu_page(__FILE__, 'Setting for PHP-KeyCodes', 'Settings', 'manage_options', __FILE__.'_settings', 'withinweb_keycodes_settings_page' );
	add_submenu_page(__FILE__, 'Create KeyCode Item', 'Create Item', 'manage_options', __FILE__.'_createitem', 'withinweb_keycodes_createitem_page' );
	add_submenu_page(__FILE__, 'Create KeyCode List of items', 'Item List', 'manage_options', __FILE__.'_listitems', 'withinweb_keycodes_listitems_page' );	
	add_submenu_page(__FILE__, 'PHP-KeyCodes Sales', 'Sales', 'manage_options', __FILE__.'_sales', 'withinweb_keycodes_sales_page' );
	add_submenu_page(__FILE__, 'Test an item', 'Local Test', 'manage_options', __FILE__.'_localtest', 'withinweb_keycodes_localtest_page' );
	add_submenu_page(__FILE__, 'Help with PHP-KeyCodes', 'Help', 'manage_options', __FILE__.'_help', 'withinweb_keycodes_help_page' );
	add_submenu_page(__FILE__, 'About PHP-KeyCodes', 'About', 'manage_options', __FILE__.'_about', 'withinweb_keycodes_about_page' );
			
	//add_submenu_page(__FILE__, 'Uninstall PHP-KeyCodes', 'Uninstall', 'manage_options', __FILE__.'_uninstall', 'withinweb_keycodes_uninstall_page' );	

	// This is the hidden page for edit items
	// It is not displayed as part of the menu system
    add_submenu_page(null, 'Edit item page', 'Edit item page', 'manage_options', __FILE__.'_edititem', 'process_withinweb_keycodes_edititem_page');
	
	// This is the hidden page for edit items
	// It is not displayed as part of the menu system
    add_submenu_page(null, 'Sales details page', 'Sales details page', 'manage_options', __FILE__.'_salesdetails', 'process_withinweb_keycodes_salesdetails_page');
	
}

/**
* Install plugin
*/
function withinweb_keycodes_install() {
	if (version_compare( get_bloginfo( 'version' ), '3.1', '<' ) ) {
	
        	echo "<p>Plugin requires WordPress 3.1 or higher</p>";
        	exit;
	
			//deactivate_plugins( basename( __FILE__ ) );		//deactivate the plugin	
	}
	else
	{
   		if ( get_option( 'withinweb_keycodes_op_array' ) === false )
   		{
      		$options_array['withinweb_keycodes_admin_email'] = 'admin@somewhere.com';
      		$options_array['withinweb_keycodes_paypal_email'] = '';
	  		$options_array['withinweb_keycodes_cancel_url'] = '';
	  		$options_array['withinweb_keycodes_return_url'] = '';	
			$options_array['withinweb_keycodes_ipn_url'] = plugins_url( 'ipn/confirm.php', __FILE__ );
      		add_option( 'withinweb_keycodes_op_array', $options_array );
   		}		
		
		if ( get_option( 'withinweb_keycodes_environment_array' ) == false )
		{		
			$environment_array['withinweb_keycodes_paypal_environment'] = '';
      		add_option( 'withinweb_keycodes_environment_array', $environment_array );
		}	
		
		if ( get_option( 'withinweb_keycodes_smtp_array' ) == false )
		{		
			$smtp_array['withinweb_keycodes_smtp_use'] = 'false';
			$smtp_array['withinweb_keycodes_smtp_host'] = '';
			$smtp_array['withinweb_keycodes_smtp_username'] = '';
			$smtp_array['withinweb_keycodes_smtp_password'] = '';
      		add_option( 'withinweb_keycodes_smtp_array', $smtp_array );
		}		
		
		withinweb_keycodes_install_saleshistory_table();
		withinweb_keycodes_install_items_table();
		//withinweb_keycodes_install_data();
		
	}
}


// Variables to identify version of table
// See http://codex.wordpress.org/Creating_Tables_with_Plugins to see how
// you handle versions of tables to update the structure if there is a change.
global $wpdb;
global $withinweb_keycodes_db_version;


/**
Install the saleshistory table
http://codex.wordpress.org/Creating_Tables_with_Plugins
*/
function withinweb_keycodes_install_saleshistory_table() {

	global $wpdb;

   	$table_name = $wpdb->prefix . "keycodes_saleshistory";
      

    //You have to put each field on its own line in your SQL statement.
    //You have to have two spaces between the words PRIMARY KEY and the definition of your primary key.
    //You must use the keyword KEY rather than its synonym INDEX and you must include at least one KEY.
	//Example of sql:
 	//$sql = "CREATE TABLE " . $table . " (
     //         id INT NOT NULL AUTO_INCREMENT,
     //         name VARCHAR(100) NOT NULL DEFAULT '',
     //         email VARCHAR(100) NOT NULL DEFAULT '',
     //         UNIQUE KEY id (id)
     //         );";	  
	  
   $sql = "CREATE TABLE IF NOT EXISTS $table_name (
	recid INT NOT NULL AUTO_INCREMENT,	
	receiver_email VARCHAR( 100 )  NOT NULL ,
	item_name VARCHAR( 100 )  NOT NULL ,
	item_number VARCHAR( 50 )  NOT NULL ,
	payment_status VARCHAR( 50 ) , 	
	mc_gross VARCHAR( 10 )  NOT NULL ,		
	payer_email VARCHAR( 100 )  NOT NULL ,			
	txn_type VARCHAR( 50 ) ,		
	txn_id VARCHAR( 100 ) , 
	mc_currency VARCHAR(10) NOT NULL , 
	completed  DATETIME NOT NULL ,
	quantity INT default '1' NOT NULL ,
	licencecodes TEXT ,
	business VARCHAR(255),
	receiver_id VARCHAR(255),
	invoice VARCHAR(255),
	custom TEXT,
	memo TEXT,
	tax VARCHAR(255),
	option_name1 VARCHAR(255),
	option_selection1 VARCHAR(255),
	option_name2 VARCHAR(255),
	option_selection2 VARCHAR(255),
	num_cart_items VARCHAR(255),
	mc_fee VARCHAR(255),
	payment_date VARCHAR(255),
	payment_type VARCHAR(255),
	first_name VARCHAR(255),
	last_name VARCHAR(255),
	payer_business_name VARCHAR(255),
	address_name VARCHAR(255),
	address_street VARCHAR(255),
	address_city VARCHAR(255),
	address_state VARCHAR(255),
	address_zip VARCHAR(255),
	address_country VARCHAR(255),
	address_status VARCHAR(255),
	payer_id VARCHAR(255),
	payer_status VARCHAR(255),
	notify_version VARCHAR(255),
	verify_sign VARCHAR(255),
	PRIMARY KEY  (recid)	
    );";

   require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
   dbDelta( $sql );
 
   add_option( "withinweb_keycodes_db_version", $withinweb_keycodes_db_version );
   
   //echo("<p>keycodes_saleshistory created</p>");
}


/**
Install the items table
*/
function withinweb_keycodes_install_items_table() {

	global $wpdb;
  	$table_name = $wpdb->prefix . "keycodes_items";	
	
	//note that item number is unique which is needed later on as it is this value that is looked up in the database.
	
   $sql = "CREATE TABLE IF NOT EXISTS $table_name (
	recid INT NOT NULL AUTO_INCREMENT,		
	item_number VARCHAR(50) UNIQUE NOT NULL,		
	item_name VARCHAR(100) UNIQUE NOT NULL ,
	item_title VARCHAR(100) NOT NULL,
	item_description TEXT,
	item_description_full TEXT,
	mc_gross VARCHAR(10) NOT NULL ,	
	mc_currency VARCHAR(10), 	
	currency VARCHAR(20),
	emailsubject VARCHAR(255),
	emailtextkeycodes TEXT,	
	lowerlimit INT default '5',
	keycodes TEXT,
	PRIMARY KEY  (recid)	
    );";	
	
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
   	dbDelta( $sql );	
 
}

/**
/Used to install default data into the table if that is required - not used in this application
*/
//function withinweb_keycodes_install_data() {
//  
//   global $wpdb;   
//   $table_name = $wpdb->prefix . "keycodes_saleshistory";
//   $rows_affected = $wpdb->insert( $table_name, array( 'reveiver_email' => 'fred@blogs.com', 'item_name' => 'myitem', 'item_number' => 'mynumber', 'mc_gross' => '2', 'payer_email' => "me@me.com", mc_currency => 'USD' , 'completed' => '2014-01-01') );
//   
//}

/**
* Deactivate the plugin
*/
function withinweb_keycodes_deactivate() {

   	//NOTE dbDelta( $sql );  does not work with DROP
	
	global $wpdb;
	
	//Delete the table if it exists		
	$table_name = $wpdb->prefix . "keycodes_saleshistory";      
	$sql = "DROP TABLE IF EXISTS $table_name";	   
	$wpdb->query( $sql );	
	
	//Delete the table if it exists	
	$table_name = $wpdb->prefix . "keycodes_items";      
	$sql = "DROP TABLE IF EXISTS $table_name";	   
	$wpdb->query( $sql );

	//Delete all the array
	delete_option( 'withinweb_keycodes_op_array' );
	delete_option( 'withinweb_keycodes_environment_array' );
	delete_option( 'withinweb_keycodes_smtp_array' );
	
}


//==================================================================================================
/*
Create custom Post Type
*/
/*function create_post_type() {
	register_post_type( 'withinweb_keycodes_product',
		array(
			'labels' => array(
				'name' => __( 'Products' ),
				'singular_name' => __( 'Product' )
			),
		'public' => true,
		'has_archive' => true,
		)
	);
}*/


//==================================================================================================
/*
Create short codes
*/

//--------------------------------------------------------------
//Example shortcode for testing
add_shortcode( 'mysite', 'keycodes_mysite' );
function keycodes_mysite() {
	return '<a href="http://www.withinweb.com">My site</a>';	
}

//--------------------------------------------------------------
//Short code for generating the button
add_shortcode( 'keycodesbutton', 'process_keycodes_button' );
function process_keycodes_button( $atts ) {
	
	extract(shortcode_atts(array(
      'recid' => 0,     //Default value
   	), $atts));
	
		
	if (!is_numeric($recid))
	{
		exit();	
	}		
	$result = "";
	
	$options 		= get_option( 'withinweb_keycodes_op_array' );
	$environment 	= get_option( 'withinweb_keycodes_environment_array' );
	
	
	$paypaladdress 			= $options['withinweb_keycodes_paypal_email'];
	$cancel_url 			= $options['withinweb_keycodes_cancel_url'];
	$return_url  			= $options['withinweb_keycodes_return_url'];
	$notify_url 			= $options['withinweb_keycodes_ipn_url'];
		
	//Get item details
	global $wpdb;	
	$table_name = $wpdb->prefix . "keycodes_items";
	//$row = $wpdb->get_row( "SELECT * FROM " . $table_name . " WHERE recid = " . $recid );
	
	$row = $wpdb->get_row( $wpdb->prepare( 
		"
			SELECT * FROM $table_name WHERE recid = %d 
		", 
    	    array(
			$recid			
		) 
	) );
	
	if ( $row )
	{
			
		$item_name 				= $row->item_name;
		$mc_gross 				= $row->mc_gross;
		$item_number 			= $row->item_number;
		$physicalgoods 			= $row->physicalgoods;
		$mc_currency 			= $row->mc_currency;
		
		
		$result .= "<form method='post' name='paypal_form' action='https://www.paypal.com/cgi-bin/webscr'>" . "\r\n";
		
			$result .= "<!-- PayPal Configuration -->" . "\r\n";
        	$result .= "<input type='hidden' name='cmd' value='_xclick' />" . "\r\n";
        	$result .= "<input type='hidden' name='business' value='$paypaladdress' />" . "\r\n";
        	$result .= "<input type='hidden' name='currency_code' value='$mc_currency' />" . "\r\n";
        	$result .= "<input type='hidden' name='no_note' value='1' />" . "\r\n";

       		$result .= "<!-- Product Information -->" . "\r\n";
			$result .= "<input type='hidden' name='item_name' value='$item_name' />" . "\r\n";
			$result .= "<input type='hidden' name='amount' value='$mc_gross' />" . "\r\n";
			$result .= "<input type='hidden' name='item_number' value='$item_number' />" . "\r\n";

			$result .= "<!-- Build notation -->" . "\r\n";
			$result .= "<input type='hidden' name='bn' value='Withinweb_Cart_WPS' />" . "\r\n";
			$result .= "<input type='hidden' name='mrb' value='L9C6XEDHCDXQ2' />" . "\r\n";

			$result .= "<!-- //language code, France(FR), Spain (ES), Italy (IT), Germany (DE), China (CN), English (US). -->" . "\r\n";
			$result .= "<input type='hidden' name='lc' value='US' />" . "\r\n";	
		
			$result .= "<!-- custom variable -->" . "\r\n";    
    		
			//Custom entry
			if ( isset( $_POST["custom"] ) ) {
				$custom = $_POST["custom"];
			
				$result .= "<input type='hidden' name='custom' value='$custom' />" . "\r\n";
        	
			}		
		
			//Note that name='no_shipping' defines if you want the shipping address to be prompted for
		
			$result .= "<!-- Free Shipping -->" . "\r\n";
			$result .= "<input type='hidden' name='shipping' value='0' />" . "\r\n";

			$result .= "<!-- URLs -->" . "\r\n";
			$result .= "<input type='hidden' name='notify_url' value='$notify_url' />" . "\r\n";
			
			if ($cancel_url != "") {
			
				$result .= "<input type='hidden' name='cancel_return' value='$cancel_url' />" . "\r\n";
				
			}
		
			if ($return_url != "") {
			
				$result .= "<input type='hidden' name='return' value='$return_url' />" . "\r\n";
			
			}
	
		$result .= "<input type='image' src='https://www.paypal.com/en_US/GB/i/btn/btn_buynowCC_LG.gif' border='0' name='submit' alt='PayPal - The safer, easier way to pay online.'>" . "\r\n";
	
    	$result .= "</form>" . "\r\n";    	

	}
	else
	{
		$result = "No row found";	
	}

	return $result;		
	
}



//==================================================================================================
/**
* Pages
*/
function withinweb_keycodes_settings_page() {
	if ( is_admin() )		//Check if admin user
	{
		//we are in wp-admin
		require (plugin_dir_path(__FILE__) .'views/adminsettings.php' );
	}

}

function withinweb_keycodes_createitem_page() {
	if ( is_admin() )		//Check if admin user
	{
		//we are in wp-admin
		require (plugin_dir_path(__FILE__) .'views/admincreateitem.php' );
	}
}

function withinweb_keycodes_listitems_page() {
	if ( is_admin() )		//Check if admin user
	{
		//we are in wp-admin
		require (plugin_dir_path(__FILE__) .'views/adminlistitems.php' );
	}
}
	
function withinweb_keycodes_about_page() {
	if ( is_admin() )		//Check if admin user
	{
		//we are in wp-admin
		require (plugin_dir_path(__FILE__) .'views/adminabout.php' );
	}
}

function withinweb_keycodes_help_page() {
	if ( is_admin() )		//Check if admin user
	{
		//we are in wp-admin
		require (plugin_dir_path(__FILE__) .'views/adminhelp.php' );
	}
}

function withinweb_keycodes_sales_page() {
	if ( is_admin() )		//Check if admin user
	{
		//we are in wp-admin
		require (plugin_dir_path(__FILE__) .'views/adminsales.php' );
	}
}

function process_withinweb_keycodes_salesdetails_page() {
	if ( is_admin() )		//Check if admin user
	{
		//we are in wp-admin
		require (plugin_dir_path(__FILE__) .'views/adminsalesdetails.php' );
	}
}

function process_withinweb_keycodes_edititem_page() {
	if ( is_admin() )		//Check if admin user
	{
		//we are in wp-admin
		require (plugin_dir_path(__FILE__) .'views/adminedititem.php' );
	}	
}

function withinweb_keycodes_localtest_page() {
	if ( is_admin() )		//Check if admin user
	{
		//we are in wp-admin
		require (plugin_dir_path(__FILE__) .'views/adminlocaltest.php' );
	}	
}


//function withinweb_keycodes_uninstall_page() {
//	if ( is_admin() )		//Check if admin user
//	{
//		//we are in wp-admin	
//	}
//}
//==================================================================================================



//==================================================================================================
//Include any include files
include('scripts/process.php');

?>