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
            'icon' => 'fas fa-images'
        )
    ),
    'menu' => array(
        'name' => 'Slider',
        'icon' => 'fas fa-images',
        'items' => array(
            array(
                'route' => 'Slider:Admin:Browser@indexAction',
                'name' => 'View all slides'
            ),
            array(
                'route' => 'Slider:Admin:Image@addAction',
                'name' => 'Add a slider'
            )
        )
    )
);