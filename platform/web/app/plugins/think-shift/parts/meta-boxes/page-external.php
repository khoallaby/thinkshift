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



/* 3 columns */

/*
piklist( 'field', array(
    'type'        => 'group',
    'label'       => __( 'Todo\'s (Group)', 'thinkshift' ),
    'description' => __( 'Saves data as individual meta keys.', 'thinkshift' ),
    'add_more'    => true,
    'fields'      => array(
        array(
            'type'     => 'file',
            'field'    => 'image',
            'label'    => __( 'Image', 'thinkshift' ),
            'columns'  => 12,
            'validate' => array(
                array(
                    'type'    => 'limit',
                    'options' => array(
                        'min' => 1,
                        'max' => 1
                    )
                )
            )
        ),
        array(
            'type'    => 'editor',
            'field'   => 'task',
            'label'   => __( 'Task', 'thinkshift' ),
            'columns' => 12,
            'options' => array(
                'drag_drop_upload' => true,
                'editor_height'    => 100,
                'media_buttons'    => false,
                'teeny'            => true,
                'quicktags'        => false,
                'tinymce'          => array(
                    'autoresize_min_height' => 100,
                    'toolbar1'              => 'bold,italic,bullist,numlist,blockquote,link,unlink,undo,redo',
                    'resize'                => false,
                    'wp_autoresize_on'      => true
                )
            )
        ),
        array(
            'type'    => 'select',
            'field'   => 'demo_add_more_todo_user_id',
            'label'   => __( 'Assigned to', 'thinkshift' ),
            'columns' => 12,
            'choices' => piklist(
                get_users(
                    array(
                        'orderby' => 'display_name',
                        'order'   => 'asc'
                    ),
                    'objects'
                ),
                array(
                    'ID',
                    'display_name'
                )
            )
        )
    )
) );
*/