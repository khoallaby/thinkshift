<?php get_template_part('templates/page', 'header'); ?>


<!--FILTER FOR POST LOOP-->
<div id="post-filter">
  <div class="row">
    <div class="col-lg-12 text-xs-center">
      <button type="button" class="btn mr-4 mb-4 btn-lg btn-pill btn-success">Assess</button>
      <button type="button" class="btn mr-4 mb-4 btn-lg btn-pill btn-success">Collaborate</button>
      <button type="button" class="btn mr-4 mb-4 btn-lg btn-pill btn-success">Cultivate Talent</button>
      <button type="button" class="btn mr-4 mb-4 btn-lg btn-pill btn-secondary">Generate Insight</button>
      <button type="button" class="btn mr-4 mb-4 btn-lg btn-pill btn-secondary">Innovate</button>
      <button type="button" class="btn mr-4 mb-4 btn-lg btn-pill btn-secondary">Lead</button>
      <button type="button" class="btn mr-4 mb-4 btn-lg btn-pill btn-secondary">Organize</button>
      <button type="button" class="btn mr-4 mb-4 btn-lg btn-pill btn-secondary">Persuade</button>
      <button type="button" class="btn mr-4 mb-4 btn-lg btn-pill btn-secondary">Serve</button>
      <button type="button" class="btn mr-4 mb-4 btn-lg btn-pill btn-secondary">Work Physically</button>
    </div>
  </div>
</div>


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