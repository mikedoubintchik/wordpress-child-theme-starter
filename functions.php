<?php

$WP_ENV = 'development';

// Check Environment
if ($WP_ENV === 'development' || $WP_ENV === 'staging') {
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
    wp_enqueue_style('parent/styles', get_template_directory_uri() . '/library/css/style.css');

    // Child Theme Styles
    wp_enqueue_style('child/styles', get_stylesheet_directory_uri() . '/' . $GLOBALS['$asset_root'] . '/css/' . $GLOBALS['$styles']);

    // Font Awesome
    wp_enqueue_style('vendor/font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');

}


// Enqueue Scripts
add_action('wp_enqueue_scripts', 'theme_child_enqueue_scripts');
function theme_child_enqueue_scripts()
{

    // Parent Theme Scripts
    wp_register_script('parent/scripts', get_template_directory_uri() . '/library/js/scripts.js', array('jquery'), '1.0', true);
    wp_enqueue_script('parent/scripts');


    // Child Theme Scripts
    wp_register_script('child/scripts', get_stylesheet_directory_uri() . '/' . $GLOBALS['$asset_root'] . '/js/' . $GLOBALS['$js'], array('jquery'), '1.0', true);
    wp_enqueue_script('child/scripts');

    // jQuery
    wp_deregister_script('jquery');
    wp_register_script('jquery', ('//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'), false, null, true);
    wp_enqueue_script('jquery');
}

/**
 * Secondary navigation
 */
function subnav()
{

    function wpse13669_show_all_children($post_id, $current_level)
    {
        $children = get_posts(array(
            'post_type' => 'page',
            'posts_per_page' => -1,
            'post_parent' => $post_id,
            'order_by' => 'title',
            'order' => 'ASC'));
        if (empty($children)) return;

        echo '<ul class="child-pages level-' . $current_level . '-children has-children">';

        foreach ($children as $child) {

            $current_page = (get_the_ID() === $child->ID ? 'current_page_sidemenu_item' : '');

            echo '<li class="' . $current_page . ' level-' . $current_level . '">';
            echo '<a href="' . get_permalink($child->ID) . '">';
            echo apply_filters('the_title', $child->post_title);
            echo '</a>';

            // now call the same function for child of this child
            wpse13669_show_all_children($child->ID, $current_level + 1);

            echo '</li>';

        }

        echo '</ul>';

    }

    echo '<div class="subnav">';
    wpse13669_show_all_children($post->ID, 1);
    echo '</div>';
}
