<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Slider\Controller\Admin;

use Cms\Controller\Admin\AbstractController;

final class Browser extends AbstractController
{
    /**
     * Renders a grid
     * 
     * @param integer $page Current page
     * @return string
     */
    public function indexAction($page = 1)
    {
        $url = $this->createUrl('Slider:Admin:Browser@indexAction', array(), 1);
        return $this->createGrid($page, $url, $this->getModuleService('categoryManager')->getLastId());
    }

    /**
     * Fetches images associated with category id
     * 
     * @param string $categoryId
     * @param integer $page Current page number
     * @return string
     */
    public function categoryAction($id, $page = 1)
    {
        $url = $this->createUrl('Slider:Admin:Browser@categoryAction', array($id), 1);
        return $this->createGrid($page, $url, $id);
    }

    /**
     * Creates a grid
     * 
     * @param integer $page Page number
     * @param string $url
     * @param string $categoryId
     * @return string
     */
    private function createGrid($page, $url, $categoryId)
    {
        $imageManager = $this->getModuleService('imageManager');
        $images = $imageManager->fetchAll($categoryId, $page, $this->getSharedPerPageCount());

        $paginator = $imageManager->getPaginator();
        $paginator->setUrl($url);

        // Appends a breadcrumb
        $this->view->getBreadcrumbBag()
                   ->addOne('Slider');

        return $this->view->render('browser', array(
            'categoryId' => $categoryId,
            'images' => $images,
            'paginator' => $paginator,
            'categories' => $this->getModuleService('categoryManager')->fetchAll()
        ));
    }
}
