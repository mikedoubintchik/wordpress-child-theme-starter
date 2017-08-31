<?php get_header(); ?>

<?php
$options = get_option('som_theme_options');

if (is_front_page()) {
    if (get_theme_mod('som_banner_layout') == 'full-width') {
        ?>
        <div class="banner full-width">
            <div class="wrap">
                <h1><?php echo get_theme_mod('som_banner_headline'); ?></h1>
                <a href="<?php echo get_theme_mod('som_banner_buttonlink'); ?>"
                   class="<?php echo get_theme_mod('som_primary_color'); ?>-btn btn"><?php echo get_theme_mod('som_banner_buttontext'); ?></a>
            </div>
        </div> <?php
    } else {
        $banner_image_ID = get_attachment_id_from_url(get_theme_mod('som_banner_image'));
        ?>
        <div class="banner two-column <?php echo get_theme_mod('som_secondary_color'); ?>">
            <div class="wrap">
                <?php echo wp_get_attachment_image($banner_image_ID, 'large', 0, array('class' => "banner-image", 'alt' => "School of Medicine",)); ?>
                <div class="banner-text">
                    <h1><?php echo get_theme_mod('som_banner_headline'); ?></h1>
                    <p><?php echo get_theme_mod('som_banner_bodytext'); ?></p>
                    <a href="<?php echo get_theme_mod('som_banner_buttonlink'); ?>"
                       class="<?php echo get_theme_mod('som_primary_color'); ?>-btn btn"><?php echo get_theme_mod('som_banner_buttontext'); ?></a>
                </div>
            </div>
        </div> <?php
    }
    ?>

    <div class="content">

        <?php if (get_theme_mod('som_homepage_layout') == 'content-option-2') { ?>
            <div class="extra-content">
                <div class="wrap">
                    <h2><?php echo get_theme_mod('som_homepage_headline'); ?></h2>
                    <p><?php echo get_theme_mod('som_homepage_bodytext'); ?></p>
                </div>
            </div>
        <?php } ?>
        <div class="main-content <?php echo get_theme_mod('som_homepage_layout'); ?>">
            <div class="wrap">
                <!-- Home Page Left Column -->
                <div class="home-left-column">
                    <?php if (is_active_sidebar('homeleft')) : dynamic_sidebar('homeleft');
                    else : ?>
                        <div class="recent-news">
                            <h2><?php echo get_theme_mod('som_left_column_heading'); ?></h2>
                            <ul class="recent-posts">
                                <?php
                                $args = array('posts_per_page' => 3);
                                $postslist = get_posts($args);
                                foreach ($postslist as $post) :
                                    setup_postdata($post); ?>
                                    <li class="post clearfix">
                                        <?php if (get_theme_mod('som_homepage_layout') == 'content-option-2') {
                                            the_post_thumbnail();
                                        } ?>
                                        <a href="<?php the_permalink(); ?>" class="post-title"><?php the_title(); ?></a>
                                        <div class="date"><?php the_time(get_option('date_format')); ?></div>
                                        <?php the_excerpt(); ?>
                                    </li>
                                    <?php
                                endforeach;
                                wp_reset_postdata();
                                ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Home Page Right Column -->
                <div class="homepage-content">
                    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>

                        <h2><?php the_title(); ?></h2>
                        <?php the_content(); ?>

                    <?php endwhile;
                    else : ?>
                        <p><?php echo "Enter content on the page titled'" . the_title() . "' and it will appear here." ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

<?php } else { ?>

    <div class="content inner">
    <div class="wrap cf">

        <?php $children = wp_list_pages(array('echo' => 0, 'title_li' => '', 'depth' => 1, 'child_of' => get_post_top_ancestor_id())); ?>
        <?php if ($children) { ?>
        <div class="main-content with-subnav">
            <?php } else { ?>
            <div class="main-content">
                <?php } ?>
                <?php if (have_posts()) : while (have_posts()) : the_post(); ?>

                    <h1><?php the_title(); ?></h1>
                    <?php the_post_thumbnail('large'); ?>
                    <?php the_content(); ?>

                <?php endwhile;
                else : ?>
                    <p><?php echo "No posts found." ?></p>
                <?php endif; ?>
            </div>

            <?php subnav(); ?>

        </div>
    </div>

<?php } ?>

<?php get_footer(); ?>