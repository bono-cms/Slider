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
use Krystal\Validate\Pattern;
use Krystal\Stdlib\VirtualEntity;

final class Image extends AbstractController
{
    /**
     * Returns image manager
     * 
     * @return \Slider\Service\ImageManager
     */
    private function getImageManager()
    {
        return $this->getModuleService('imageManager');
    }

    /**
     * Creates a form
     * 
     * @param \Krystal\Stdlib\VirtualEntity $image
     * @param string $title
     * @return string
     */
    private function createForm(VirtualEntity $image, $title)
    {
        // Load view plugins
        $this->view->getPluginBag()
                   ->appendScript('@Slider/admin/image.form.js');

        // Append breadcrumbs
        $this->view->getBreadcrumbBag()->addOne('Slider', 'Slider:Admin:Browser@indexAction')
                                       ->addOne($title);

        return $this->view->render('image.form', array(
            'categories' => $this->getModuleService('categoryManager')->fetchList(),
            'image' => $image
        ));
    }

    /**
     * Renders empty form
     * 
     * @return string
     */
    public function addAction()
    {
        $this->view->getPluginBag()
                   ->load('preview');

        $image = new VirtualEntity();
        $image->setPublished(true)
              ->setOrder(0);

        return $this->createForm($image, 'Add a slider');
    }

    /**
     * Renders edit form
     * 
     * @param string $id
     * @return string
     */
    public function editAction($id)
    {
        $image = $this->getImageManager()->fetchById($id);

        if ($image !== false) {
            return $this->createForm($image, 'Edit the slider');
        } else {
            return false;
        }
    }

    /**
     * Saves settings
     * 
     * @return string
     */
    public function tweakAction()
    {
        if ($this->request->hasPost('published', 'order')) {
            $published = $this->request->getPost('published');
            $orders = $this->request->getPost('order');

            $imageManager = $this->getImageManager();

            if ($imageManager->updatePublished($published) && $imageManager->updateOrders($orders)) {
                $this->flashBag->set('success', 'Settings have been updated successfully');
                return '1';
            }
        }
    }

    /**
     * Deletes selected slide image
     * 
     * @param string $id
     * @return string
     */
    public function deleteAction($id)
    {
        return $this->invokeRemoval('imageManager', $id);
    }

    /**
     * Persists an image
     * 
     * @return string
     */
    public function saveAction()
    {
        $input = $this->request->getPost('image');

        return $this->invokeSave('imageManager', $input['id'], $this->request->getAll(), array(
            'input' => array(
                'source' => $input,
                'definition' => array(
                    'order' => new Pattern\Order(),
                    'link' => new Pattern\Url()
                )
            ),
            'file' => array(
                'source' => $this->request->getFiles(),
                'definition' => array(
                    'file' => new Pattern\ImageFile(array(
                        'required' => !$input['id']
                    ))
                )
            )
        ));
    }
}
