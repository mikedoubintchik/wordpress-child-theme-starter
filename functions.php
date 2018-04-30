<?php
// Define Environment
define( 'WP_ENV', getenv( 'WP_ENV' ) ? getenv( 'WP_ENV' ) : 'development' );

// Check Environment
if ( WP_ENV === 'development' || WP_ENV === 'staging' ) {
    $GLOBALS['$asset_root'] = 'build';
    $GLOBALS['$styles']     = "style.css";
    $GLOBALS['$js']         = "main.js";
} else {
    $GLOBALS['$asset_root'] = "dist";
    $GLOBALS['$styles']     = "style.min.css";
    $GLOBALS['$js']         = "main.min.js";
}

// Enqueue Styles
add_action( 'wp_enqueue_scripts', 'theme_child_enqueue_styles' );
function theme_child_enqueue_styles() {

    // Parent Theme Styles
    wp_enqueue_style( 'parent/styles', get_template_directory_uri() . '/style.css' );

    // Child Theme Styles
    wp_enqueue_style( 'child/styles', get_stylesheet_directory_uri() . '/' . $GLOBALS['$asset_root'] . '/css/' . $GLOBALS['$styles'] );
}


// Enqueue Scripts
add_action( 'wp_enqueue_scripts', 'theme_child_enqueue_scripts' );
function theme_child_enqueue_scripts() {
    // Child Theme Scripts
    wp_register_script( 'child/scripts', get_stylesheet_directory_uri() . '/' . $GLOBALS['$asset_root'] . '/js/' . $GLOBALS['$js'], array( 'jquery' ), '1.0', true );
    wp_enqueue_script( 'child/scripts' );

    // jQuery
    wp_deregister_script( 'jquery' );
    wp_register_script( 'jquery', ( '//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js' ), false, null, true );
    wp_enqueue_script( 'jquery' );
}

// If using Docker Starter, uncomment this in your development environment
add_action( 'wp_footer', function () { ?>
    <script id="__bs_script__">//<![CDATA[
        document.write("<script async src='http://HOST:3000/browser-sync/browser-sync-client.js?v=2.18.11'><\/script>".replace("HOST", location.hostname));
        //]]>
    </script>
<?php } );