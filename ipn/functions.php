<?php


//-----------------------------------------------------------------
/**
* Purpose	:	Format the array into a better list
* Inputs	:	The data
* @return string
*/
function format_post($data) {

if ( is_array ($data) ) {
		$file_values = "";
		foreach($data as $key=>$val) {
			$file_values .= "$key = $val\r\n";
		}
		
		return $file_values;

	}
	else
		{ 
		return false; 
		}

}
//-----------------------------------------------------------------
/**
* Purpose	:	Create the local variables ready for display output
* Inputs	:	None
* @return string
*/
function create_local_variables()
{
	//PayPal Configuration 
	$array_name['business']				="$_POST[business]"; 
	$array_name['receiver_email']		="$_POST[receiver_email]"; 		//Your primary PayPal email address	
	$array_name['receiver_id']			="$_POST[receiver_id]"; 
	$array_name['item_name']			="$_POST[item_name]"; 			//A brief description of the item
	$array_name['item_number']			="$_POST[item_number]"; 		//The item identity which is used to define the item
	$array_name['mc_currency']			="$_POST[mc_currency]";			//Currency code
	$array_name['quantity']				="$_POST[quantity]"; 			
	$array_name['invoice']				="$_POST[invoice]"; 
	$array_name['custom']				="$_POST[custom]"; 				//pass through variable not seen by the purchaser
	$array_name['memo']					="$_POST[memo]"; 
	$array_name['tax']					="$_POST[tax]"; 
	$array_name['option_name1']			="$_POST[option_name1]"; 
	$array_name['option_selection1']	="$_POST[option_selection1]"; 
	$array_name['option_name2']			="$_POST[option_name2]"; 
	$array_name['option_selection2']	="$_POST[option_selection2]"; 
	$array_name['num_cart_items']		="$_POST[num_cart_items]"; 
	$array_name['mc_gross']				="$_POST[mc_gross]"; 			//The payment gross
	$array_name['mc_fee']				="$_POST[mc_fee]"; 
	$array_name['mc_currency']			="$_POST[mc_currency]"; 
	$array_name['settle_amount']		="$_POST[settle_amount]"; 
	$array_name['settle_currency']		="$_POST[settle_currency]"; 
	$array_name['exchange_rate']		="$_POST[exchange_rate]"; 
	$array_name['payment_fee']			="$_POST[payment_fee]"; 
	$array_name['payment_status']		="$_POST[payment_status]"; 
	$array_name['pending_reason']		="$_POST[pending_reason]"; 
	$array_name['reason_code']			="$_POST[reason_code]"; 
	$array_name['payment_date']			="$_POST[payment_date]"; 
	$array_name['txn_id']				="$_POST[txn_id]"; 				//The PayPal transaction id
	$array_name['txn_type']				="$_POST[txn_type]"; 			//The type of PayPal transaction
	$array_name['payment_type']			="$_POST[payment_type]"; 
	$array_name['for_auction']			="$_POST[for_auction]"; 
	$array_name['auction_buyer_id']		="$_POST[auction_buyer_id]"; 
	$array_name['auction_closing_date']	="$_POST[auction_closing_date]"; 
	$array_name['auction_multi_item']	="$_POST[auction_multi_item]"; 
	$array_name['first_name']			="$_POST[first_name]"; 
	$array_name['last_name']			="$_POST[last_name]"; 
	$array_name['payer_business_name']	="$_POST[payer_business_name]"; 
	$array_name['address_name']			="$_POST[address_name]"; 
	$array_name['address_street']		="$_POST[address_street]"; 
	$array_name['address_city']			="$_POST[address_city]"; 
	$array_name['address_state']		="$_POST[address_state]"; 
	$array_name['address_zip']			="$_POST[address_zip]"; 
	$array_name['address_country']		="$_POST[address_country]"; 
	$array_name['address_status']		="$_POST[address_status]"; 
	$array_name['payer_email']			="$_POST[payer_email]"; 		//The email address of the person who is purchasing the product
	$array_name['payer_id']				="$_POST[payer_id]"; 
	$array_name['payer_status']			="$_POST[payer_status]"; 		//Will be COMPLETE if transaction is complete
	$array_name['notify_version']		="$_POST[notify_version]"; 
	$array_name['verify_sign']			="$_POST[verify_sign]"; 
 
	return $array_name;

}

//Post the data and get result


