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
    '/admin/module/slider' => array(
        'controller' => 'Admin:Browser@indexAction'
    ),
    
    '/admin/module/slider/page/(:var)' => array(
        'controller' => 'Admin:Browser@indexAction'
    ),
    
    '/admin/module/slider/tweak' => array(
        'controller' => 'Admin:Image@tweakAction',
        'disallow' => array('guest')
    ),
    
    '/admin/module/slider/image/delete/(:var)' => array(
        'controller' => 'Admin:Image@deleteAction',
        'disallow' => array('guest')
    ),
    
    '/admin/module/slider/image/add' => array(
        'controller' => 'Admin:Image@addAction'
    ),
    
    '/admin/module/slider/image/edit/(:var)' => array(
        'controller' => 'Admin:Image@editAction'
    ),
    
    '/admin/module/slider/image/save' => array(
        'controller' => 'Admin:Image@saveAction',
        'disallow' => array('guest')
    ),

    '/admin/module/slider/category/view/(:var)' => array(
        'controller' => 'Admin:Browser@categoryAction'
    ),
    
    '/admin/module/slider/category/(:var)/page/(:var)' => array(
        'controller' => 'Admin:Browser@categoryAction'
    ),
    
    '/admin/module/slider/category/add' => array(
        'controller' => 'Admin:Category@addAction'
    ),
    
    '/admin/module/slider/category/edit/(:var)' => array(
        'controller' => 'Admin:Category@editAction'
    ),
    
    '/admin/module/slider/category/save' => array(
        'controller' => 'Admin:Category@saveAction',
        'disallow' => array('guest')
    ),
    
    '/admin/module/slider/category/delete/(:var)' => array(
        'controller' => 'Admin:Category@deleteAction',
        'disallow' => array('guest')
    )
);
