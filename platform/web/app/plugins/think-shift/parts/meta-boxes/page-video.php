<?php
/*
Title: Video Options
Post Type: page
Order: 1
Collapse: false
Flow: Video
Template: template-video.php
*/


use ThinkShift\Plugin\Tags;



piklist( 'field', array(
    'type'        => 'group',
    'field'    => 'videos',
    'label'       => __( 'Videos', 'thinkshift' ),
    'description' => __( 'Add videos', 'thinkshift' ),
    'add_more'    => true,
    'fields'      => array(
        array(
            'type'    => 'text',
            'field'   => 'video_url',
            'label'   => __( 'URL', 'thinkshift,' ),
            'columns' => 12
        ),
        array(
            'type'    => 'select',
            'field'   => 'video_source',
            #'scope'   => 'taxonomy',
            'label'   => __( 'Video Source', 'thinkshift,' ),
            'columns' => 12,
            'choices' => [
                '' => '',
                'vimeo' => 'Vimeo',
                'youtube' => 'YouTube'
            ]
        ),
        array(
            'type'    => 'select',
            'field'   => 'video_tag',
            #'scope'   => 'taxonomy',
            'label'   => __( 'Strengths', 'thinkshift,' ),
            'columns' => 12,
            'choices' => piklist(
                Tags::getTagsFromCategory( Tags::$strengthMetaKey ),
                array(
                    'term_id',
                    'name'
                )
            ),
            'attributes' => array(
                'multiple' => 'multiple' // This changes a select field into a multi-select field
            )
        )
    )
) );
