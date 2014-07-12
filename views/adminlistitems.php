<div class="wrap">

		<?php

 	    if ( isset( $_GET['m'] ) && $_GET['m'] == '1' )
        	{
	    	?>
    	       <div id='message' class='updated fade'><p><strong>You have successfully updated the item.</strong></p></div>
	    	<?php
	   	    }
	    ?> 


	<h2><strong>KeyCodes Items</strong></h2>
	<p>List of items.</p>        
	
	<?php 	
	
	global $wpdb;	
	$table_name = $wpdb->prefix . "keycodes_items";
	//$items = $wpdb->get_results( "SELECT * FROM " . $table_name );	
	
	$items = $wpdb->get_results( 
		$wpdb->prepare(
			"
				SELECT * FROM $table_name
			",
			array()
		) );
	
	if ( $items )
	{
	
		?>
        <table cellspacing="0" class="wp-list-table widefat fixed">
		<thead>
        <tr>
        	<th class="manage-column">Record id</td>
        	<th class="manage-column">Item Number</td>
        	<th class="manage-column">Item Name</td>        
			<th class="manage-column">Item Title</td>
          	<th class="manage-column">Gross</td>            
          	<th class="manage-column">Currency</td> 
          	<th class="manage-column">Lower Limit</td>
			<th class="manage-column">Short Code</td>
      		<th class="manage-column">Details</td>
        </tr>
        </thead>
        
      	<tfoot>
        <tr>
        	<th class="manage-column">Record id</td>              
        	<th class="manage-column">Item Number</td>          
        	<th class="manage-column">Item Name</td>          
			<th class="manage-column">Item Title</td>			 
          	<th class="manage-column">Gross</td>            
          	<th class="manage-column">Currency</td> 
          	<th class="manage-column">Lower Limit</td>
			<th class="manage-column">Short Code</td>
     		<th class="manage-column">Details</td>            
        </tr>
      	</tfoot>        
        <?php
	
		foreach ( $items as $row )
		{		
			?>
            <tbody>
			<tr class="alternate">
				<td class="role column-role"><?php echo( $row->recid ); ?></td>                      
				<td class="role column-role"><?php echo( $row->item_number ); ?></td>          
				<td class="role column-role"><?php echo( $row->item_name ); ?></td>          
				<td class="role column-role"><?php echo( $row->item_title ); ?></td>			
				<td class="role column-role"><?php echo( $row->mc_gross ); ?></td>			
				<td class="role column-role"><?php echo( $row->mc_currency ); ?></td>
				<td class="role column-role"><?php echo( $row->lowerlimit ); ?></td>
              
                <th class="manage-column">[keycodesbutton recid="<?php echo( $row->recid ); ?>"]</td>
                
				<?php 				
				//Get plug in base name
				$pluginfolder = str_replace( '/views', '',  dirname(plugin_basename(__FILE__)) );
				$complete_url_edit = wp_nonce_url( 'admin.php?page=' . $pluginfolder . '/withinweb_keycodes.php_edititem&recid='.$row->recid, 'edit_page', 'refid' ); 				
				?>                
                <td class="role column-role"><a href="<?php echo $complete_url_edit; ?>">Edit</a></td>
			</tr>
            <tbody>
            <?php
		}		
		?>
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