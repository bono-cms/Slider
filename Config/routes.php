<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

return array(
    '/%s/module/slider' => array(
        'controller' => 'Admin:Browser@indexAction'
    ),
    
    '/%s/module/slider/page/(:var)' => array(
        'controller' => 'Admin:Browser@indexAction'
    ),
    
    '/%s/module/slider/tweak' => array(
        'controller' => 'Admin:Image@tweakAction',
        'disallow' => array('guest')
    ),
    
    '/%s/module/slider/image/delete/(:var)' => array(
        'controller' => 'Admin:Image@deleteAction',
        'disallow' => array('guest')
    ),
    
    '/%s/module/slider/image/add' => array(
        'controller' => 'Admin:Image@addAction'
    ),
    
    '/%s/module/slider/image/edit/(:var)' => array(
        'controller' => 'Admin:Image@editAction'
    ),
    
    '/%s/module/slider/image/save' => array(
        'controller' => 'Admin:Image@saveAction',
        'disallow' => array('guest')
    ),

    '/%s/module/slider/category/view/(:var)' => array(
        'controller' => 'Admin:Browser@categoryAction'
    ),
    
    '/%s/module/slider/category/(:var)/page/(:var)' => array(
        'controller' => 'Admin:Browser@categoryAction'
    ),
    
    '/%s/module/slider/category/add' => array(
        'controller' => 'Admin:Category@addAction'
    ),
    
    '/%s/module/slider/category/edit/(:var)' => array(
        'controller' => 'Admin:Category@editAction'
    ),
    
    '/%s/module/slider/category/save' => array(
        'controller' => 'Admin:Category@saveAction',
        'disallow' => array('guest')
    ),
    
    '/%s/module/slider/category/delete/(:var)' => array(
        'controller' => 'Admin:Category@deleteAction',
        'disallow' => array('guest')
    )
);
