<!doctype html>

<!--[if lt IE 7]>
<html <?php language_attributes(); ?> class="no-js lt-ie9 lt-ie8 lt-ie7"><![endif]-->
<!--[if (IE 7)&!(IEMobile)]>
<html <?php language_attributes(); ?> class="no-js lt-ie9 lt-ie8"><![endif]-->
<!--[if (IE 8)&!(IEMobile)]>
<html <?php language_attributes(); ?> class="no-js lt-ie9"><![endif]-->
<!--[if gt IE 8]><!-->
<html <?php language_attributes(); ?> class="no-js"><!--<![endif]-->

<head>
    <meta charset="utf-8">

    <?php // force Internet Explorer to use the latest rendering engine available ?>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title><?php wp_title(''); ?></title>

    <?php // mobile meta (hooray!) ?>
    <meta name="HandheldFriendly" content="True">
    <meta name="MobileOptimized" content="320">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

    <?php // icons & favicons (for more: http://www.jonathantneal.com/blog/understand-the-favicon/) ?>
    <link rel="apple-touch-icon" href="<?php echo get_template_directory_uri(); ?>/library/images/apple-icon-touch.png">
    <link rel="icon" href="<?php echo get_template_directory_uri(); ?>/favicon.ico">
    <!--[if IE]>
    <link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/favicon.ico">
    <![endif]-->
    <?php // or, set /favicon.ico for IE10 win ?>
    <meta name="msapplication-TileColor" content="#f01d4f">
    <meta name="msapplication-TileImage"
          content="<?php echo get_template_directory_uri(); ?>/library/images/win8-tile-icon.png">

    <!-- ALERT BAR CSS -->
    <link rel="stylesheet" href="https://alertbar.oit.duke.edu/sites/all/themes/blackwell/css/alert.css" type="text/css"
          media="Screen"/>

    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">

    <?php // wordpress head functions ?>
    <?php wp_head(); ?>
    <?php // end of wordpress head ?>

    <?php if (is_front_page()) {
        if (get_theme_mod('som_banner_layout') == 'full-width') { ?>
            <style>
                .banner.full-width {
                    background-image: url('<?php echo esc_url( get_theme_mod( 'som_banner_image' ) ); ?>');
                }
            </style>
        <?php }
    } ?>

    <?php // drop Google Analytics Here ?>
    <?php // end analytics ?>

</head>

<body <?php body_class(); ?>>

<div id="container">

    <header class="header" role="banner">

        <div id="inner-header" class="wrap cf">

            <div class='site-logo'>
                <?php if (get_theme_mod('som_logo')) : ?>
                    <a href='<?php echo esc_url(home_url('/')); ?>'
                       title='<?php echo esc_attr(get_bloginfo('name', 'display')); ?>' rel='home'><img
                                src='<?php echo esc_url(get_theme_mod('som_logo')); ?>'
                                alt='<?php echo esc_attr(get_bloginfo('name', 'display')); ?>'></a>
                <?php else : ?>
                    <a href='<?php echo esc_url(home_url('/')); ?>'
                       title='<?php echo esc_attr(get_bloginfo('name', 'display')); ?>' rel='home'><img
                                src='/wp-content/themes/som-theme/library/images/logo.png'
                                alt='<?php echo esc_attr(get_bloginfo('name', 'display')); ?>'></a>
                <?php endif; ?>
            </div>

            <div class="search">
                <?php the_widget('WP_Widget_Search'); ?>
            </div>

        </div>

        <div class="mobile-toggle">Menu</div>

        <nav role="navigation" class="<?php echo get_theme_mod('som_primary_color'); ?>">
            <?php wp_nav_menu(array(
                'container' => false,                           // remove nav container
                'container_class' => 'menu cf',                 // class of container (should you choose to use it)
                'menu' => __('The Main Menu', 'bonestheme'),  // nav name
                'menu_class' => 'nav top-nav cf',               // adding custom nav class
                'theme_location' => 'main-nav',                 // where it's located in the theme
                'before' => '',                                 // before the menu
                'after' => '',                                  // after the menu
                'link_before' => '',                            // before each link
                'link_after' => '',                             // after each link
                'depth' => 0,                                   // limit the depth of the nav
                'fallback_cb' => ''                             // fallback function (if there is one)
            )); ?>

        </nav>

        <!-- ALERT BAR JS -->
        <script src="https://alertbar.oit.duke.edu/alert.html" type="text/javascript"></script>

    </header>

    <div class="breadcrumb-wrap">
        <h2 class="element-invisible">You are here</h2>
        <ul class="breadcrumb">
            <?php bcn_display(); ?>
        </ul>
    </div>