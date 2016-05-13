<?php get_header();?>
    <!--==============================content================================-->
    <div class="inner">
        <div class="main">
            <section id="content">
                <div class="indent">
                    <div class="wrapper">
                        <article class="col-1">
                            <?php if ( have_posts() ) :?>
                                <?php while ( have_posts() ) : the_post(); ?>
                                    <div class="indent-left">
                                        
                                        <div class="post_path"><?php if (function_exists('dimox_breadcrumbs')) dimox_breadcrumbs(); ?></div>
                                        <h3><?php the_title();?></h3>

                                        <div class="wrapper p3">
                                            <div class="extra-wrap">
                                                <p class="p1"><?php the_content();?></p>
                                            </div>
                                            <?php wp_link_pages(array('before' => '<p><strong>Страницы:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
                                            <?php the_tags('<p class="tags">Метки: ', ', ', '</p>'); ?>
                                            <?php if ( $user_ID ) : ?>
                                                <div class="edit_post"><?php edit_post_link(__('[Правка]')); ?> (Вы вошли как <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>)</div>
                                            <?php endif; ?>

                                            <?php
                                            if (function_exists('wp_list_comments')) {
                                                comments_template('/comments.php', true);
                                            }
                                            else {
                                                comments_template('/comments-old.php');
                                            }
                                            ?>
                                        </div>
                                    </div>

                                <?php endwhile; endif;?>



                        </article>

                        <article class="col-2">


                            <?php if(!function_exists('dynamic_sidebar') || !dynamic_sidebar('right_sidebar')): endif;?>

                        </article>

                    </div>
                </div>
            </section>
            <div class="block"></div>
        </div>
    </div>
    </div>
<?php get_footer();?>