<?php

return array(
    'name'  => 'Slider',
    'caption'  => 'Slider',
    'route' => 'Slider:Admin:Browser@indexAction',
    'order' => 1,
    'description' => 'Slider module allows you to manage sliders with random dimensions',
    // Bookmarks of this module
    'bookmarks' => array(
        array(
            'name' => 'Add a slider',
            'controller' => 'Slider:Admin:Image@addAction',
            'icon' => 'glyphicon glyphicon-picture'
        )
    )
);