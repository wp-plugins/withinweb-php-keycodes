<?php
//--------------------------------------------------------
// Settings page
function process_withinweb_keycodes_settings()
{
	
   if ( !current_user_can( 'manage_options' ) )
   {
      wp_die( 'You are not allowed to be on this page.' );
   }
   
   // Check the nonce field
   if ( !check_admin_referer( 'withinweb_keycodes_op_verify', 'withinweb_keycodes_settings' ) )
   {
		exit;   
   }
   
   $options = get_option( 'withinweb_keycodes_op_array' );
   
   //-------------
   if ( isset( $_POST['admin_email'] ) )
   {
		$admin_email = sanitize_text_field( $_POST['admin_email'] );

		//Validaate email
		if ( is_email($admin_email) )
		{
      		$options['withinweb_keycodes_admin_email'] = $admin_email;
		}
		else
		{
			$pluginfolder = str_replace( '/scripts', '',  dirname(plugin_basename(__FILE__)) );		
			wp_redirect(  admin_url( 'admin.php?page=' . $pluginfolder . '/withinweb_keycodes.php_settings&m=2' ) );
			exit;
		}
   }
   
      //-------------
   if ( isset( $_POST['paypal_email'] ) )
   {
		$paypal_email = sanitize_text_field( $_POST['paypal_email'] );
      
		//Validaate email
		if ( is_email($paypal_email) )
		{
	  		$options['withinweb_keycodes_paypal_email'] = $paypal_email;
		}
		else		
		{
			$pluginfolder = str_replace( '/scripts', '',  dirname(plugin_basename(__FILE__)) );		
			wp_redirect(  admin_url( 'admin.php?page=' . $pluginfolder . '/withinweb_keycodes.php_settings&m=2' ) );
			exit;
		}
		
   }
   
      //-------------
   if ( isset( $_POST['cancel_url'] ) )
   {
      $options['withinweb_keycodes_cancel_url'] = sanitize_text_field( $_POST['cancel_url'] );
   }

   //-------------
   if ( isset( $_POST['return_url'] ) )
   {
      $options['withinweb_keycodes_return_url'] = sanitize_text_field( $_POST['return_url'] );
   }

	//Get plug in base name
	$pluginfolder = str_replace( '/scripts', '',  dirname(plugin_basename(__FILE__)) );		

   	update_option( 'withinweb_keycodes_op_array', $options );
	wp_redirect(  admin_url( 'admin.php?page=' . $pluginfolder . '/withinweb_keycodes.php_settings&m=1' ) );
   	exit;
}
//--------------------------------------------------------
//Environment settings
function process_withinweb_keycodes_environment()
{
	
   if ( !current_user_can( 'manage_options' ) )
   {
      wp_die( 'You are not allowed to be on this page.' );
   }

   // Check the nonce field
   if (!check_admin_referer( 'withinweb_keycodes_op_verify', 'withinweb_keycodes_environment' ))
   {
	   exit();
   }
   
   $options = get_option( 'withinweb_keycodes_environment_array' );

   if ( isset( $_POST['environment'] ) )
   {
      $options['withinweb_keycodes_paypal_environment'] = sanitize_text_field( $_POST['environment'] );
   }

	//Get plug in base name
	$pluginfolder = str_replace( '/scripts', '',  dirname(plugin_basename(__FILE__)) );			

   	update_option( 'withinweb_keycodes_environment_array', $options );	
	wp_redirect(  admin_url( 'admin.php?page=' . $pluginfolder . '/withinweb_keycodes.php_settings&m=1' ) );

   	exit;	
}
//--------------------------------------------------------
//SMTP settings
function process_withinweb_keycodes_smtp()
{
	
   	if ( !current_user_can( 'manage_options' ) )
   	{
      wp_die( 'You are not allowed to be on this page.' );
   	}

   	// Check the nonce field
   	if (!check_admin_referer( 'withinweb_keycodes_op_verify', 'withinweb_keycodes_smtp' ))
   	{
	   exit();
   	}
   
   	$options = get_option( 'withinweb_keycodes_smtp_array' );

   	if ( isset( $_POST['smtp_use'] ) )
   	{
      $options['withinweb_keycodes_smtp_use'] = sanitize_text_field( $_POST['smtp_use'] );
   	}

   	if ( isset( $_POST['smtp_host'] ) )
   	{
      $options['withinweb_keycodes_smtp_host'] = sanitize_text_field( $_POST['smtp_host'] );
   	}

   	if ( isset( $_POST['smtp_username'] ) )
   	{
      $options['withinweb_keycodes_smtp_username'] = sanitize_text_field( $_POST['smtp_username'] );
   	}

   	if ( isset( $_POST['smtp_password'] ) )
   	{
      $options['withinweb_keycodes_smtp_password'] = sanitize_text_field( $_POST['smtp_password'] );
   	}
   
   	//Get plug in base name
	$pluginfolder = str_replace( '/scripts', '',  dirname(plugin_basename(__FILE__)) );
   
   	update_option( 'withinweb_keycodes_smtp_array', $options );   	
	wp_redirect(  admin_url( 'admin.php?page=' . $pluginfolder . '/withinweb_keycodes.php_settings&m=1' ) );   	

   	exit;	
}
//--------------------------------------------------------
//Create a new item
function process_withinweb_keycodes_createitem()
{

   if ( !current_user_can( 'manage_options' ) )
   {
      wp_die( 'You are not allowed to be on this page.' );
   }

   // Check the nonce field
  if ( !check_admin_referer( 'withinweb_keycodes_op_verify', 'withinweb_keycodes_createitem' ) )
   {
	   exit;
   }   
   
   if ( isset( $_POST['item_number'] ) )
   {
      $item_number = sanitize_text_field( $_POST['item_number'] );
   }  
   
   if ( isset( $_POST['item_name'] ) )
   {
      $item_name = sanitize_text_field( $_POST['item_name'] );
   }   

   if ( isset( $_POST['item_title'] ) )
   {
      $item_title = sanitize_text_field( $_POST['item_title'] );
   }   

   if ( isset( $_POST['item_description'] ) )
   {
      $item_description = sanitize_text_field( $_POST['item_description'] );
   }   
 
   if ( isset( $_POST['mc_gross'] ) )
   {
      $mc_gross = sanitize_text_field( $_POST['mc_gross'] );
	  
	  if (!validateTwoDecimals($mc_gross))
	  {
	  	$pluginfolder = str_replace( '/scripts', '',  dirname(plugin_basename(__FILE__)) );	
	
		wp_redirect(  admin_url( 'admin.php?page=' . $pluginfolder . '/withinweb_keycodes.php_createitem&m=3' ) );		
		exit;	
	  }	  
	  
   }   

   if ( isset( $_POST['mc_currency'] ) )   
   {
      $mc_currency = sanitize_text_field( $_POST['mc_currency'] );
   }   
   
   if ( isset( $_POST['emailsubject'] ) )   
   {
      $emailsubject = sanitize_text_field( $_POST['emailsubject'] );
   }   
   
   if ( isset( $_POST['emailtextkeycodes'] ) )   
   {
      $emailtextkeycodes = sanitize_text_field ( $_POST['emailtextkeycodes'] );
   }

   if ( isset( $_POST['lowerlimit'] ) )
   {
      $lowerlimit = sanitize_text_field( $_POST['lowerlimit'] );
   }

	if (!is_numeric($lowerlimit))
	{
		$pluginfolder = str_replace( '/scripts', '',  dirname(plugin_basename(__FILE__)) );	
	
		wp_redirect(  admin_url( 'admin.php?page=' . $pluginfolder . '/withinweb_keycodes.php_createitem&m=2' ) );		
		exit;	
	}

   if ( isset( $_POST['keycodes'] ) )
   {
      $keycodes = $_POST['keycodes'];
   }   


	global $wpdb;
	$table_name = $wpdb->prefix . "keycodes_items";

	//Check item_name and item_numebr to see if they are duplciated values
	//$strSQL = " SELECT item_name FROM $table_name WHERE item_name = $item_name ";
	$row1 = $wpdb->get_row( $wpdb->prepare( 
		"
			SELECT item_name FROM $table_name WHERE item_name = %s
		", 
    	    array(
			$item_name			
		) 
	) );

	//Check item_name and item_numebr to see if they are duplciated values
	//$strSQL = " SELECT item_name FROM $table_name WHERE item_name = $item_name ";
	$row2 = $wpdb->get_row( $wpdb->prepare( 
		"
			SELECT item_number FROM $table_name WHERE item_number = %s
		", 
    	    array(
			$item_number			
		) 
	) );


	if ($row1 || $row2)
	{
		//If either row has something in it then it means item_name or item_number exists.
		//Get plug in base name
		$pluginfolder = str_replace( '/scripts', '',  dirname(plugin_basename(__FILE__)) );	
	
		wp_redirect(  admin_url( 'admin.php?page=' . $pluginfolder . '/withinweb_keycodes.php_createitem&m=4' ) );
		exit;
		
	}
	else
	{

		$wpdb->query( $wpdb->prepare( 
			"
				INSERT INTO $table_name
				( item_number, item_name, item_title, item_description, mc_gross, mc_currency, lowerlimit, emailsubject, emailtextkeycodes, keycodes )
				VALUES ( %s, %s, %s, %s, %s, %s, %d, %s, %s, %s  )
			", 
	    	    array(
				$item_number, 
				$item_name, 
				$item_title,
				$item_description,
				$mc_gross,
				$mc_currency,
				$lowerlimit,
				$emailsubject,
				$emailtextkeycodes,
				$keycodes
			) 
		) );

		//exit( var_dump( $wpdb->last_query ) );	
		//Get plug in base name
		$pluginfolder = str_replace( '/scripts', '',  dirname(plugin_basename(__FILE__)) );	
	
		wp_redirect(  admin_url( 'admin.php?page=' . $pluginfolder . '/withinweb_keycodes.php_createitem&m=1' ) );		
		exit;
	
	}

}
//--------------------------------------------------------
//Edit an item
function process_withinweb_keycodes_edititem()
{

   if ( !current_user_can( 'manage_options' ) )
   {
      wp_die( 'You are not allowed to be on this page.' );
   }
   
   // Check the nonce field
   if ( !check_admin_referer( 'withinweb_keycodes_op_verify', 'withinweb_keycodes_edititem' ) )
   {
	   //echo("refer failed");
	   exit;
   }

   if ( isset( $_POST['recid'] ) )
   {
      $recid = sanitize_text_field( $_POST['recid'] );
   }  

   if ( isset( $_POST['item_number'] ) )
   {
      $item_number = sanitize_text_field( $_POST['item_number'] );
   }  
   
   if ( isset( $_POST['item_name'] ) )
   {
      $item_name = sanitize_text_field( $_POST['item_name'] );
   }   

   if ( isset( $_POST['item_title'] ) )
   {
      $item_title = sanitize_text_field( $_POST['item_title'] );
   }   

   if ( isset( $_POST['item_description'] ) )
   {
      $item_description = sanitize_text_field( $_POST['item_description'] );
   }   
 
   if ( isset( $_POST['mc_gross'] ) )
   {
      $mc_gross = sanitize_text_field( $_POST['mc_gross'] );

	  if (!validateTwoDecimals($mc_gross))
	  {
	  	$pluginfolder = str_replace( '/scripts', '',  dirname(plugin_basename(__FILE__)) );	
	
		wp_redirect(  admin_url( 'admin.php?page=' . $pluginfolder . '/withinweb_keycodes.php_edititem&m=2' ) );		
		exit;	
	  }	  	  
	  
   }   

   if ( isset( $_POST['mc_currency'] ) )   
   {
      $mc_currency = sanitize_text_field( $_POST['mc_currency'] );
   }   

   if ( isset( $_POST['emailsubject'] ) )   
   {
      $emailsubject = $_POST['emailsubject'];
   }   

   if ( isset( $_POST['emailtextkeycodes'] ) )   
   {
      $emailtextkeycodes = $_POST['emailtextkeycodes'];
   }   

   if ( isset( $_POST['lowerlimit'] ) )
   {
      $lowerlimit = sanitize_text_field( $_POST['lowerlimit'] );
   }   

   if ( isset( $_POST['keycodes'] ) )
   {
      $keycodes = $_POST['keycodes'];
   }   

	//echo("recid " . $recid . "<br/");
	//echo("item number " . $item_number . "<br/>");
	//echo("item_name " . $item_name . "<br/>");
	//echo("item title " . $item_title . "<br/>");
	//echo("item description " . $item_description . "<br/>");
	//echo("mc gross " . $mc_gross . "<br/>");
	//echo("mc currency " . $mc_currency . "<br/>");
	//echo("emailsubject " . $emailsubject . "<br/>");		
	//echo("email textkey codes " . $emailtextkeycodes . "<br/>");	
	//echo("lower limit " . $lowerlimit . "<br/>");
	//echo("keycodes " . $keycodes . "<br/>");
	//exit;
	
	
/*
	//Update record with new values
	global $wpdb;
	$table_name = $wpdb->prefix . "keycodes_items";
	$data = array( 
				'item_number' => $item_number,
				'item_name' => $item_name,					
				'item_title' => $item_title,
				'item_description' => $item_description,
				'mc_gross' => $mc_gross,
				'mc_currency' => $mc_currency,				
				'emailsubject' => $emailsubject,
				'emailtextkeycodes' => $emailtextkeycodes,				
				'lowerlimit' => $lowerlimit,
				'keycodes' => $keycodes					
				);
	$where = array(
		 		'recid' => $recid
				);					
	$format = array(	//format of data
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',				
				'%s',
				'%d',
				'%s'
				);
	$where_format = array( 	//format of where
				'%d'
				);
	$rows_affected = $wpdb->update( $table_name, $data, $where, $format, $where_format );
*/	
	

	global $wpdb;
	$table_name = $wpdb->prefix . "keycodes_items";
	$wpdb->query( $wpdb->prepare( 
		"
			UPDATE $table_name SET
			item_number = %s, 
			item_name = %s, 
			item_title = %s, 
			item_description = %s, 
			mc_gross = %s, 
			mc_currency = %s, 
			emailsubject = %s, 
			emailtextkeycodes = %s, 
			lowerlimit = %d,
			keycodes = %s
			WHERE 
			recid = %d
		", 
    	    array(
			$item_number, 
			$item_name, 
			$item_title,
			$item_description,
			$mc_gross,
			$mc_currency,
			$emailsubject,
			$emailtextkeycodes,
			$lowerlimit,			
			$keycodes,
			$recid			
		) 
	) );
	
	
	
	//exit( var_dump( $wpdb->last_query ) );
	//Get plug in base name
	$pluginfolder = str_replace( '/scripts', '', dirname(plugin_basename(__FILE__)) );	
	//$complete_url = wp_nonce_url( 'admin.php?page=' . $pluginfolder . '/withinweb_keycodes.php_edititem&recid=' . $recid . '&m=1', 'edit_page', 'refid' );
	
	//wp_redirect(  admin_url( 'admin.php?page=' . $pluginfolder . '/withinweb_keycodes.php_edititem&recid=' . $recid . '&m=1' ) );		
	//echo($complete_url);
	//exit;	
	//header ( admin_url ( $complete_url ) );
	//exit;		
	//page=withinweb_keycodes1/withinweb_keycodes.php_listitems	
	//wp_redirect(  admin_url( 'admin.php?page=' . $pluginfolder . '/withinweb_keycodes.php_listitem' ) );
	//exit;	
	
	
	wp_redirect(  admin_url( 'admin.php?page=' . $pluginfolder . '/withinweb_keycodes.php_listitems&m=1' ) );	
	//wp_redirect(  admin_url ( $complete_url ) );	
	exit;

}
//--------------------------------------------------------
//Edit a new item
function process_withinweb_keycodes_localtest()
{
	
	if ( !current_user_can( 'manage_options' ) )
   	{
    	wp_die( 'You are not allowed to be on this page.' );
   	}
   
    // Check the nonce field
   	if ( !check_admin_referer( 'withinweb_keycodes_op_verify', 'withinweb_keycodes_localtest' ) )
   	{
	   exit;
   	}   
   
   	//Get the item number
	//Get the details for this product and post it to the confirm.php file
	
		//We need to post:
		//$item_name	
		//$mc_gross 
		//$item_number
		//$mc_currency	  
   
   	if ( isset( $_POST['item_number'] ) )					
   	{
    	$item_number = sanitize_text_field( $_POST['item_number'] );
   	}
	
  	if ( isset( $_POST['item_name'] ) )
   	{
    	$item_name = sanitize_text_field( $_POST['item_name'] );
   	}	
	
   	if ( isset( $_POST['receiver_email'] ) )
   	{
    	$receiver_email = sanitize_text_field( $_POST['receiver_email'] );
   	}   

   	if ( isset( $_POST['payment_status'] ) )
   	{
    	$payment_status = sanitize_text_field( $_POST['payment_status'] );
   	}   

   	if ( isset( $_POST['mc_gross'] ) )
   	{
    	$mc_gross = sanitize_text_field( $_POST['mc_gross'] );
   	}   

   	if ( isset( $_POST['mc_currency'] ) )
   	{
    	$mc_currency = sanitize_text_field( $_POST['mc_currency'] );
   	}   

   	if ( isset( $_POST['txn_id'] ) )
   	{
    	$txn_id = sanitize_text_field( $_POST['txn_id'] );
   	}   
	
   	if ( isset( $_POST['txn_type'] ) )
   	{
    	$txn_type = sanitize_text_field( $_POST['txn_type'] );
   	}   
	
 	if ( isset( $_POST['payer_email'] ) )
   	{
    	$payer_email = sanitize_text_field( $_POST['payer_email'] );
   	}   	
	

   	//echo('$item_name :' . $item_name . "<br/>");
   	//echo('$email_address :' . $email_address . "<br/>");	
	//exit;   
   
	//post to confirm.php
   	//http://codex.wordpress.org/Function_Reference/wp_remote_post

	//Get confirm url
	$options 						= get_option( 'withinweb_keycodes_op_array' );
	$withinweb_keycodes_ipn_url 	= $options["withinweb_keycodes_ipn_url"];	//The location of this confirm url
	
	//echo($withinweb_keycodes_ipn_url . "<br/>");
	//exit;
	
	//wp_redirect( $withinweb_keycodes_ipn_url );
	
	//Fields which should be posted to test the item.
	//$receiver_email  	//This is the email address which paypal uses to send confirmation emails, normally it is your email address
	//item_name			//The name of the item which can actually be anything and is not validated by the script
	//item_number		//The reference number of the item which should be the same as entered in the database
	//payment_status	//Completed, Pending, Failed, Denied   Defines the status from Paypal - selecting completed will simulate a completed transaction
	//mc_gross			//The gross value of the goods which must be the same as entered into the database.
	//quantity			//1
	//mc_currency		//The currency code which must be the same as entered into the database.
	//txn_id			//The PayPal transaction id. You can enter any random series of characters here, I place the current time and date.
	//payer_email		//This is the email address of the purchaser.  For this test, enter a personal email address so that you can see the resulting emails.
	//txn_type			//web_accept, Cart, Send Money		//This should be web_accept or cart.	
	
	$response = wp_remote_post( $withinweb_keycodes_ipn_url, array(
		'method' => 'POST',
		'timeout' => 45,
		'redirection' => 5,
		'httpversion' => '1.0',
		'blocking' => true,
		'headers' => array(),
		'body' => array( 'item_number' => $item_number, 'itme_name' => $item_name, 'receiver_email' => $receiver_email, 'payment_status' => $payment_status, 'mc_gross' => $mc_gross, 'mc_currency' => $mc_currency, 'txn_id' => $txn_id, 'payment_status' => $payment_status, 'txn_type' => $txn_type, 'payer_email' => $payer_email ),
		'cookies' => array()
    	)	
	);	

	//Get plug in base name
	$pluginfolder = str_replace( '/scripts', '',  dirname(plugin_basename(__FILE__)) );
	wp_redirect(  admin_url( 'admin.php?page=' . $pluginfolder . '/withinweb_keycodes.php_localtest&m=1' ) );		
	exit;

}
//--------------------------------------------------------
// Validate to two decimal places
function validateTwoDecimals($number)
{
   if(ereg('^[0-9]+\.[0-9]{2}$', $number))
	 return true;
   else
	 return false;
}


?>