//-----------------------------------------------------------------
/**
* Purpose	:	Post the data and get result
* Inputs	:	the url to post to and the data to post
* @return string
*/
function fsockpost($url, $data) { 

	//Parse URL 
	$web = parse_url($url); 
	
	//stripslashes added
	foreach($data as $i=>$v) { 
		$postdata.= $i . "=" . urlencode(stripslashes($v)) . "&"; 
	}
	
	$postdata .= "cmd=_notify-validate";

	//Set the port number
	if ($web[scheme] == "https") { $web[port]="443";  $ssl="ssl://"; } else { $web[port]="80"; }  

	//Create PayPal connection
	$fp = @fsockopen($ssl . $web[host], $web[port], $errnum, $errstr, 30); 

	//Error checking
	if(!$fp) 
		{

		//Handle error
		//Send email		
		
		} else { //Post Data
 
		fputs($fp, "POST $web[path] HTTP/1.1\r\n"); 
		fputs($fp, "Host: $web[host]\r\n"); 
		fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n"); 
		fputs($fp, "Content-length: ".strlen($postdata)."\r\n"); 
		fputs($fp, "Connection: close\r\n\r\n"); 
		fputs($fp, $postdata . "\r\n\r\n"); 

		//loop through the response from the server 
		while(!feof($fp)) { $info[]=@fgets($fp, 1024); } 

		//close fp - we are done with it 
		fclose($fp); 

		//break up results into a string
		$info = implode(",",$info); 

		}

	return $info; 

}

