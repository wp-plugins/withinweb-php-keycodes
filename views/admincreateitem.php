<div class="wrap">

	<h2><strong>Create a new item</strong></h2>
	<p>This page creates a new item.</p>     
    
    <?php
        if ( isset( $_GET['m'] ) && $_GET['m'] == '1' )
        {
    ?>
           <div id='message' class='updated fade'><p><strong>You have successfully created a new item.</strong></p></div>
    <?php
        }
    ?>    

    <?php
        if ( isset( $_GET['m'] ) && $_GET['m'] == '2' )
        {
    ?>
           <div id='message' class='error'><p><strong>Lower Limit must be an integer value - no data has been saved.</strong></p></div>
    <?php
        }
    ?>
    
    <?php
        if ( isset( $_GET['m'] ) && $_GET['m'] == '3' )
        {
    ?>
           <div id='message' class='error'><p><strong>Gross value must be two decimal places - no data has been saved.</strong></p></div>
    <?php
        }
    ?>     
    
    <?php
        if ( isset( $_GET['m'] ) && $_GET['m'] == '4' )
        {
    ?>
           <div id='message' class='error'><p><strong>Either item name or itme number are duplicated values- no data has been saved.</strong></p></div>
    <?php
        }
    ?>         

  	<p>
    	<strong>Create item:</strong>
  	</p>

   	<form method="post" action="admin-post.php">
    
		<input type="hidden" name="action" value="withinweb_keycodes_createitem" />
    
		<?php wp_nonce_field( 'withinweb_keycodes_op_verify', 'withinweb_keycodes_createitem' ); ?>
        
      	<table class="form-table">
			<tbody>
            	<tr class="form-field form-required">
		        	<th scope="row"><label for="item_name">Item name <span class="description">(required)</span></label></th>
		    	    <td><input type="text" id="item_name" name="item_name"  style="width:200px;"></td>
        		</tr>
                
            	<tr class="form-field form-required">
		        	<th scope="row"><label for="item_number">Item number <span class="description">(required)</span></label></th>
		    	    <td><input type="text" id="item_number" name="item_number" style="width:200px;" ></td>
        		</tr>

            	<tr class="form-field form-required">
		        	<th scope="row"><label for="item_title">Item title <span class="description">(required)</span></label></th>
		    	    <td><input type="text" id="item_title" name="item_title" style="width:250px;" ></td>
        		</tr>
                
            	<tr class="form-field form-required">
		        	<th scope="row"><label for="item_description">Item Description <span class="description">(required)</span></label></th>
		    	    <td><input type="text" id="item_description" name="item_description" style="width:250px;" ></td>
        		</tr>    

           		<tr class="form-field form-required">
		        	<th scope="row"><label for="mc_gross">Gross <span class="description">(required)</span></label></th>
		    	    <td><input type="text" id="mc_gross" name="mc_gross" style="width:70px;" ></td>
        		</tr>                    
                
           		<tr class="form-field form-required">
		        	<th scope="row"><label for="mc_gross">Currency <span class="description">(required)</span></label></th>
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
		        	<th scope="row"><label for="emailsubject">Email Subject</strong> This is the subject of the email when an item is purchased.  
            			<br/>The item name is substituted for item_name when the email is sent. <span class="description">(required)</span></label></th>
		    	    <td><input type="text" id="emailsubject" name="emailsubject" style="width:400px;" value="Subject line of email sent to purchaser for item_name" ></td>
        		</tr>
				
           		<tr class="form-field form-required">
		        	<th scope="row"><label for="emailtextkeycodes">Email Text for key codes / pins / software licences</strong>
            		<br/>The actual codes are substituted for key_codes when the email is sent.<span class="description">(required)</span></label></th>
		    	    <td><textarea id="emailtextkeycodes" name="emailtextkeycodes" style="width:350px;" rows="6" >A suitable entry for the body text of the email for key codes / pin codes / licence codes similar to the following :

To : email_address first_name last_name

Your key code(s) are listed here :

key_codes</textarea></td>
        		</tr>                   
				
           		<tr class="form-field form-required">
		        	<th scope="row"><label for="lowerlimit">Lower Limit <span class="description">(required)</span></label></th>
		    	    <td><input type="text" id="lowerlimit" name="lowerlimit" value="5" style="width:50px;" ></td>
        		</tr>                   
                
           		<tr class="form-field form-required">
		        	<th scope="row"><label for="keycodes">Key Codes <span class="description">(required)</span></label></th>
		    	    <td><textarea id="keycodes" name="keycodes" style="width:350px;" rows="6"></textarea></td>
        		</tr>            

	  		</tbody>
		</table>
         
        <p class="submit">
        <input type="submit" value="Create Item" class="button-primary"/>
        </p>        
    
   
    </form>

</div>