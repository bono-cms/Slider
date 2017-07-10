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
        // Append breadcrumbs
        $this->view->getBreadcrumbBag()->addOne('Slider', 'Slider:Admin:Browser@indexAction')
                                       ->addOne($title);

        $attributeGroupManager = $this->getModuleService('attributeGroupManager');

        // If edit form
        if ($image->getId()) {
            // Populate values
            $attributes = $attributeGroupManager->fetchAll($image->getCategoryId());
            $attributeGroupManager->populateValues($attributes, $image);

        } else {
            // No attributes on creating
            $attributes = array();
        }

        return $this->view->render('image.form', array(
            'categories' => $this->getModuleService('categoryManager')->fetchList(),
            'image' => $image,
            'attributes' => $attributes,
            'hasAttributes' => $image->getCategoryId() && !empty($attributes)
        ));
    }

    /**
     * Renders empty form
     * 
     * @param string $categoryId Optional category ID to be pre-selected in the form
     * @return string
     */
    public function addAction($categoryId = null)
    {
        $this->view->getPluginBag()
                   ->load('preview');

        $image = new VirtualEntity();
        $image->setPublished(true)
              ->setOrder(0)
              ->setCategoryId($categoryId);

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

            if ($this->getImageManager()->updateSettings($this->request->getPost())) {
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
        $service = $this->getModuleService('imageManager');

        // Batch removal
        if ($this->request->hasPost('toDelete')) {
            $ids = array_keys($this->request->getPost('toDelete'));

            $service->deleteByIds($ids);
            $this->flashBag->set('success', 'Selected elements have been removed successfully');

        } else {
            $this->flashBag->set('warning', 'You should select at least one element to remove');
        }

        // Single removal
        if (!empty($id)) {
            $service->deleteById($id);
            $this->flashBag->set('success', 'Selected element has been removed successfully');
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

        $formValidator = $this->createValidator(array(
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
            $service = $this->getModuleService('imageManager');

            if (!empty($input['id'])) {
                if ($service->update($this->request->getAll())) {
                    $this->flashBag->set('success', 'The element has been updated successfully');
                    return '1';
                }

            } else {
                if ($service->add($this->request->getAll())) {
                    $this->flashBag->set('success', 'The element has been created successfully');
                    return $service->getLastId();
                }
            }

        } else {
            return $formValidator->getErrors();
        }
    }
}
