<div class="htmlcaslink_urlcontainer">
    <label for="link_url" class="screen-reader-text">Link Url</label>
    <input type="text" id="htmlcaslink_url" name="htmlcaslink_url" placeholder="Enter a Link" value="<?php echo $link_url;?>" />
    <?php if(!empty($errors['htmlcaslink_url'])): ?>
            <span class="error">
                    <?php echo $errors['htmlcaslink_url']; ?>
            </span>
    <?php endif; ?>

    <?php echo $nonce_field; ?>
</div>
