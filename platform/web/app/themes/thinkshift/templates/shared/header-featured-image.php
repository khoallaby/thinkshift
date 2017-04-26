<div class="featured-image">
    <?php
    $size = 'full';
    the_post_thumbnail( $size, ['itemprop' => 'image','class' => 'img-responsive'] );

    if( $featuredImageText =  get_post_meta( get_the_ID(), 'featured_image_text', true ) )
        echo sprintf( '<h2>%s</h2>', $featuredImageText );
    ?>
</div>



