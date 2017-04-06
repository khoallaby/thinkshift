<?php get_template_part('templates/page', 'header'); ?>


    <form id="post-filter">
        <div class="row">
            <div class="col-12 text-xs-center" data-toggle="buttons">
                <?php
                $strengths = ThinkShift\Plugin\Tags::getAllStrengths();

                foreach( $strengths as $strength ) {
                    if( isset($_GET['strengths']) )
                        $strengthArray = $_GET['strengths'];
                    else
                        $strengthArray = ThinkShift\Plugin\Users::getUserStrengths();

                    $checked = in_array( $strength, $strengthArray );
                    ?>
                    <label class="btn mr-4 mb-4 btn-lg btn-pill btn-primary <?php echo $checked ? 'active' : ''; ?>">
                        <?php
                        echo sprintf('<input type="checkbox" %s class="" autocomplete="off" name="strengths[]" value="%s"/> %s',
                            $checked ? 'checked' : '',
                            $strength,
                            $strength
                        );
                        ?>
                    </label>
                <?php } ?>
            </div>
        </div>
        <div class="row">
            <div class="col-12 text-xs-right">
                <input type="submit" class="btn btn-primary" value="Submit" />
            </div>
        </div>
    </form>

<?php if (!have_posts()) : ?>
    <div class="alert alert-warning">
        <?php _e('Sorry, no results were found.', 'sage'); ?>
    </div>
    <?php get_search_form(); ?>
<?php else : ?>
    <?php while (have_posts()) : the_post(); ?>
        <article <?php post_class(); ?>>
            <header>
                <h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
            </header>
            <div class="row">
              <div class="col-lg-12">
                <?php
                    get_template_part('templates/' . get_post_type() . '/index', 'occupation' );
                ?>
              </div>
            </div>
        </article>
    <?php

    endwhile;

    the_posts_navigation();

endif;