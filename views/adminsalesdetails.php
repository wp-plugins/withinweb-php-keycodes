<div class="wrap">
        
	<h2><strong>KeyCodes Sales detail</strong></h2>
	<p>Lists the sales details for this item.</p>   

	<?php

	//Check for verification nonce
	if ( !isset($_GET['recid']) || !wp_verify_nonce($_GET['refid'], 'salesdetails_page')) {
		//invalid click through
		exit;
	}

	$recid = $_GET['recid'];
	
	//Check if nummeric value
	if ( !is_numeric($recid) )
	{
		exit;	
	}	
	
	//Get details for this record and display it.
	global $wpdb;	
	$table_name = $wpdb->prefix . "keycodes_saleshistory";
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
	
		?>
    
      		<table class="form-table">
				<tbody>
	            	<tr class="form-field form-required">
			        	<th scope="row"><label for="receiver_email">receiver_email <span class="description"></span></label></th>
			    	    <td><?php echo( $row->receiver_email ); ?></td>
        			</tr>
                
	            	<tr class="form-field form-required">
			        	<th scope="row"><label for="item_name">item_name <span class="description"></span></label></th>
			    	    <td><?php echo( $row->item_name ); ?></td>
        			</tr>
                
	            	<tr class="form-field form-required">
			        	<th scope="row"><label for="item_name">item_name <span class="description"></span></label></th>
			    	    <td><?php echo( $row->item_number ); ?></td>
        			</tr>                
                
	            	<tr class="form-field form-required">
			        	<th scope="row"><label for="payment_status">payment_status <span class="description"></span></label></th>
			    	    <td><?php echo( $row->payment_status ); ?></td>
        			</tr>              

	            	<tr class="form-field form-required">
			        	<th scope="row"><label for="payment_status">mc_gross <span class="description"></span></label></th>
			    	    <td><?php echo( $row->mc_gross ); ?></td>
        			</tr>

	            	<tr class="form-field form-required">
			        	<th scope="row"><label for="payer_email">payer_email <span class="description"></span></label></th>
			    	    <td><?php echo( $row->payer_email ); ?></td>
        			</tr>                    

	            	<tr class="form-field form-required">
			        	<th scope="row"><label for="txn_type">txn_type <span class="description"></span></label></th>
			    	    <td><?php echo( $row->txn_type ); ?></td>
        			</tr> 

	            	<tr class="form-field form-required">
			        	<th scope="row"><label for="txn_id">txn_id <span class="description"></span></label></th>
			    	    <td><?php echo( $row->txn_id ); ?></td>
        			</tr> 
		
	            	<tr class="form-field form-required">
			        	<th scope="row"><label for="mc_currency">mc_currency <span class="description"></span></label></th>
			    	    <td><?php echo( $row->mc_currency ); ?></td>
        			</tr> 		

	            	<tr class="form-field form-required">
			        	<th scope="row"><label for="completed">completed <span class="description"></span></label></th>
			    	    <td><?php echo( $row->completed ); ?></td>
        			</tr> 	

	            	<tr class="form-field form-required">
			        	<th scope="row"><label for="quantity">quantity <span class="description"></span></label></th>
			    	    <td><?php echo( $row->quantity ); ?></td>
        			</tr> 	 
                    
	            	<tr class="form-field form-required">
			        	<th scope="row"><label for="licencecodes">licencecodes <span class="description"></span></label></th>
			    	    <td><?php echo( $row->licencecodes ); ?></td>
        			</tr> 	                     

	            	<tr class="form-field form-required">
			        	<th scope="row"><label for="business">business <span class="description"></span></label></th>
			    	    <td><?php echo( $row->business ); ?></td>
        			</tr>

	            	<tr class="form-field form-required">
			        	<th scope="row"><label for="receiver_id">receiver_id <span class="description"></span></label></th>
			    	    <td><?php echo( $row->receiver_id ); ?></td>
        			</tr>

	            	<tr class="form-field form-required">
			        	<th scope="row"><label for="invoice">invoice <span class="description"></span></label></th>
			    	    <td><?php echo( $row->invoice ); ?></td>
        			</tr>

	            	<tr class="form-field form-required">
			        	<th scope="row"><label for="custom">custom <span class="description"></span></label></th>
			    	    <td><?php echo( $row->custom ); ?></td>
        			</tr>

	            	<tr class="form-field form-required">
			        	<th scope="row"><label for="memo">memo <span class="description"></span></label></th>
			    	    <td><?php echo( $row->memo ); ?></td>
        			</tr>

	            	<tr class="form-field form-required">
			        	<th scope="row"><label for="tax">tax <span class="description"></span></label></th>
			    	    <td><?php echo( $row->tax ); ?></td>
        			</tr>

	            	<tr class="form-field form-required">
			        	<th scope="row"><label for="option_name1">option_name1 <span class="description"></span></label></th>
			    	    <td><?php echo( $row->option_name1 ); ?></td>
        			</tr>

	            	<tr class="form-field form-required">
			        	<th scope="row"><label for="option_selection1">option_selection1 <span class="description"></span></label></th>
			    	    <td><?php echo( $row->option_selection1 ); ?></td>
        			</tr>
                    
	            	<tr class="form-field form-required">
			        	<th scope="row"><label for="option_name2">option_name2 <span class="description"></span></label></th>
			    	    <td><?php echo( $row->option_name2 ); ?></td>
        			</tr>                    

	            	<tr class="form-field form-required">
			        	<th scope="row"><label for="option_selection2">option_selection2 <span class="description"></span></label></th>
			    	    <td><?php echo( $row->option_selection2 ); ?></td>
        			</tr>       

	            	<tr class="form-field form-required">
			        	<th scope="row"><label for="num_cart_items">num_cart_items <span class="description"></span></label></th>
			    	    <td><?php echo( $row->num_cart_items ); ?></td>
        			</tr>       

	            	<tr class="form-field form-required">
			        	<th scope="row"><label for="mc_fee">mc_fee <span class="description"></span></label></th>
			    	    <td><?php echo( $row->mc_fee ); ?></td>
        			</tr>

	            	<tr class="form-field form-required">
			        	<th scope="row"><label for="payment_date">payment_date <span class="description"></span></label></th>
			    	    <td><?php echo( $row->payment_date ); ?></td>
        			</tr>

	            	<tr class="form-field form-required">
			        	<th scope="row"><label for="payment_type">payment_type <span class="description"></span></label></th>
			    	    <td><?php echo( $row->payment_type ); ?></td>
        			</tr>
                    
	            	<tr class="form-field form-required">
			        	<th scope="row"><label for="first_name">first_name <span class="description"></span></label></th>
			    	    <td><?php echo( $row->first_name ); ?></td>
        			</tr>

 	            	<tr class="form-field form-required">
			        	<th scope="row"><label for="last_name">last_name <span class="description"></span></label></th>
			    	    <td><?php echo( $row->last_name ); ?></td>
        			</tr>
 
  	            	<tr class="form-field form-required">
			        	<th scope="row"><label for="payer_business_name">payer_business_name <span class="description"></span></label></th>
			    	    <td><?php echo( $row->payer_business_name ); ?></td>
        			</tr>
                    
 	            	<tr class="form-field form-required">
			        	<th scope="row"><label for="address_name">address_name <span class="description"></span></label></th>
			    	    <td><?php echo( $row->address_name ); ?></td>
        			</tr>                    

  	            	<tr class="form-field form-required">
			        	<th scope="row"><label for="address_street">address_street <span class="description"></span></label></th>
			    	    <td><?php echo( $row->address_street ); ?></td>
        			</tr>
                       
 	            	<tr class="form-field form-required">
			        	<th scope="row"><label for="address_city">address_city <span class="description"></span></label></th>
			    	    <td><?php echo( $row->address_city ); ?></td>
        			</tr>   

 	            	<tr class="form-field form-required">
			        	<th scope="row"><label for="address_state">address_state <span class="description"></span></label></th>
			    	    <td><?php echo( $row->address_state ); ?></td>
        			</tr>   

 	            	<tr class="form-field form-required">
			        	<th scope="row"><label for="address_zip">address_zip <span class="description"></span></label></th>
			    	    <td><?php echo( $row->address_zip ); ?></td>
        			</tr>   
                    
  	            	<tr class="form-field form-required">
			        	<th scope="row"><label for="address_country">address_country <span class="description"></span></label></th>
			    	    <td><?php echo( $row->address_country ); ?></td>
        			</tr> 
                    
  	            	<tr class="form-field form-required">
			        	<th scope="row"><label for="address_status">address_status <span class="description"></span></label></th>
			    	    <td><?php echo( $row->address_status ); ?></td>
        			</tr> 
                    
   	            	<tr class="form-field form-required">
			        	<th scope="row"><label for="payer_id">payer_id <span class="description"></span></label></th>
			    	    <td><?php echo( $row->payer_id ); ?></td>
        			</tr>    
                                    
   	            	<tr class="form-field form-required">
			        	<th scope="row"><label for="payer_status">payer_status <span class="description"></span></label></th>
			    	    <td><?php echo( $row->payer_status ); ?></td>
        			</tr>     
 
   	            	<tr class="form-field form-required">
			        	<th scope="row"><label for="notify_version">notify_version <span class="description"></span></label></th>
			    	    <td><?php echo( $row->notify_version ); ?></td>
        			</tr>    
                    
    	            <tr class="form-field form-required">
			        	<th scope="row"><label for="verify_sign">verify_sign <span class="description"></span></label></th>
			    	    <td><?php echo( $row->verify_sign ); ?></td>
        			</tr>                    
                                  
		  		</tbody>
			</table>

		<?php		

	}
	else
	{
		?>
		<h2>No items found</h2>
		<?php
	}
	?>
     
</div>