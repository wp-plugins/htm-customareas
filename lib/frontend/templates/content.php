<?php
/*
 * Display the result of the embedded posts
 */
?>
<?php for ($i = 0; $i < count($posts); $i++): ?>
    <div class="htmcaspost">

        <?php if ($image != 'none'): ?>
            <div class="htmcaspost_<?php echo $image; ?>">
                <?php echo get_the_post_thumbnail($posts[$i]->ID, $image); ?> 
            </div>
        <?php endif; ?>

        <div class="htmcaspost_container">
            <?php if ($title == 'yes'): ?>
                <div class="htmcaspost_title">
                    <?php if ($link == 'yes'): 
                        $link = $posts[$i]->link != '' ? $posts[$i]->link : $posts[$i]->guid;?>
                        <a href="<?php echo $link; ?>"><?php echo $posts[$i]->post_title; ?></a>
                    <?php
                    else:
                        echo $title;
                    endif;
                    ?>

                </div>		
                <?php endif; ?>

            <div class="htmcaspost_content">
                <?php echo $posts[$i]->post_content; ?> 
            </div>
        </div>


    </div>


<?php endfor; ?> 