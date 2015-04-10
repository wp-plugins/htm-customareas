<div id="admin-wrap">
    <h2>Custom Areas Options</h2>
	
	<?php if($saved): ?> 
		<div id="saved-options">
			Save successful
		</div>
	<?php endif; ?>
	
	
	<div id="tabs">
	<ul>
		<li><a href="#tabs-1">Options</a></li>
		<li><a href="#tabs-2">Instructions</a></li>	
		<li><a href="#tabs-3">About</a></li>
	</ul>
	


	<!-- The options tab --> 
	<div class="thetab" id="tabs-1">
	    <form id="htmcustomareas" action="<?php echo $this->data['form_url']; ?>" method="post" enctype="multipart/form-data">
	        <?php echo $settings_field; ?>
			<ul>
				<li class="form_row">
	                <label for="htmcas_title">Show Post Titles:</label>
					<select name="htmcas_title" id="htmcas_title">
						<option value="yes" <?php echo $options['htmcas_title'] == 'yes' ? 'selected="selected"' : ''; ?>>Yes</option>
						<option value="no" <?php echo $options['htmcas_title'] == 'no' ? 'selected="selected"' : ''; ?>>No</option>
					</select>
				
	            	<?php if(!empty($errors['htmcas_title'])): ?>
	           			<span class="error">
	           				<?php echo $errors['htmcas_title']; ?>
	           			</span>
	           		<?php endif; ?>
	            </li>
	            
	             <li class="form_row">
	                <label for="htmcas_link">Enable Link:</label>
					<select name="htmcas_link" id="htmcas_link">
						<option value="yes" <?php echo $options['htmcas_link'] == 'yes' ? 'selected="selected"' : ''; ?>>Yes</option>
						<option value="no" <?php echo $options['htmcas_link'] == 'no' ? 'selected="selected"' : ''; ?>>No</option>
					</select>
				
	            	<?php if(!empty($errors['htmcas_link'])): ?>
	           			<span class="error">
	           				<?php echo $errors['htmcas_link']; ?>
	           			</span>
	           		<?php endif; ?>
	            </li>
	            
	            <li class="form_row">
	                <label for="htmcas_featured">Featured Image:</label>
					<select name="htmcas_featured" id="htmcas_featured">
						<option value="none" <?php echo $options['htmcas_featured'] == 'none' ? 'selected="selected"' : ''; ?>>None</option>
						<option value="thumbnail" <?php echo $options['htmcas_featured'] == 'thumbnail' ? 'selected="selected"' : ''; ?>>Thumbnail</option>
						<option value="medium" <?php echo $options['htmcas_featured'] == 'medium' ? 'selected="selected"' : ''; ?>>Medium</option>
						<option value="large" <?php echo $options['htmcas_featured'] == 'large' ? 'selected="selected"' : ''; ?>>Large</option>
					</select>
				
	            	<?php if(!empty($errors['htmcas_featured'])): ?>
	           			<span class="error">
	           				<?php echo $errors['htmcas_featured']; ?>
	           			</span>
	           		<?php endif; ?>
	            </li>
                    
                    
                      <li class="form_row">
	                <label for="htmcas_css">Enable CSS on Front End:</label>
					<select name="htmcas_css" id="htmcas_css">
						<option value="yes" <?php echo $options['htmcas_css'] == 'yes' ? 'selected="selected"' : ''; ?>>Yes</option>
						<option value="no" <?php echo $options['htmcas_css'] == 'no' ? 'selected="selected"' : ''; ?>>No</option>
					</select>
				
                                <?php if(!empty($errors['htmcas_css'])): ?>
	           			<span class="error">
	           				<?php echo $errors['htmcas_css']; ?>
	           			</span>
	           		<?php endif; ?>
	            </li>
	            
	            <li class="form_row">
	                <label for="htmcas_post_type">Enable Custom Areas On:</label>
	                <div>
	                <?php for($i = 0; $i < count($types); $i++): ?>
	                		<input type="checkbox" name="htmcas_post_type[]" value="<?php echo $types[$i];?>" <?php echo is_array($options['htmcas_post_type']) && in_array($types[$i], $options['htmcas_post_type']) ? 'checked="checked"' : ''; ?>/><?php echo ucfirst($types[$i]); ?><br/>
	                <?php endfor; ?>
	                </div> 
	            </li>    
	            	                 
	            <li class="form_row">
	                <label for="htmcas_user_add_shortcode">Which Users Can Add Shortcodes:</label>
	                <div>
	                <?php for($i = 0; $i < count($user_types); $i++): ?>
	                		<input type="checkbox" name="htmcas_user_add_shortcode[]" value="<?php echo $user_types[$i];?>" <?php echo is_array($options['htmcas_user_add_shortcode']) && in_array($user_types[$i], $options['htmcas_user_add_shortcode']) ? 'checked="checked"' : ''; ?>/><?php echo ucfirst($user_types[$i]); ?><br/>
	                <?php endfor; ?>
	                </div> 
	            </li>    
	            
	            <li class="form_row">
	                <label for="htmcas_user_add_customarea">Which Users Can Add Custom Area Posts:</label>
	                <div>
	                <?php for($i = 0; $i < count($user_types); $i++): ?>
	                		<input type="checkbox" name="htmcas_user_add_customarea[]" value="<?php echo $user_types[$i];?>" <?php echo is_array($options['htmcas_user_add_customarea']) && in_array($user_types[$i], $options['htmcas_user_add_customarea']) ? 'checked="checked"' : ''; ?>/><?php echo ucfirst($user_types[$i]); ?><br/>
	                <?php endfor; ?>
	                </div> 
	            </li>    
	            
	                
	      
	        <!-- Saved -->
	        <input type="hidden" name="saved"/>
	        <p class="submit">
	            <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
	        </p>
	        
	        
	        <!-- Shortcode instructions-->
	    </form>
	  </div>

	 	 
	 <!-- The Instructions Tab -->
	 <div class="thetab" id="tabs-2">
	 	<h2>Instructions</h2>
	  	<p>This plugin is designed to be fairly straightforward. User groups can be assiged access to create custom area posts which consist of a title, a link, a featured image and some content. These are then output on your pages using shorcodes. This provides you with a convenient mechanism to allow users to edit sections of the site without granting them full access to all posts.</p>
	  	<p>You can drop a shortcode into a text widget to output posts in a sidebar.</p>
	 </div>
	 	 
	 <!-- The About Tab -->
	 <div class="thetab" id="tabs-3">
	  	<h2>About</h2>
	  	<p>This plugin was produced by Oliver Burton, a contract web developer based in the UK. For more information visit and support or to make a donation visit 
                    <a href="http://www.htmlstudio.co.uk/component/custom-areas-for-wordpress/">http://www.htmlstudio.co.uk/component/custom-areas-for-wordpress/</a></p>
	 </div>
	  
</div>