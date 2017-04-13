<?php
/*
Title: External Page Options
Post Type: page
Order: 1
Priority: high
Collapse: false
Flow: flow
Template: template-external.php
*/



piklist( 'field', array(
    'type'       => 'text',
    'field'      => 'featured_image_text',
    'label'      => __( 'Featured Image Text', 'thinkshift' ),
    'help'       => __( 'Text shown inside the featured image', 'thinkshift' ),
    'attributes' => array(
        'class' => 'regular-text'
    )
) );


piklist( 'field', array(
    'type'           => 'editor',
    'field'          => 'header_content',
    #'scope'          => 'post',
    'label'          => __( 'Header Content', 'thinkshift' ),
    #'template'       => 'field',
    'description'    => __( 'This is the content area below the featured image', 'thinkshift' ),
    #'value'          => '',
    'options'        => array(
        'wpautop'           => true,
        'media_buttons'     => true,
        'shortcode_buttons' => true,
        'teeny'             => false,
        'dfw'               => false,
        'tinymce'           => array(
            'resize'           => false,
            'wp_autoresize_on' => true
        ),
        'quicktags'         => true,
        'drag_drop_upload'  => true
    ),
    'on_post_status' => array(
        'value' => 'lock'
    )
) );