//-----------------------------------------------------------------
/**
* Process the transaction for single purchase item
* Check if the mc_gross payment value is correct
* Check if the mc_currency code is correct
* Check if txn_id has been used before
* Save the data to the database
* Send the email
*
* For keycodegoods only check that the payment sent is at least the database value but do not exist.
* Also only send a warning email if payment and currency values don't match
* For digital goods and key codes, check if database value is less than or equal to paypal value, if not it will exit and send an email
*/
function process($paypal, $toaddress, $fromaddress, $emailsubject, $emailtext, $emailtextkeycodes, $copyemail, $testmode ) {

	$txn_id 		=	$paypal["txn_id"];
	$item_number 	=	$paypal["item_number"];	
	$item_name 		=	$paypal['item_name'];
	$payment_status =	$paypal['payment_status'];
	$mc_gross		=	$paypal["mc_gross"];
	$mc_currency	=	$paypal["mc_currency"];
	$receiver_email	=	$paypal["receiver_email"];
	$payer_email	=	$paypal["payer_email"];
	$quantity		=	$paypal["quantity"]; 		//this is actually set to 1 in code later on so is not really necessary here

/*	echo("txn_id : $txn_id<br>");
	echo("item_number : $item_number<br>");
	echo("item_name : $item_name<br>");	
	echo("payment_status : $payment_status<br>");	
	echo("mc_gross : $mc_gross<br>");	
	echo("mc_currency : $mc_currency<br>");	
	echo("receiver_email : $receiver_email<br>");	
	echo("payer_email : $payer_email<br>");	
	echo("email text : $emailtext<br>");
	exit;*/	

	$headers .= "From:" . $fromaddress . $sep . "\r\n";   //header with separater for headers

	$scriptPath = dirname(__FILE__);
	$path = realpath($scriptPath . '/./');
	$filepath = split("wp-content", $path);
	
	require_once(''.$filepath[0].'wp-blog-header.php');

	//Get tblitems details given the item_number
	global $wpdb;	
	$table_name = $wpdb->prefix . "keycodes_items";
	//$row = $wpdb->get_row( "SELECT * FROM " . $table_name . " WHERE item_number = '" . $item_number . "'" );
	
	$row = $wpdb->get_row( $wpdb->prepare( 
		"
			SELECT * FROM $table_name WHERE item_number = %s
		", 
    	    array(
			$item_number			
		) 
	) );
	
	//wp_mail( $toaddress, "Test - process", "SELECT * FROM " . $table_name . " WHERE item_number = '" . $item_number . "'  COUNT " . count($row), $headers);
	//exit;

	if ( $row )
	{			
		
		$recid				= $row->recid;
		$dbpaymentgross 	= $row->mc_gross;
		$isphysical			= $row->physicalgoods;	
		$isdigital			= $row->digitalgoods;	
		$iskeycodes			= $row->keycodesgoods;	
		$emailsubject		= $row->emailsubject;
		$emailtextkeycodes	= $row->emailtextkeycodes;
		$dbmc_currency 		= $row->mc_currency;			
	
		$iskeycodes = 1;

		
		if ($iskeycodes == 1) {
			if ( $dbpaymentgross > $mc_gross ) {	// $mc_gross must always be more than or equal to the entry in the database			
				wp_mail( $toaddress, "IPN - Error : Payment Gross is not correct.", "No action taken, no goods sent\r\nDatabase entry : $dbpaymentgross, mc_gross : $mc_gross", $headers);
				exit;		//exit with key codes
			}	
		}


		//Now check if the currency code is correct	
		if (strcmp($dbmc_currency, $mc_currency) != 0) {
			if ($iskeycodes == 1) {				
				wp_mail( $toaddress, "IPN - Error : Currency Code is not correct.", "No action taken, no goods sent\r\nDatabase entry : $dbmc_currency, mc_currency : $mc_currency", $headers );
				exit; //exit here for key codes
			}		
		}


		//Now check if the txn_id has been used before
		if (checktxnid($txn_id, $toaddress, $fromaddress, $testmode) != 0) {
			//wp_mail( $toaddress, "IPN - Error : Txn_ID has been duplicated", "No action taken, no goods sent\r\ntxn_id : $txn_id", $headers );
			exit;		
		}


		if ($iskeycodes == 1) {		//uses $emailtextkeycodes
			savetodbkeycodes($paypal, $recid, $txn_id, $item_number, $item_name, $payment_status, $mc_gross, $mc_currency, $receiver_email, $payer_email, $fromaddress, $emailsubject, $emailtextkeycodes, $copyemail, $toaddress, $testmode);
		}
	
	
	}
	

}
//-----------------------------------------------------------------
/**
//Purpose : For key codes, save the details to database - KEY CODES SINGLE ITEM PURCHASE
//Note that key code goods do not store the user id because no entry is created in the tblusers table
*/
function savetodbkeycodes($paypal, $recid, $txn_id, $item_number, $item_name, $payment_status, $mc_gross, $mc_currency, $receiver_email, $payer_email, $fromaddress, $emailsubject, $emailtext, $copyemail, $toaddress, $testmode) {

	define ("KEYCODES_DEBUG", "FALSE");

	$headers .= "From:" . $fromaddress . $sep . "\r\n";   //header with separater for headers
	
	$scriptPath = dirname(__FILE__);
	$path = realpath($scriptPath . '/./');
	$filepath = split("wp-content", $path);
	
	require_once(''.$filepath[0].'wp-blog-header.php');

	//Set quantity to 1 for this situation (single item purchase)
	$quantity = 1;

	//create database entry with current date/time for 
	//tblsaleshistory and convert to yyyy-mmm-dd time i.e. date("Y-m-d H:i:s")
	
	$currentdate = date("Y-m-d H:i:s");
	
	$item_name_s 			= 	$item_name;
	$receiver_email_s 		= 	$receiver_email;
	$payer_email_s 			= 	$payer_email;
	$custom_s 				= 	$paypal["custom"];		

	$business_s 			= 	substr( $paypal["business"] , 0, 254);
	$receiver_id_s 			=	substr( $paypal["receiver_id"] , 0, 254);
	$invoice_s 				=	substr( $paypal["invoice"] , 0, 254);
	$memo_s 				=	$paypal["memo"];
	$tax_s 					=	substr( $paypal["tax"] , 0, 254);
	$option_name1_s 		=	substr( $paypal["option_name1"] , 0, 254);
	$option_selection1_s 	=	substr( $paypal["option_selection1"] , 0, 254);
	$option_name2_s 		=	substr( $paypal["option_name2"] , 0, 254);
	$option_selection2_s 	=	substr( $paypal["option_selection2"] , 0, 254);
	$num_cart_items_s 		=	substr( $paypal["num_cart_items"] , 0, 254);
	$mc_fee_s 				=	substr( $paypal["mc_fee"] , 0, 254);
	$payment_date_s 		=	substr( $paypal["payment_date"] , 0, 254);
	$payment_type_s			=	substr( $paypal["payment_type"] , 0, 254);
	$first_name_s			=	substr( $paypal["first_name"] , 0, 254);
	$last_name_s			=	substr( $paypal["last_name"] , 0, 254);
	$payer_business_name_s	=	substr( $paypal["payer_business_name"] , 0, 254);
	$address_name_s			=	substr( $paypal["address_name"] , 0, 254);
	$address_street_s		=	substr( $paypal["address_street"] , 0, 254);
	$address_city_s			=	substr( $paypal["address_city"] , 0, 254);
	$address_state_s		=	substr( $paypal["address_state"] , 0, 254);
	$address_zip_s			=	substr( $paypal["address_zip"] , 0, 254);
	$address_country_s		=	substr( $paypal["address_country"] , 0, 254);
	$address_status_s		=	substr( $paypal["address_status"] , 0, 254);
	$payer_id_s				=	substr( $paypal["payer_id"] , 0, 254);
	$payer_status_s			=	substr( $paypal["payer_status"] , 0, 254);
	$notify_version_s		=	substr( $paypal["notify_version"] , 0, 254);
	$verify_sign_s			=	substr( $paypal["verify_sign"] , 0, 254);			
	
	//Now check if the txn_id has been used before
	//This is to prevent duplicate saving into database and to stop email being sent out to customers twice.
	if (checktxnid($txn_id, $toaddress, $fromaddress, $testmode) != 0) {	
		exit;
	}

	//--------------------------------------------	
	//Check if there are key codes in the database for this $recid
	//If there are then :
		//Read the top key code from the database.
		//If there are less then the lower limit then send an email
		//Delete the key codes from the database
		//Add it to the email message
	//If not then send an email	

	$numberofkeycodes = getnoofkeycodes($paypal, $recid);
	
	if (KEYCODES_DEBUG == "TRUE") {
		wp_mail( $toaddress, "Test 6", "Number of keycodes: $numberofkeycodes", $headers );
	}
	
	if ($numberofkeycodes > 0) {

		$notoretrieve = 1;	//in this case we only want 1 key code from the table
		$usinghttps = false; //This is for PayPal and so this will always be false
		$keycodes = getnextkeycodes($paypal, $recid, $notoretrieve, $usinghttps);  //get top key code and delete it from the database
	    $lowerlimit = getlowerlimit($paypal, $recid);		

		//--------------------------------------
		//Send email to customer
		//Case insensitive replace.				
		$emailtext 		= preg_replace("/key_codes/i", $keycodes, $emailtext);				
		$emailtext 		= preg_replace("/email_address/i", $payer_email, $emailtext);		
		$emailtext 		= preg_replace("/first_name/i", $first_name_s, $emailtext);
		$emailtext 		= preg_replace("/last_name/i", $last_name_s, $emailtext);		
		$emailsubject 	= preg_replace("/item_name/i", $item_name, $emailsubject);
		//--------------------------------------
  		
        wp_mail( $payer_email, $emailsubject, $emailtext, $headers);
        
		$emailsubject = $emailsubject . " (copy)";        
        wp_mail( $toaddress, $emailsubject, $emailtext, $headers);        
        

		if ($numberofkeycodes <= $lowerlimit) {		
			//send email to administrator saying that lower limit has been reached.
			$emailtext = "You have reached the lower limit for key codes in the product item : " . $item_number;
			$emailsubject = "Key code lower limit reached (PayPal message 1)";
            wp_mail( $toaddress, $emailsubject, $emailtext, $headers);                    
		}         
	
		$keycodes_s = $keycodes;

	}
    else
    {
    
    	//send email to administrator saying that lower limit has been reached.
		$emailtext = "You have reached the lower limit for key codes in the product item : " . $item_number;
		$emailsubject = "Key code lower limit reached (PayPal message 2)";
        wp_mail( $toaddress, $emailsubject, $emailtext, $headers);
    
    }
	
	$payment_status = "PAYPAL " . $payment_status;
      
  
	if (KEYCODES_DEBUG == "TRUE") {
		wp_mail( $toaddress, "Test 7", format_post($paypal), $headers );
	}	
	

	/*	  
    global $wpdb;
    $table_name = $wpdb->prefix . "keycodes_saleshistory";
            
	$data = array( 
					'licencecodes' => $keycodes_s, 
					'business' => $business_s, 
					'receiver_id' => $receiver_id_s,
					'invoice' => $invoice_s,
					'memo' => $memo_s,
					'tax' => $tax_s,
					'option_name1' => $option_name1_s,
					'option_selection1' => $option_selection1_s,
					'option_name2' => $option_name2_s, 
					'option_selection2' => $option_selection2_s,
					
					'num_cart_items' => $num_cart_items_s,
					'mc_fee' => $mc_fee_s,
					'payment_date' => $payment_date_s,
					'payment_type' => $payment_type_s,
					'first_name' => $first_name_s,
					'last_name' => $last_name_s,
					'payer_business_name' => $payer_business_name_s,
					'address_name' => $address_name_s,
					'address_street' => $address_street_s,
					'address_city' => $address_city_s,
					
					'address_state' => $address_state_s,
					'address_zip' => $address_zip_s,
					'address_country' => $address_country_s,
					'address_status' => $address_status_s, 
					'payer_id' => $payer_id_s,
					'payer_status' => $payer_status_s,
					'notify_version' => $notify_version_s,
					'verify_sign' => $verify_sign_s,
					'custom' => $custom_s,
					//'item_id' => $recid,
					
					'txn_id' => $txn_id,
					'item_number' => $item_number,
					'item_name' => $item_name_s,
					'payment_status' => $payer_status,
					'mc_gross' => $mc_gross,
					'mc_currency' => $mc_currency,
					'receiver_email' => $receiver_email_s,
					'payer_email' => $payer_email_s,
					'completed' => $currentdate,
					'quantity' => $quantity					
										
					);
															
	$format = array(
					'%s', 	//string licencecodes
					'%s', 	//string business
					'%s', 	//string receiver_id
					'%s', 	//string invoice
					'%s', 	//string memo
					'%s', 	//string tax
					'%s', 	//string option_name1
					'%s', 	//string option_selection1
					'%s', 	//string option_name2
					'%s', 	//string option_selection2																														

					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',

					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					//'%s',

					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s'
					
					);
																													
   	$rows_affected = $wpdb->insert( $table_name, $data, $format );
	*/


	global $wpdb;
	$table_name = $wpdb->prefix . "keycodes_saleshistory";
	$rows_affected = $wpdb->query( $wpdb->prepare( 
		"
			INSERT INTO $table_name
			(
			licencecodes,
			business,
			receiver_id,
			invoice,
			memo,
			tax,
			option_name1,
			option_selection1,
			option_name2,
			option_selection2,
					
			num_cart_items,
			mc_fee,
			payment_date,
			payment_type,
			first_name,
			last_name,
			payer_business_name,
			address_name,
			address_street,
			address_city,
					
			address_state,
			address_zip,
			address_country,
			address_status, 
			payer_id,
			payer_status,
			notify_version,
			verify_sign,
			custom,	
					
			txn_id,
			item_number,
			item_name,
			payment_status,
			mc_gross,
			mc_currency,
			receiver_email,
			payer_email,
			completed,
			quantity
			)

			VALUES
			(
			'%s', 
			'%s', 
			'%s', 
			'%s', 
			'%s', 
			'%s', 
			'%s', 
			'%s', 
			'%s', 
			'%s', 

			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',

			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',	

			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s'
			)
		", 
    	    array(
		
			$keycodes_s, 
			$business_s, 
			$receiver_id_s,
			$invoice_s,
			$memo_s,
			$tax_s,
			$option_name1_s,
			$option_selection1_s,
			$option_name2_s, 
			$option_selection2_s,
					
			$num_cart_items_s,
			$mc_fee_s,
			$payment_date_s,
			$payment_type_s,
			$first_name_s,
			$last_name_s,
			$payer_business_name_s,
			$address_name_s,
			$address_street_s,
			$address_city_s,
					
			$address_state_s,
			$address_zip_s,
			$address_country_s,
			$address_status_s, 
			$payer_id_s,
			$payer_status_s,
			$notify_version_s,
			$verify_sign_s,
			$custom_s,	
					
			$txn_id,
			$item_number,
			$item_name_s,
			$payer_status,
			$mc_gross,
			$mc_currency,
			$receiver_email_s,
			$payer_email_s,
			$currentdate,
			$quantity			
			
			) 
		) );


	if (KEYCODES_DEBUG == "TRUE") {
		wp_mail( $toaddress, "Test 8", $wpdb->last_query, $headers );	
	}
	//exit;				
			
    //$query = " INSERT INTO " . $dbprefix . "key_tblsaleshistory ";
    //$query .= " ( ";
    //$query .= " licencecodes, business, receiver_id, invoice, memo, tax, option_name1, option_selection1, option_name2, option_selection2,  num_cart_items, mc_fee, payment_date, payment_type, ";
    //$query .= " first_name, last_name, payer_business_name, address_name, address_street, address_city, address_state, address_zip, address_country, address_status, payer_id, payer_status, ";
    //$query .= " notify_version, verify_sign, custom,  item_id, txn_id, item_number, item_name, payment_status, mc_gross, mc_currency, receiver_email, payer_email, ";
    //$query .= " completed, quantity ";
    //$query .= " ) ";
    //$query .= " VALUES ";
    //$query .= " ( ";
    //$query .= "	'$keycodes_s', '$business_s', '$receiver_id_s', '$invoice_s', '$memo_s', '$tax_s', '$option_name1_s', '$option_selection1_s', '$option_name2_s', '$option_selection2_s', '$num_cart_items_s', ";
    //$query .= " '$mc_fee_s', '$payment_date_s', '$payment_type_s', '$first_name_s', '$last_name_s', '$payer_business_name_s', '$address_name_s', '$address_street_s', '$address_city_s', '$address_state_s', ";
    //$query .= " '$address_zip_s', '$address_country_s', '$address_status_s', '$payer_id_s', '$payer_status_s', '$notify_version_s', '$verify_sign_s', '$custom_s', $recid, '$txn_id', '$item_number', ";
    //$query .= " '$item_name_s', '$payment_status', '$mc_gross', '$mc_currency', '$receiver_email_s', '$payer_email_s', '$currentdate', $quantity "; 
    //$query .= " ) ";	
    
	
    if ($rows_affected == 0)
    {
        $emailsubject = "SQL error - functions.php ref 1";
        $emailtext = "Error saving to saleshistory table";
        wp_mail( $toaddress, $emailsubject, $emailtext, $headers);
		exit;	
    }	
	
	
	if ($testmode == "true")
	{
		//???????
	}
	
   
}
//-----------------------------------------------------------------
/**
* Purpose : Checks if the txn_id has already been used
* Inputs : The txn_id to check against
* Outputs : The number of records found
* @return integer
*/
function checktxnid($txn_id, $toaddress, $fromaddress, $testmode) {
		
	$scriptPath = dirname(__FILE__);
	$path = realpath($scriptPath . '/./');
	$filepath = split("wp-content", $path);
	
	require_once(''.$filepath[0].'wp-blog-header.php');

	//Get item details
	global $wpdb;	
	$table_name = $wpdb->prefix . "keycodes_saleshistory";
	//$row = $wpdb->get_row( "SELECT * FROM " . $table_name . " WHERE txn_id = '" . $txn_id . "' " );

	$row = $wpdb->get_row( $wpdb->prepare( 
		"
			SELECT * FROM $table_name WHERE txn_id = %s
		", 
    	    array(
			$txn_id			
		) 
	) );

	//wp_mail( $toaddress, "Test - process", "SELECT * FROM " . $table_name . " WHERE txn_id = '" . $txn_id . "' ", $headers);
	//exit;

	return count($row);

}
//----------------------------------------------------------
/**
* Purpose	:	Get tblitems details given the recid of the record
* Outputs	:	Certain details from tblitems in an array
* @return array
*/
function itemdetails($item_number) {

	$scriptPath = dirname(__FILE__);
	$path = realpath($scriptPath . '/./');
	$filepath = split("wp-content", $path);
	
	require_once(''.$filepath[0].'wp-blog-header.php');

	//Get item details
	global $wpdb;	
	$table_name = $wpdb->prefix . "keycodes_items";
	//$row = $wpdb->get_row( "SELECT * FROM " . $table_name . " WHERE item_number = '" . $item_number . "' " );

	$row = $wpdb->get_row( $wpdb->prepare( 
		"
			SELECT * FROM $table_name WHERE item_number = %s
		", 
    	    array(
			$item_number			
		) 
	) );

	if ( $row )
	{
			
		$array_name['recid']				= $row->recid;			
		$array_name['item_name']			= $row->item_name;
		$array_name['item_number']			= $row->item_number;
		$array_name['mc_gross']				= $row->mc_gross;
		$array_name['item_number']			= $row->item_number;
		$array_name['mc_currency']			= $row->mc_currency;
		$array_name['emailsubject']			= $row->emailsubject;
		$array_name['emailtextkeycodes']	= $row->emailtextkeycodes;
		
	}
	
	return $array_name;
	
}
?>