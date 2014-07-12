<div class="wrap">

	<h2><strong>Local Test</strong></h2>
	<p>Test an item locally, not through PayPal</p>     
    
    <?php
        if ( isset( $_GET['m'] ) && $_GET['m'] == '1' )
        {
    ?>
		<div id='message' class='updated fade'><p><strong>A local test has been actioned - check your email.</strong></p></div>
    <?php
        }
    ?>


   	<form method="post" action="admin-post.php">
    
		<input type="hidden" name="action" value="withinweb_keycodes_localtest" />
    
		<?php wp_nonce_field( 'withinweb_keycodes_op_verify', 'withinweb_keycodes_localtest' ); ?>        
        
		<?php $options = get_option( 'withinweb_keycodes_op_array' ); ?>    
        
        
      	<table class="form-table">
			<tbody>
            
                <tr class="form-field form-required">
		        	<th scope="row"><label for="receiver_email">Receiver email <span class="description"></span> This is the email address which PayPal uses to send confirmation emails, normally it is your email address</label></th>
		    	    <td><input type="text" id="receiver_email" name="receiver_email" value="<?php echo esc_html( $options['withinweb_keycodes_paypal_email'] ); ?>" style="width:200px;"></td>
        		</tr>
            
            	<tr class="form-field form-required">
		        	<th scope="row"><label for="item_number">Item number reference <span class="description"></span>  The item number reference of the item.</label></th>
		    	    <td>
                    	
                        <?php

							//Get item names for drop down list.
							//global $wpdb;	
							//$table_name = $wpdb->prefix . "keycodes_items";
							//$items = $wpdb->get_row( "SELECT recid, item_name FROM " . $table_name . " ORDER BY item_name " );

							global $wpdb;
							$table_name = $wpdb->prefix . "keycodes_items";
							//$items = $wpdb->get_results( "SELECT * FROM " . $table_name . " ORDER BY item_name " );
							
							
							$items = $wpdb->get_results( 
								$wpdb->prepare(
									"
										SELECT * FROM $table_name ORDER BY item_name 
									",
									array()
								) );							
							

							if ( $items )
							{
						
								//echo( count($items) );
								//exit;
						
								?>
		                        <select name="item_number">

        	                    <?php						

									foreach ( $items as $row )
									{
										?>
										<option value="<?php echo( $row->item_number ); ?>"><?php echo( $row->item_number ); ?></option>
										<?php		
									}	
							
								?>
                            
								</select>                        

								<?php
							}
							else
							{
								echo("No items found");	
							}
						?>
                        
                  	</td>
        		</tr>
                
               	<tr class="form-field form-required">
		        	<th scope="row"><label for="payment_status">Payment status <span class="description"></span> Defines the status from Paypal - selecting completed will simulate a completed transaction.</label></th>
		    	    <td>                    	
   						<select name="payment_status">
							<option value=""></option>
							<option value="Completed" selected>Completed</option>
							<option value="Pending">Pending</option>
							<option value="Failed">Failed</option>
							<option value="Denied">Denied</option>
						</select>    
       				</td>
        		</tr>
        
                <tr class="form-field form-required">
		        	<th scope="row"><label for="mc_gross">Payment gross <span class="description"></span> The gross value of the goods which must be the same as entered into the database.</label></th>
		    	    <td>                   
                    	<input type="text" id="mc_gross" name="mc_gross" style="width:100px;">                       
       				</td>
        		</tr>

                <tr class="form-field form-required">
		        	<th scope="row"><label for="mc_currency">Currency Code <span class="description"></span> The currency code which must be the same as entered into the database.</label></th>
		    	    <td>
                        
   						<select id="mc_currency" name="mc_currency">
							<option value=""></option>                      
                       		<option value="AUD">AUD</option>
                       		<option value="CAD">CAD</option>
                       		<option value="CSK">CSK</option>
                       		<option value="DKK">DKK</option>
                       		<option value="EUR">EUR</option>
                       		<option value="HKD">HKD</option>
                      		<option value="ILS">ILS</option>
                     		<option value="JPY">JPY</option>
                     		<option value="MXN">MXN</option>
                    		<option value="NOK">NOK</option>
                    		<option value="NZD">NZD</option>
                    		<option value="PHP">PHP</option>
                    		<option value="PLN">PLN</option>
                    		<option value="GBP">GBP</option>
                    		<option value="SGD">SGD</option>
                    		<option value="SEK">SEK</option>
                    		<option value="CHF">CHF</option>
                    		<option value="THB">THB</option>
                    		<option value="USD" selected>USD</option>
						</select>                     
                                         
       				</td>
        		</tr>        
        
                <tr class="form-field form-required">
		        	<th scope="row"><label for="txn_id">Transaction ID <span class="description">(required)</span> The PayPal transaction id. You can enter any random series of characters here, I place the current time and date.</label></th>
		    	    <td>                   
                    	<input type="text" id="txn_id" name="txn_id" style="width:200px;" value="<?php echo (date( "d/m/y G:i:s" )); ?>" >                       
       				</td>
        		</tr>  
        
                <tr class="form-field form-required">
                	<th scope="row"><label for="txn_type">Transaction Type <span class="description"></span></label></th>
                	<td>
				        <select name="txn_type">
							<option value=""></option>
							<option value="web_accept" selected>Web accept</option>
							<option value="cart">Cart</option>
							<option value="send_money">Send Money</option>
						</select>                    
                    </td>
				</tr>
        
                <tr class="form-field form-required">
                	<th scope="row"><label for="txn_type">Payer email</strong> This is the email address of the purchaser.  
					For this test, enter a personal email address so that you can see the resulting emails. <span class="description"></span></label></th>
                	<td>
				  		<input type="text" id="payer_email" name="payer_email" style="width:200px;">       
                    </td>
				</tr>        
        
	  		</tbody>
		</table>
         
        <p class="submit">
        <input type="submit" value="Submit The Test" class="button-primary"/>
        </p>        
    
   
    </form>

