<?php


/**
*  PAYPAL specific
*  
*  Handles extracting the key codes from the tblitems 'keycodes' field.
*  There are three functions here which could be modified to fit in with other system of licence generation.
*  The keycodes are located in the database for each item, one key code per line.
*  First, the number of key codes is calculated and checked against the lower limit.  If the number of key codes
*  has reached the lower limit, then an email is sent to the administrator.  The top key code is extracted and
*  the remainder are saved to the database.
*  key code functions :
*  - getnoofkeycodes returns the number of key codes that are in the database for this item.
*  - getlowerlimit returns the number defined in the database for the lower limit of key codes.
*  - getnextkeycodes returns the next key code, (or key codes), and removes it, (or them), from the database.
*
*	Each function has the array $paypal which passes all received paypal data.  These variables are listed in 
*	create_local_variables() function in com_functions.php file.  So the last name of the customer would be :
*			$paypal["last_name"]
*	It would be possible to use this information to calculate a keycode based on the user details.
*/

//---------------------------------------
/**
* Given the record id of the product item, return the number of key codes that 
* are in the database field
* @param $recid is the record id of the item in tblitems
* @param $paypal is all the paypal codes but is no tused here and is provided just in case something needs to use it
* @return integer
*/
function getnoofkeycodes($paypal, $recid) {

	//Get table items details given the item_number
	global $wpdb;	
	$table_name = $wpdb->prefix . "keycodes_items";

	//$row = $wpdb->get_row( "SELECT keycodes FROM " . $table_name . " WHERE recid = " . $recid );

	$row = $wpdb->get_row( $wpdb->prepare( 
		"
			SELECT keycodes FROM $table_name WHERE recid = %d
		", 
    	    array(			
			$recid			
		) 
	) );	


	if ( $row )
	{		
		if ($row->keycodes == "")
		{
			return 0;
		}
		else
		{
			return count(preg_split("/\n/", $row->keycodes));
		}
	}
	else
	{
		return 0;
	}
}
//---------------------------------------
/**
* Given the product recid return the lower limit number of pins to identify when an email is sent out
* @param int $recid
* @param $paypal which is not used but might be used as sometime
* @return integer
*/
function getlowerlimit($paypal, $recid) {

	global $wpdb;	
	$table_name = $wpdb->prefix . "keycodes_items";
	//$row = $wpdb->get_row( "SELECT lowerlimit FROM " . $table_name . " WHERE recid = " . $recid );

	$row = $wpdb->get_row( $wpdb->prepare(
		"
			SELECT lowerlimit FROM $table_name WHERE recid = %d
		", 
    	    array(
			$recid
		) 
	) );

	if ( $row ) 
	{
		return	$row->lowerlimit;	
	}
	else
	{
		return 0;
	}
	
}
//---------------------------------------
/**
* Given the product $recid return the series of pin numbers as a string separated by crlf (\r\n)
* Delete that number from the table and save it back the remainder into the table
* @param int $recid is the record id of the product item
* @param boolean $usinghttps if true then don't delete the top item, else delete the top item
* @param int $notoretreive is the number of pins to retrieve
* @return string
*/
function getnextkeycodes($paypal, $recid, $notoretrieve, $usinghttps) {
	
	//Retrieve all pin numbers.
	//Split into an array
	//Retrieve the top $notoretrieve
	//Save back the remainder
	//Return


	//Retrieve all pin numbers
	global $wpdb;	
	$table_name = $wpdb->prefix . "keycodes_items";
	//$row = $wpdb->get_row( "SELECT keycodes FROM " . $table_name . " WHERE recid = " . $recid );

	$row = $wpdb->get_row( $wpdb->prepare(
		"
			SELECT keycodes FROM $table_name WHERE recid = %d
		", 
    	    array(
			$recid
		) 
	) );
	
	//echo($row[0]["keycodes"]);
	//exit;	
	
	//return $wpdb->last_query;
	//exit;	

	//Split into an array
	$keysarray = preg_split("/\n/", $row->keycodes );
	$keyscount = count($keysarray);
	
	//print_r($keysarray);
	//exit;
	
	//echo($keyscount);
	//echo($notoretrieve);
	//echo( $table[0]["keycodes"] );
	//exit;

	//Retrieve the top $notoretrieve
	if ($keyscount >= $notoretrieve) {

		//gets the key to return
		$keys = "";
		for ($i = 0; $i < $notoretrieve; $i++) {
			$keys .= trim($keysarray[$i]);
		}


		$keysleft = "";
		for ($i = $notoretrieve; $i < $keyscount; $i++) {
			
			if ($i == ($keyscount - 1)) {
				$keysleft .= trim($keysarray[$i]);
			} else {
				$keysleft .= trim($keysarray[$i]) . "\r\n";
			}
			
		}

		//echo($keysleft . "<br/>");
		//echo($keys);
		//exit;
		
		/*
		//update the keycodes after removing the first one
		global $wpdb;
		$table_name = $wpdb->prefix . "keycodes_items";
		$data = array( 
					'keycodes' => trim($keysleft)
					);		
		$where = array(
			 		'recid' => $recid
					);					
		$format = array(	//format of data
					'%s'
					);
		$where_format = array( 	//format of where
					'%d'
					);		
		$rows_affected = $wpdb->update( $table_name, $data, $where, $format, $where_format );
		*/

		$keycodesleft = trim($keysleft);

		//update the keycodes after removing the first one
		global $wpdb;
		$table_name = $wpdb->prefix . "keycodes_items";
		$wpdb->query( $wpdb->prepare( 
			"
				UPDATE $table_name SET
				keycodes = %s
				WHERE 
				recid = %d
			", 
	    	    array(
				$keycodesleft,
				$recid			
			) 
		) );
		

		//return $wpdb->last_query;
		//exit;


	} else 	{	//in this case, return all the list and delete everything from the table 
	
		$keys = $row->keycodes;		//return all the keycodes

		/*
		//Update the keycodes entry in field keycodes with blank
		global $wpdb;
		$table_name = $wpdb->prefix . "keycodes_items";
		$data = array( 
					'keycodes' => ''
					);		
		$where = array(
			 		'recid' => $recid
					);					
		$format = array(	//format of data
					'%s'
					);
		$where_format = array( 	//format of where
					'%d'
					);		
		$rows_affected = $wpdb->update( $table_name, $data, $where, $format, $where_format );
		*/		
		
		//update the keycodes after removing the first one
		global $wpdb;
		$table_name = $wpdb->prefix . "keycodes_items";
		$wpdb->query( $wpdb->prepare( 
			"
				UPDATE $table_name SET
				keycodes = ''
				WHERE 
				recid = %d
			", 
	    	    array(
				$recid			
			) 
		) );		
		

	}

	return $keys;

}

?>