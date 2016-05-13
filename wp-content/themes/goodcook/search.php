<?php get_header();?>
    <!--==============================content================================-->
    <div class="inner">
        <div class="main">
            <section id="content">
                <div class="indent">
                    <div class="wrapper">
                        <article class="col-1">
                            <div class="post_path"><?php if (function_exists('dimox_breadcrumbs')) dimox_breadcrumbs(); ?></div>
                            <div class="bg">


                            <?php if ( have_posts() ) :?>
                                <?php while ( have_posts() ) : the_post(); ?>
                                    <div class="indent-left">


                                        <h3><?php the_title();?></h3>

                                        <div class="wrapper p3">
                                            <div class="extra-wrap">
                                                <p class="p1"><?php the_content('');?></p>
                                                <div class="relative"><a class="read-more" href="<?php the_permalink();?>">Далее</a> </div>
                                            </div>

                                        </div>
                                    </div>

                                <?php endwhile; endif;?>



                        </div>
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