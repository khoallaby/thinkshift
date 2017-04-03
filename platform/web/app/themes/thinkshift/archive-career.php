<?php get_template_part('templates/page', 'header'); ?>

<?php if (!have_posts()) : ?>
    <div class="alert alert-warning">
        <?php _e('Sorry, no results were found.', 'sage'); ?>
    </div>
    <?php get_search_form(); ?>
<?php endif; ?>

<?php while (have_posts()) : the_post(); ?>
    <article <?php post_class(); ?>>
        <header>
            <h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
        </header>
        <div class="row">
            <?php
                #get_template_part('templates/content', get_post_type() != 'post' ? get_post_type() : get_post_format());

                $keys = ['occcode', 'occup', 'occdesc', 'alttitle1', 'alttitle2', 'alttitle3',
                'alttitle4', 'WorkActivity1', 'WorkActivity2','WorkActivity3', 'WorkActivity4',
                'WorkActivity5', 'WorkActivity6','WorkActivity7', 'ValueType1', 'ValueType2',
                'ValueType3','EducationNorm', 'EducationMin', 'techskillkn1', 'techskillkn2',
                'techskillkn3', 'techskillkn4', 'MedWage', 'PrEarnGrowth','PrLongUnemp',
                'openingscount', 'openingsrate', 'openingsratecat', 'pctselfemp',
                'pctselfempcat', 'highoppjobfamily', 'jobzone'];


                foreach( $keys as $key )
                    echo sprintf( '<div class="col-lg-12"><strong>%s:</strong> %s</div>', $key, get_post_meta( get_the_ID(), $key, true ) );
            ?>
        </div>
    </article>
<?php

endwhile;
the_posts_navigation();
