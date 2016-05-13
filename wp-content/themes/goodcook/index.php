<?php get_header();?>
    <!--==============================content================================-->
    <div class="inner">
        <div class="main">
            <section id="content">
                <div class="slider">
                    <ul class="items">
                        
                        <?php if ( have_posts() ) :?>
                        <?php while ( have_posts() ) : the_post(); ?>
                        <li>
                            <?php if(function_exists('add_theme_support')) if ( has_post_thumbnail() ) { the_post_thumbnail(array(613,324));} ?>
                            <div class="banner"> <strong class="title"> <strong>Hot</strong><em>Recipe</em> </strong>
                                <p><?php the_excerpt(); ?></p>
                                <a class="button-1" href="<?php the_permalink();?>">Read More</a>
                            </div>
                        </li>
                        <?php endwhile; endif; ?>
                    </ul>
                    <a class="banner-2" href="#"></a>
                </div>

                <ul class="pags">
                    <?php if (have_posts()):?>
                    <?php while ( have_posts() ) : the_post(); $count = 1;?>
                    <li><a href="#"><?php echo $count; $count++;?></a></li>

                    <?php endwhile; endif; ?>
                </ul>

                <div class="bg">
                    <div class="padding">
                        <div class="wrapper">
                            <div class="post_path"><?php if (function_exists('dimox_breadcrumbs')) dimox_breadcrumbs(); ?></div>
                            <?php if(have_posts()) : $count = 0;?>
                            <?php while(have_posts()): the_post(); ?>
                            <article class="col-<?php $count++; if ($count < 3){$col = 1;}else{$col = 2; $count = 0;} echo $col;?>">
                                <h3><a href="<?php the_permalink();?>"><?php the_title(); ?></a></h3>
                                <p><?php the_excerpt();?></p>
                                <div class="relative"><a class="button-2" href="<?php the_permalink();?>">Read More</a> </div>
                            </article>
                            <?php endwhile;?>
                            <?php else:?>
                            <p>Ничего не найдено</p>
                            <?php endif;?>
                        </div>
                    </div>
                </div>
                <?php if(function_exists('wp_pagenavi')) { wp_pagenavi(); } else { ?>
                    <div class="wp-pagenavi">
                        <div class="alignleft"><?php next_posts_link('&nbsp;&nbsp;&laquo; Предыдущие') ?></div>
                        <div class="alignright"><?php previous_posts_link('Следующие &raquo;&nbsp;&nbsp;') ?></div>
                    </div>
                <?php } ?>



                <?php get_sidebar();?>
            </section>
            <div class="block"></div>
        </div>
    </div>
</div>
<?php get_footer();?>

