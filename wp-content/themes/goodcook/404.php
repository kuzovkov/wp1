<?php get_header();?>
    <!--==============================content================================-->
    <div class="inner">
        <div class="main">
            <section id="content">
                <div class="indent">
                    <div class="wrapper">
                        <article class="col-1">
                            <div class="post_path"><?php if (function_exists('dimox_breadcrumbs')) dimox_breadcrumbs(); ?></div>


                                        <h3>Ничего не найдено</h3>


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