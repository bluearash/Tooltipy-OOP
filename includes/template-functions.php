<?php

/**
 * Works like the theme Wordpress get_template_part()
 */
function tooltipy_template_part( $file_prefix, $file_suffix = '' ){

    $template_file = TOOLTIPY_PLUGIN_DIR . 'public/partials/' . $file_prefix;
    if( !empty( $file_suffix ) ){
        $template_file .= '-' . $file_suffix;
    }
    $template_file .= '.php';

    include $template_file;
}

/**
 * Main popup content
 */
function tltpy_popup_add_main_section(){
    tooltipy_template_part( 'tooltip', 'pop' );
}

/**
 * Adds the synonym section to the popup content
 */
function tltpy_popup_add_synonyms_section(){

    tooltipy_template_part( 'tooltip', 'synonyms' );
}

/**
 * Adds video section to the popup content
 */
function tltpy_popup_add_video_section(){
    $video_id = get_post_meta( get_the_ID(), 'tltpy_youtube_id', true );

    if( $video_id ):
        ?>
            <div class="tltpy_video">
                <iframe width="560" height="315" src="https://www.youtube.com/embed/<?php echo($video_id); ?>" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
            </div>
        <?php
    endif;
}