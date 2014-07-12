<?php @set_time_limit(300);		//added to make sure emails are sent out from SMTP

	//define the debug flag
	define ("KEYCODES_DEBUG", "FALSE");

	include ("functions.php");
	include ("keycodes_paypal.php");
		
		
	//http://stackoverflow.com/questions/16866389/get-wordpress-options-in-outside-php-file
	//https://codex.wordpress.org/Function_Reference/site_url   or content_url or CONTENT_DIR or CONTENT_URL
	
	$scriptPath = dirname(__FILE__);
	$path = realpath($scriptPath . '/./');
	$filepath = split("wp-content", $path);
	//print_r($filepath);
	//echo(''.$filepath[0].'wp-blog-header.php');
	//exit;
	//define('WP_USE_THEMES', false);	
	require_once(''.$filepath[0].'wp-blog-header.php');
	

	//#############################################################################
	//Get all set up variables
	$options 		= get_option( 'withinweb_keycodes_op_array' );
	$environment 	= get_option( 'withinweb_keycodes_environment_array' );

	$withinweb_keycodes_admin_email 			= $options["withinweb_keycodes_admin_email"];			//Your admin email address
	$withinweb_keycodes_paypal_email 			= $options["withinweb_keycodes_paypal_email"];			//PayPal email address
	$withinweb_keycodes_cancel_url 				= $options["withinweb_keycodes_cancel_url"];			//PayPal cancel url
	$withinweb_keycodes_return_url 				= $options["withinweb_keycodes_return_url"];			//PayPal return url
	$withinweb_keycodes_ipn_url 				= $options["withinweb_keycodes_ipn_url"];				//The location of this confirm url
	$withinweb_keycodes_paypal_environment 		= $environment["withinweb_keycodes_paypal_environment"];	//live or sandbox
	//#############################################################################	


	$headers .= "From:" . $withinweb_keycodes_admin_email . $sep . "\r\n";   //header with separater for headers
	
	//++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	// TEST EMAIL
	if (KEYCODES_DEBUG == "TRUE") {
		wp_mail( $withinweb_keycodes_admin_email, "Test 1", "Confirm start", $headers );
	}
	//exit;
	//++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	
	//wp_mail( $withinweb_keycodes_admin_email, "IPN - TEST", "test", $headers );	
	//echo("withinweb_keycodes_admin_email:= " . $withinweb_keycodes_admin_email . "<br/>");
	//echo("withinweb_keycodes_paypal_email:= " . $withinweb_keycodes_paypal_email . "<br/>");
	//echo("withinweb_keycodes_cancel_url:= " . $withinweb_keycodes_cancel_url . "<br/>");
	//echo("withinweb_keycodes_return_url:= " . $withinweb_keycodes_return_url . "<br/>");
	//echo("withinweb_keycodes_ipn_url:= ". $withinweb_keycodes_ipn_url . "<br/>");
	//echo("withinweb_keycodes_paypal_environment:= ". $withinweb_keycodes_paypal_environment . "<br/>");					
	//exit;


	//-------------------------------------------------------------
	//Confirm PayPal transaction and store data in database
	//
	//*	Check the payment_status is Completed				
	//*	Check that txn_id has not been previously processed			
	//*	Check that receiver_email is your Primary PayPal email			
	//*	Check that mc_gross is correct
	//*	Check that mc_currency is correct			
	//*	Process payment and update database
	
	//Note that :	
	//	currency_code is mc_currency, payment_gross is mc_gross.  payment_gross is the legacy version.		
	//	currency_code is the variable that you use on your HTML button code.
	//	mc_currency is the variable that IPN post back.
	//	So, currency_code is posted back as mc_currency.		
	//-------------------------------------------------------------


	//#############################################################################
	//Local test mode
	
	if ( $withinweb_keycodes_paypal_environment == "localtest" )
	{
		$testmode = "true";
	}
	else
	{
		$testmode = "false";
	}


	//++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	// TEST EMAIL
	if (KEYCODES_DEBUG == "TRUE") {
		wp_mail( $withinweb_keycodes_admin_email, "Test 2", "Test mode : " . $testmode, $headers );	
	}
	//++++++++++++++++++++++++++++++++++++++++++++++++++++++++



	//#############################################################################
	//Local test mode
	if ($testmode == "true") { 		//$testmode is INTERNAL test			
		$result = "VERIFIED";	//set this when test mode is true and it comes from local test page
	}
	//#############################################################################



	//#############################################################################
	//Format the received post data for display purposes
	$req_format = format_post($_POST);
	
	if (KEYCODES_DEBUG == "TRUE") {
		wp_mail( $withinweb_keycodes_admin_email, "Test 3", $req_format, $headers );
	}

	//Get the required posted variables and place into an array
	$paypal = create_local_variables();
	
	$adminaddress = $withinweb_keycodes_admin_email;
	
	//This is single item PayPal selection	
	//If we know the item_name we now have to find the email details of this.
	 
	//Get email details for this item	
	$itemdetails = itemdetails($_POST['item_number']);	 

	$item_name			= $itemdetails['item_name'];
	$item_number		= $itemdetails['item_number'];
	$emailsubject 		= $itemdetails['emailsubject'];
	$emailtextkeycodes	= $itemdetails['emailtextkeycodes'];


	if (KEYCODES_DEBUG == "TRUE") {
		wp_mail( $withinweb_keycodes_admin_email, "Test 4", "Email details \r\n ITEM NUMBER: " . $item_number . "\r\n ITEM NAME: " . $item_name . "\r\n EMAIL SUBJECT: " . $emailsubject . "\r\n EMAIL TEXT: " . $emailtextkeycodes . "\r\n", $headers );
		//wp_mail( $withinweb_keycodes_admin_email, "Test 4", "result: " . $result, $headers );		
		//exit;
	}

	//#############################################################################	
	//use the fsockopen method to get the result
	if ($testmode != "true") { 		//$testmode is INTERNAL test

            if ($withinweb_keycodes_paypal_environment == "live")
            {
			    $result = fsockpost( "https://www.paypal.com/cgi-bin/webscr", $_POST );
            }

            if ($withinweb_keycodes_paypal_environment == "sandbox")    
            {
			    $result = fsockpost( "https://www.sandbox.paypal.com/cgi-bin/webscr", $_POST );
            }

	}
	//#############################################################################
	

	//#############################################################################
	//Get receiver email address
	$receiver_email = $withinweb_keycodes_paypal_email;
	//#############################################################################


	if (KEYCODES_DEBUG == "TRUE") {
		wp_mail( $withinweb_keycodes_admin_email, "Test 5", "receiver_email: " . $receiver_email . " environment: " . $withinweb_keycodes_paypal_environment, $headers );
	}


	//#############################################################################	
	//check the ipn result received back from paypal
	if ( preg_match("/INVALID/i", $result) ) {
		$reason = "This is either attempted fraud (check that they have paid before you send anything off) or there is corruption of IPN data between your site and PayPal's site";
		wp_mail( $receiver_email, "IPN - INVALID result", $reason, $headers );
	} else {

		switch( strtolower( $paypal["payment_status"] ) ) {

			case "completed":		//The payment has been completed and the funds are now in your account.

				//check email address - note the trim of the email addresses						
				if ( strcasecmp( trim( $paypal["receiver_email"] ), trim( $receiver_email ) ) == 0) {

						switch( strtolower( $paypal["txn_type"] ) ) {
							case "web_accept": //"web_accept": The payment was sent by your customer via a Buy Now button.								

								//Transaction OK, so now make further tests.								
								process($paypal, $receiver_email, $adminaddress, $emailsubject, $emailtext, $emailtextkeycodes, $setup['copyemail'], $testmode );				
								wp_mail( $receiver_email, "IPN - VERIFIED and SINGLE ITEM PURCHASE", $req_format, $headers );
								break;

							case "cart":  //"cart": This payment was sent by your customer via the Shopping Cart featureS					

							case "send_money":
								$reason = "This payment was sent by your customer from the PayPal website using the Send Money tab\r\n";
								wp_mail( $receiver_email, "IPN - VERIFIED and SOMEBODY SENT MONEY", $reason, $headers );
								break;

							//subscr_signup, subscr_cancel,subscr_failed,subscr_eot,subscr_modify - these do not have a payment_status of Completed
	
							case "subscr_payment":
								$reason = "This IPN is for a subscription payment\r\n";
								wp_mail( $receiver_email, "IPN - VERIFIED and SUBSCRIPTION PAYMENT", $reason, $headers );
								break;		
									
							default:
								$reason = "Unknown txn type is received\r\n";
								wp_mail( $receiver_email, "IPN - Unknown txn type has been received", $reason, $headers );
								break;								
									
							}
						
					}
					else
					{
					$reason = "This usually means that you have multiple emails registered with PayPal but you have not used the primary email address in the PHP-Keycodes admin set up";
					wp_mail( $receiver_email, "IPN - VERIFIED but incorrect Receiver_email address, no action taken", $reason, $headers );					
					}
					
					break;

				case "pending":	//The payment is pending - see the "pending reason" variable below for more information. 
							//Note: You will receive another instant payment notification when the payment becomes 
							//"completed", "failed", or "denied"

						switch( strtolower ( $paypal["pending_reason"]  )  ) {
							case "address":
								$reason = "The payment is pending because your customer did not include a confirmed shipping address and you, the merchant, have your Payment Receiving Preferences set such that you want to manually accept or deny each of these payments. To change your preference, go to the Preferences section of your Profile";
								break;
							case "echeck":
								$reason = "The payment is pending because it was made by an eCheck, which has not yet cleared";
								break;								
							case "intl":
								$reason = "The payment is pending because you, the merchant, hold an international account and do not have a withdrawal mechanism. You must manually accept or deny this payment from your Account Overview";
								break;								
							case "multi-currency":
								$reason = "You do not have a balance in the currency sent, and you do not have your Payment Receiving Preferences set to automatically convert and accept this payment. You must manually accept or deny this payment";
								break;								
							case "unilateral":
								$reason = "The payment is pending because it was made to an email address that is not yet registered or confirmed.";
								break;								
							case "upgrade":
								$reason = "The payment is pending because it was made via credit card and you, the merchant, must upgrade your account to Business or Premier status in order to receive the funds";
								break;								
							case "verify":
								$reason = "The payment is pending because you, the merchant, are not yet verified. You must verify your account before you can accept this payment";
								break;								
							case "other":
								$reason = "The payment is pending for some other reason. For more information, contact PayPal customer service";
								break;
							default:
								$reason = "Unknown pending reason was received.";
								break;						
							
						}

					//Send email to admin
					wp_mail( $receiver_email, "IPN - VERIFIED and PENDING", $reason, $headers );
					//Send email to customer
					wp_mail( $paypal["payer_email"], "PayPal purchase verified and order is waiting to be processed", $reason, $headers );
					break;				
				
				case "failed":					
				    $reason = "This only happens if the payment was made from your customer's bank account.\r\n\r\n";
					wp_mail( $receiver_email, "IPN - VERIFIED and FAILED", $reason, $headers );
					break;
						
				case "denied":
					$reason = "You, the merchant, denied the payment. This will only happen if the payment was previously pending due to one of the pending reasons\r\n\r\n";					
					wp_mail( $receiver_email, "IPN - VERIFIED and DENIED", $reason, $headers );
					break;

				//---------------				
				case "refunded";
					$reason = "You refunded the payment\r\n\r\n";
					wp_mail( $receiver_email, "IPN - VERIFIED and REFUNDED", $reason, $headers );					
					break;					

				case "partially-refunded";
					$reason = "The transaction has been partially refunded\r\n\r\n";
					wp_mail( $receiver_email, "IPN - VERIFIED and PARTIALLY-REFUNDED", $reason, $headers );										
					break;
					
				case "expired";
					$reason = "This authorization has expired and cannot be captured\r\n\r\n";
					wp_mail( $receiver_email, "IPN - VERIFIED and EXPIRED", $reason, $headers );														
					break;
				
				case "processed";
					$reason = "A payment has been accepted\r\n\r\n";
					wp_mail( $receiver_email, "IPN - VERIFIED and PROCESSED", $reason, $headers );																			
					break;

				case "canceled-reversal";
					$reason = "A reversal has been cancelled. For example, you won a dispute with the customer, and the funds for the transaction that was reversed have been returned to you.\r\n\r\n";
					wp_mail( $receiver_email, "IPN - VERIFIED and CANCELLED-REVERSAL", $reason, $headers );					
					break;
			
				case "in-progress";
					$reason = "The transaction is in process of authorization and capture\r\n\r\n";
					wp_mail( $receiver_email, "IPN - VERIFIED and IN-PROGRESS", $reason, $headers );							
					break;
	
				case "reversed";
					$reason = "A payment was reversed due to a chargeback or other type of reversal. The funds have been removed from your account balance and returned to the buyer.\r\n\r\n";
					wp_mail( $receiver_email, "IPN - VERIFIED and REVERSED", $reason, $headers );				
					break;													
				//---------------

			case "":	//blank payment_status, so check subscription txt_type
				
					//check email address - note the trim of the email addresses						
					if ( strcasecmp( trim( $paypal["receiver_email"] ), trim( $receiver_email ) ) == 0) {

						switch( strtolower( $paypal["txn_type"] ) ) {

							case "subscr_signup":
								$reason = "This IPN is for a subscription sign-up, which is not used in this application\r\n";								
								wp_mail( $receiver_email, "IPN - VERIFIED and SUBSCRIPTION SIGNUP", $reason, $headers );									
								break;

							case "subscr_cancel":
								$reason = "This IPN is for a subscription cancellation, which is not used in this application\r\n";
								wp_mail( $receiver_email, "IPN - VERIFIED and SUBSCRIPTION CANCELLED", $reason, $headers );									
								break;

							case "subscr_failed":
								$reason = "This IPN is for a subscription payment failure, which is not used in this application\r\n";
								wp_mail( $receiver_email, "IPN - VERIFIED and SUBSCRIPTION FAILED", $reason, $headers );								
								break;

							//subscr_payment is not here because it has a status of Completed
							//case "subscr_payment": // has a payment_status of Completed
							//	process_subscr_payment($paypal, $receiver_email, $adminaddress, $emailsubject, $emailtext, $setup['copyemail'], $testmode);
							//	$reason = "This IPN is for a subscription payment\r\n";							
							//	wp_mail( $receiver_email, "IPN - Verfied and subscription payment", $reason, $headers );			
							//	break;

							case "subscr_eot":
								$reason = "This IPN is for a subscription's end-of-term, which is not used in this application\r\n";
								wp_mail( $receiver_email, "IPN - VERIFIED and SUBSCRIPTION END OF TERM", $reason, $headers );									
								break;

							case "subscr_modify":
								$reason = "This IPN is for a modification to a subscription, which is not used in this application\r\n";
								wp_mail( $receiver_email, "IPN - VERIFIED and MODIFIED ATTEMPTED", $reason, $headers );										
								break;
									
							default:
								$reason = "Unknown txn type is received\r\n";
								wp_mail( $receiver_email, "IPN - UNKNOWN txn type is received", $reason, $headers );									
								break;								
									
						}

					}
					else
					{
					$reason = "This usually means that you have multiple emails registered with PayPal but you have not used the primary email address in the PHP-Keycodes admin set up";
					wp_mail( $receiver_email, "IPN - VERIFIED but incorrect Receiver_email address", $reason, $headers );									
					}
																
					break;
			
				default:
					$reason = "Unknown payment status was received\r\n\r\n";
					wp_mail( $receiver_email, "IPN - VERIFIED and FAILED", $reason, $headers );						
					break;		
				
			}
				
	}
	//#############################################################################	


?>