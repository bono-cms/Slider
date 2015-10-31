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
    
    '/admin/module/slider/category/view/(:var)' => array(
        'controller' => 'Admin:Browser@categoryAction'
    ),
    
    '/admin/module/slider/category/(:var)/page/(:var)' => array(
        'controller' => 'Admin:Browser@categoryAction'
    ),
    
    '/admin/module/slider/save.ajax' => array(
        'controller' => 'Admin:Browser@saveAction',
        'disallow' => array('guest')
    ),
    
    '/admin/module/slider/image/delete.ajax' => array(
        'controller' => 'Admin:Browser@deleteAction',
        'disallow' => array('guest')
    ),
    
    '/admin/module/slider/image/delete-selected.ajax' => array(
        'controller' => 'Admin:Browser@deleteSelectedAction',
        'disallow' => array('guest')
    ),
    
    '/admin/module/slider/image/add' => array(
        'controller' => 'Admin:Image:Add@indexAction'
    ),
    
    '/admin/module/slider/image/add.ajax' => array(
        'controller' => 'Admin:Image:Add@addAction',
        'disallow' => array('guest')
    ),
    
    '/admin/module/slider/image/edit/(:var)' => array(
        'controller' => 'Admin:Image:Edit@indexAction'
    ),
    
    '/admin/module/slider/image/edit.ajax' => array(
        'controller' => 'Admin:Image:Edit@updateAction',
        'disallow' => array('guest')
    ),
    
    '/admin/module/slider/category/add' => array(
        'controller' => 'Admin:Category:Add@indexAction'
    ),
    
    '/admin/module/slider/category/add.ajax' => array(
        'controller' => 'Admin:Category:Add@addAction',
        'disallow' => array('guest')
    ),
    
    '/admin/module/slider/category/edit/(:var)' => array(
        'controller' => 'Admin:Category:Edit@indexAction'
    ),
    
    '/admin/module/slider/category/edit.ajax' => array(
        'controller' => 'Admin:Category:Edit@updateAction',
        'disallow' => array('guest')
    ),
    
    '/admin/module/slider/category/delete.ajax' => array(
        'controller' => 'Admin:Browser@deleteCategoryAction',
        'disallow' => array('guest')
    )
);
