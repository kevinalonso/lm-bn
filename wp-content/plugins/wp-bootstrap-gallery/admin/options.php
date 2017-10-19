<?php

/**
 * A unique identifier is defined to store the options in the database and reference them from the theme.
 * By default it uses the theme name, in lowercase and without spaces, but this can be changed if needed.
 * If the identifier changes, it'll appear as if the options have been reset.
 * 
 */
function wpb_optionsframework_option_name() {
    $wpb_optionsframework_settings = get_option('wpb_optionsframework');
    $wpb_optionsframework_settings['id'] = WPB_OPTIONS_FRAMEWORK_TAG;
    update_option('wpb_optionsframework', $wpb_optionsframework_settings);
}

/**
 * Defines an array of options that will be used to generate the settings page and be saved in the database.
 * When creating the 'id' fields, make sure to use all lowercase and no spaces.
 *  
 */
function wpb_optionsframework_options() {
    $colorboxpath = WPBGALLERY_URL . 'colorbox/';
    $options = array();

    $options[] = array('name' => __('Gallery Settings', 'wpbgallery'),
        'type' => 'heading');
    $caption = array(
                '%title%' => 'Title',
                '%alt%' => 'Alternative Text',
                '%filename%' => 'Filename',
                '%caption%' => 'Caption',
        );
    $options[] = array('name' => __('Caption Text', 'wpbgallery'),
        'desc' => __('Select the text that you want to be displayed for the as caption on frontend', 'wpbgallery'),
        'id' => 'wpbgallery_caption',
        'std' => '%title%',
        'class' => '',
        'options' => $caption,
        'type' => 'radio');

    $options[] = array('name' => __('Use TimThumb', 'wpbgallery'),
        'desc' => __('Instead of using WP\'s thumbnail generator, uses <a href="http://code.google.com/p/timthumb/">TimThumb</a>, which will do the thumbnails as they are requested, so allowing you to change the thumbnail without having to regenerate all the thumbnails.', 'wpbgallery'),
        'id' => 'wpbgallery_use_timthumb',
        'std' => '0',
        'type' => 'checkbox');

    $options[] = array('name' => __('TimThumb Width', 'wpbgallery'),
        'desc' => __('Thumbnail width. This only applies if using TimThumb.', 'wpbgallery'),
        'id' => 'wpbgallery_thumb_width',
        'std' => '100',
        'class' => 'mini',
        'type' => 'text');

    $options[] = array('name' => __('TimThumb Height', 'wpbgallery'),
        'desc' => __('Thumbnail height. This only applies if using TimThumb.', 'wpbgallery'),
        'id' => 'wpbgallery_thumb_height',
        'std' => '100',
        'class' => 'mini',
        'type' => 'text');
    
        $options[] = array('name' => __('Borderless', 'wpbgallery'),
        'desc' => __('If you want the gallery popup to be borderless then just tick this option. Not sure what this means? Then click on <a href="http://blueimp.github.io/Bootstrap-Image-Gallery/" target="_blank" >this link</a> to view demo.', 'wpbgallery'),
        'id' => 'borderless',
        'std' => '0',
        'type' => 'checkbox');
    
        $options[] = array('name' => __('Fullscreen', 'wpbgallery'),
        'desc' => __('Enable full screen mode for gallery images.', 'wpbgallery'),
        'id' => 'fullscreen',
        'std' => '0',
        'type' => 'checkbox');
        
        $options[] = array('name' => __('Automatically Add Bootstrap Scripts', 'wpbgallery'),
        'desc' => __('If your theme already uses bootstrap js/css files then uncheck this box', 'wpbgallery'),
        'id' => 'bootstrap_script',
        'std' => '1',
        'type' => 'checkbox');

    $options[] = array('name' => __('Please Donate', 'wpbgallery'),
        'desc' => __('I have utilized lots of time and effort in order to make this plugin, all donations are hugely appreciated.', 'wpbgallery'),
        'url' => 'https://www.paypal.com/cgi-bin/webscr?cmd=_xclick&business=sagar.seth9@gmail.com&item_name=WP%20Bootstrap%20Gallery&return=http://magnigenie.com',
        'type' => 'donate');

    return $options;
}