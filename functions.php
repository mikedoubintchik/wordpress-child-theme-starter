<?php
// Check Environment
if (WP_ENV === 'development' || WP_ENV === 'staging') {
    $GLOBALS['$asset_root'] = 'build';
    $GLOBALS['$styles'] = "style.css";
    $GLOBALS['$js'] = "main.js";
} else {
    $GLOBALS['$asset_root'] = "dist";
    $GLOBALS['$styles'] = "style.min.css";
    $GLOBALS['$js'] = "main.min.js";
}

// Enqueue Styles
add_action('wp_enqueue_scripts', 'theme_child_enqueue_styles');
function theme_child_enqueue_styles()
{

    // Parent Theme Styles
    wp_enqueue_style('parent/styles', get_template_directory_uri() . '/style.css');

    // Child Theme Styles
    wp_enqueue_style('child/styles', get_stylesheet_directory_uri() . '/' . $GLOBALS['$asset_root'] . '/css/' . $GLOBALS['$styles']);
}


// Enqueue Scripts
add_action('wp_enqueue_scripts', 'theme_child_enqueue_scripts');
function theme_child_enqueue_scripts()
{
    // Child Theme Scripts
    wp_register_script('child/scripts', get_stylesheet_directory_uri() . '/' . $GLOBALS['$asset_root'] . '/js/' . $GLOBALS['$js'], array('jquery'), '1.0', true);
    wp_enqueue_script('child/scripts');

    // jQuery
    wp_deregister_script('jquery');
    wp_register_script('jquery', ('//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'), false, null, true);
    wp_enqueue_script('jquery');
}