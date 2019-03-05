<?php

/**
 * Module configuration container
 */

return array(
    'name'  => 'Slider',
    'description' => 'Slider module allows you to manage sliders with random dimensions',
    // Bookmarks of this module
    'bookmarks' => array(
        array(
            'name' => 'Add a slider',
            'controller' => 'Slider:Admin:Image@addAction',
            'icon' => 'glyphicon glyphicon-picture'
        )
    ),
    'menu' => array(
        'name' => 'Slider',
        'icon' => 'fas fa-poll',
        'items' => array(
            array(
                'route' => 'Slider:Admin:Browser@indexAction',
                'name' => 'View all slides'
            ),
            array(
                'route' => 'Slider:Admin:Category@addAction',
                'name' => 'Add a slider'
            )
        )
    )
);