<?php
/* Ajax Lookup for the Posts */
?>

<div id="htmcas_ajaxsearchouter" style="display:none;">
    <div id="htmcas_ajaxsearch">
        <ul>
            <li>
                <label for="htmcas_title">Show Post Titles:</label>
                <select name="htmcas_title" id="htmcas_title">
                    <option value="yes" <?php echo $defaults['htmcas_title'] == "yes" ? 'selected="selected"' : ''; ?>>Yes</option>
                    <option value="no" <?php echo $defaults['htmcas_title'] == "no" ? 'selected="selected"' : ''; ?>>No</option>
                </select>
            </li>
            <li>
                <label for="htmcas_link">Enable Link:</label>
                <select name="htmcas_link" id="htmcas_link">
                    <option value="yes" <?php echo $defaults['htmcas_link'] == "yes" ? 'selected="selected"' : ''; ?>>Yes</option>
                    <option value="no" <?php echo $defaults['htmcas_link'] == "no" ? 'selected="selected"' : ''; ?>>No</option>
                </select>
            </li>
            </li>
            <li>
                <label for="htmcas_featured">Featured Image:</label>
                <select name="htmcas_featured" id="htmcas_featured">
                    <option value="none" <?php echo $defaults['htmcas_featured'] == "none" ? 'selected="selected"' : ''; ?>>No Image</option>
                    <option value="thumbnail" <?php echo $defaults['htmcas_featured'] == "thumbnail" ? 'selected="selected"' : ''; ?>>Thumbnail</option>
                    <option value="medium" <?php echo $defaults['htmcas_featured'] == "medium" ? 'selected="selected"' : ''; ?>>Medium</option>
                    <option value="large" <?php echo $defaults['htmcas_featured'] == "large" ? 'selected="selected"' : ''; ?>>Large Image</option>
                </select>
            </li>

        </ul>
        <h3>Filter Posts</h3>
        <div class="htmcas_searchdiv">
            <label for="htmcas_search">Search Post Title:</label>
            <input type="text" name="htmcas_search" id="htmcas_search" value="" placeholder="Search for a post..."/>
        </div>

        <div class="htmcas_filters">
            <div class="htmcas_filter">
                <!-- Show results here -->
                <label for="htmcas_select">Available Posts:</label>
                <div id="htmcas_select" name="htmcas_select">
                    <?php for ($i = 0; $i < count($posts); $i++): ?>
                        <div>
                            <span><?php echo $posts[$i]->post_title; ?></span>
                            <input type="button" class="htmcas_post" name="htmcas_post" id="htmcas_post[]" value="+" data-value="<?php echo $posts[$i]->ID; ?>"/>
                        </div>
                    <?php endfor; ?>
                </div>
            </div>

            <div class="htmcas_filter">
                <label for="htmcas_selected">Selected Posts:</label>
                <div id="htmcas_selected">

                </div>
            </div>
        </div>

        <input type="hidden" value="" name="" id="htmcas_opts"/>

        <div class="mce-footer">
            <button role="presentation" type="button" class="button button-primary button-large media-button-insert" id="htmcas_insert">Insert</button>
        </div>

        <input type="hidden" id="<?php echo $nonce['name']; ?>" name="<?php echo $nonce['name']; ?>" value="<?php echo $nonce['value']; ?>"/>
    </div>
</div>
