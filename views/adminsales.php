<div class="wrap">
        
	<h2><strong>KeyCodes Sales</strong></h2>
	<p>List of sales.</p>        
	
	<?php 
	
	$withinweb_basename = plugin_basename(__FILE__);	

	global $wpdb;	
	$table_name = $wpdb->prefix . "keycodes_saleshistory";
	//$saleshistory = $wpdb->get_results( "SELECT * FROM " . $table_name ); 	

	$saleshistory = $wpdb->get_results( $wpdb->prepare(
		"
			SELECT * FROM $table_name 
		", 
    	    array() 
	) );
	
	if ( $saleshistory )
	{
	
		?>
        <table cellspacing="0" class="wp-list-table widefat fixed">
		<thead>
        <tr>
        	<th class="manage-column">Receiver Email</td>          
        	<th class="manage-column">Item name</td>          
			<th class="manage-column">Item number</td>          
			<th class="manage-column">Payment Status</td>           
          	<th class="manage-column">Gross</td>            
          	<th class="manage-column">Payer Email</td> 
          	<th class="manage-column">Licence Codes</td>
			<th class="manage-column">Details</td>                                       
        </tr>
        </thead>
        
      	<tfoot>
        	<tr>
        		<th class="manage-column">Receiver Email</td>          
        		<th class="manage-column">Item name</td>          
				<th class="manage-column">Item number</td>          
				<th class="manage-column">Payment Status</td>           
          		<th class="manage-column">Gross</td>            
          		<th class="manage-column">Payer Email</td> 
          		<th class="manage-column">Licence Codes</td>
          		<th class="manage-column">Details</td>                
        	</tr>
      	</tfoot>        
        <?php
	
		foreach ( $saleshistory as $row )
		{		
			?>
            <tbody>
			<tr class="alternate">
				<td class="role column-role"><?php echo( $row->receiver_email ); ?></td>          
				<td class="role column-role"><?php echo( $row->item_name ); ?></td>          
				<td class="role column-role"><?php echo( $row->item_number ); ?></td>			
				<td class="role column-role"><?php echo( $row->payment_status ); ?></td>			
				<td class="role column-role"><?php echo( $row->mc_gross ); ?></td>			
				<td class="role column-role"><?php echo( $row->payer_email ); ?></td>
				<td class="role column-role"><?php echo( $row->licencecodes ); ?></td>
                
                <?php
				//Get plug in base name
				$pluginfolder = str_replace( '/views', '',  dirname(plugin_basename(__FILE__)) );
				
				$complete_url = wp_nonce_url( 'admin.php?page=' . $pluginfolder . '/withinweb_keycodes.php_salesdetails&recid='.$row->recid, 'salesdetails_page', 'refid' ); 
				?>
                <td class="role column-role"><a href="<?php echo ($complete_url) ?>">View details</a></td>
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