<?php
/*
Title: Video Options
Post Type: video
Order: 1
Collapse: false
Flow: Video
*/



piklist( 'field', array(
    'type'       => 'text',
    'field'      => 'video_url',
    'label'      => __( 'URL ID', 'thinkshift' ),
    'help'       => __( 'Use the unique ID found at the end of the URL. Example: "dQw4w9WgXcQ" from -- www.youtube.com/watch?v=dQw4w9WgXcQ', 'thinkshift' ),
    'attributes' => array(
        'class' => 'regular-text'
    )
) );

piklist( 'field', array(
    'type'       => 'select',
    'field'   => 'video_source',
    'label'   => __( 'Video Source', 'thinkshift,' ),
    'choices' => [
        '' => '',
        'vimeo' => 'Vimeo',
        'youtube' => 'YouTube'
    ],
    'attributes' => array(
        'class' => 'regular-text'
    )
) );
