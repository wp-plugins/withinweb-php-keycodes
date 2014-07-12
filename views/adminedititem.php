<div class="wrap">
        
	<h2><strong>KeyCodes Edit Item</strong></h2>
	<p>Edit the record.</p>   

	<?php
	
	//---------------------
	//http://wordpress.stackexchange.com/questions/135692/creating-a-wordpress-admin-page-without-a-menu-for-a-plugin
	//---------------------

	//---------------------
	//http://codex.wordpress.org/Function_Reference/check_admin_referer
	//---------------------
	
	//---------------------	
	//http://codex.wordpress.org/Function_Reference/wp_nonce_url
	//---------------------
	
	//Check for verification nonce	
	if ( !isset($_GET['recid'] ) || !wp_verify_nonce($_GET['refid'], 'edit_page')) {
		//invalid click through
		echo("invalid click through");
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
	$table_name = $wpdb->prefix . "keycodes_items";
	//$row = $wpdb->get_row( "SELECT * FROM " . $table_name . " WHERE recid = " . $recid );
			
	$row = $wpdb->get_row( $wpdb->prepare( 
		"
			SELECT * FROM $table_name WHERE recid = %d
		", 
		array (
			$recid
		)
	) );
	

	if ( $row )
	{

   	    if ( isset( $_GET['m'] ) && $_GET['m'] == '1' )
        	{
	    	?>
    	       <div id='message' class='updated fade'><p><strong>You have successfully updated the item.</strong></p></div>
	    	<?php
	   	    }
        
   	    if ( isset( $_GET['m'] ) && $_GET['m'] == '2' )
        	{
	    	?>
    	        <div id='message' class='error'><p><strong>Gross value must be two decimal places - no data has been saved.</strong></p></div>
	    	<?php
	   	    }
	    ?>    

	  	<p>
    		<strong>Edit item:</strong>
	  	</p>

   		<form method="post" action="admin-post.php">
    
			<input type="hidden" name="action" value="withinweb_keycodes_edititem" />
    
			<?php wp_nonce_field ( 'withinweb_keycodes_op_verify', 'withinweb_keycodes_edititem' ); ?>
     
     		<input type="hidden" name="recid" value="<?php echo($recid) ?>" />     
        
      		<table class="form-table">
				<tbody>
                
	            	<tr class="form-field form-required">
			        	<th scope="row"><label for="item_number">Item number <span class="description">(required)</span></label></th>
			    	    <td><input type="text" id="item_number" name="item_number" value="<?php echo( $row->item_number ); ?>" style="width:200px;" ></td>
        			</tr>                
                
	            	<tr class="form-field form-required">
			        	<th scope="row"><label for="item_name">Item name <span class="description">(required)</span></label></th>
			    	    <td><input type="text" id="item_name" name="item_name" value="<?php echo( $row->item_name ); ?>"  style="width:200px;"></td>
        			</tr>

	            	<tr class="form-field form-required">
			        	<th scope="row"><label for="item_title">Item title <span class="description">(required)</span></label></th>
			    	    <td><input type="text" id="item_title" name="item_title" value="<?php echo( $row->item_title ); ?>" style="width:250px;" ></td>
        			</tr>
                
	            	<tr class="form-field form-required">
			        	<th scope="row"><label for="item_description">Item Description <span class="description">(required)</span></label></th>
			    	    <td><input type="text" id="item_description" name="item_description" value="<?php echo( $row->item_description ); ?>" style="width:250px;" ></td>
        			</tr>    

	           		<tr class="form-field form-required">
			        	<th scope="row"><label for="mc_gross">Gross <span class="description">(required)</span></label></th>
			    	    <td><input type="text" id="mc_gross" name="mc_gross" value="<?php echo( $row->mc_gross ); ?>" style="width:70px;" ></td>
	        		</tr>                    
                
    		       		<tr class="form-field form-required">
		    	    	<th scope="row"><label for="mc_gross">Currency <span class="description">(required)</span></label></th>
		    		    <td><input type="text" id="mc_currency" name="mc_currency" value="<?php echo( $row->mc_currency ); ?>" style="width:70px;" ></td>
	        		</tr>                   
                
    	       		<tr class="form-field form-required">
			        	<th scope="row"><label for="emailsubject">Email Subject <span class="description">(required)</span></label></th>
		    		    <td><input type="text" id="emailsubject" name="emailsubject" value="<?php echo( $row->emailsubject ); ?>" style="width:400px;" ></td>
	        		</tr>                   
				
    	       		<tr class="form-field form-required">
			        	<th scope="row"><label for="emailtextkeycodes">Email Text <span class="description">(required)</span></label></th>
		    		    <td><textarea id="emailtextkeycodes" name="emailtextkeycodes" style="width:350px;" rows="6" ><?php echo( $row->emailtextkeycodes ); ?></textarea></td>
	        		</tr>                   
					
        	   		<tr class="form-field form-required">
		    	    	<th scope="row"><label for="lowerlimit">Lower Limit <span class="description">(required)</span></label></th>
		    		    <td><input type="text" id="lowerlimit" name="lowerlimit" value="<?php echo( $row->lowerlimit ); ?>" style="width:50px;" ></td>
	        		</tr>                   
                
    	       		<tr class="form-field form-required">
			        	<th scope="row"><label for="keycodes">Key Codes <span class="description">(required)</span></label></th>
		    		    <td><textarea id="keycodes" name="keycodes" style="width:350px;" rows="6"><?php echo( $row->keycodes ); ?></textarea></td>
	        		</tr>            

		  		</tbody>
			</table>
         
        	<p class="submit">
	        <input type="submit" value="Update Item" class="button-primary"/>
    	    </p>    
   
    	</form>
            
		<?php
	}
	else
	{
		?>
		<h2>No items found</h2>
		<?php	
	}

?>