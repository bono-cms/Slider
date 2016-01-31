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
     * @return string
     */
    public function deleteAction()
    {
        // Batch removal
        if ($this->request->hasPost('toDelete')) {
            $ids = array_keys($this->request->getPost('toDelete'));

            if ($this->getImageManager()->deleteByIds($ids)) {
                $this->flashBag->set('success', 'Selected slides have been removed successfully');
            }
        } else {
            $this->flashBag->set('warning', 'You should select at least one image to remove');
        }

        // Single removal
        if ($this->request->hasPost('id')) {
            $id = $this->request->getPost('id');

            if ($this->getImageManager()->deleteById($id)) {
                $this->flashBag->set('success', 'Selected slider has been removed successfully');
            }
        }

        return '1';
    }

    /**
     * Persists an image
     * 
     * @return string
     */
    public function saveAction()
    {
        $input = $this->request->getPost('image');

        $formValidator = $this->validatorFactory->build(array(
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

        if ($formValidator->isValid()) {
            $imageManager = $this->getImageManager();

            if ($input['id']) {
                if ($imageManager->update($this->request->getAll())) {
                    $this->flashBag->set('success', 'The slider has been updated successfully');
                    return '1';
                }
                
            } else {
                if ($imageManager->add($this->request->getAll())) {
                    $this->flashBag->set('success', 'A slider has been added successfully');
                    return $imageManager->getLastId();
                }
            }

        } else {
            return $formValidator->getErrors();
        }
    }
}
