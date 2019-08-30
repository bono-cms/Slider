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
     * @param \Krystal\Stdlib\VirtualEntity|array $image
     * @param string $title
     * @return string
     */
    private function createForm($image, $title)
    {
        // Load view plugins
        $this->view->getPluginBag()
                   ->load(array($this->getWysiwygPluginName(), 'preview'));

        // Append breadcrumbs
        $this->view->getBreadcrumbBag()->addOne('Slider', 'Slider:Admin:Browser@indexAction')
                                       ->addOne($title);

        $attributeGroupManager = $this->getModuleService('attributeGroupManager');
        $new = is_object($image);

        // Edit image
        if (is_array($image)) {
            $entity = $image[0];
        } else {
            $entity = $image;
        }

        // If edit form
        if (!$new) {
            // Populate values
            $attributes = $attributeGroupManager->fetchAll($entity->getCategoryId());
            $attributeGroupManager->populateValues($attributes, $entity);

        } else {
            // No attributes on creating
            $attributes = array();
        }

        return $this->view->render('image.form', array(
            'categories' => $this->getModuleService('categoryManager')->fetchList(),
            'image' => $image,
            'new' => $new,
            'attributes' => $attributes,
            'hasAttributes' => $entity->getCategoryId() && !empty($attributes)
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
        $historyService = $this->getService('Cms', 'historyManager');

        // Batch removal
        if ($this->request->hasPost('batch')) {
            $ids = array_keys($this->request->getPost('batch'));

            $service->deleteByIds($ids);
            $this->flashBag->set('success', 'Selected elements have been removed successfully');

            // Save in the history
            $historyService->write('Slider', 'Batch removal of "%s" slides', count($ids));

        } else {
            $this->flashBag->set('warning', 'You should select at least one element to remove');
        }

        // Single removal
        if (!empty($id)) {
            $image = $this->getImageManager()->fetchById($id);

            $service->deleteById($id);
            $this->flashBag->set('success', 'Selected element has been removed successfully');

            // Save in the history
            $historyService->write('Slider', 'Slider "%s" has been removed', $image[0]->getName());
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

        if (1) {
            $service = $this->getModuleService('imageManager');
            $historyService = $this->getService('Cms', 'historyManager');

            // Current page name
            $name = $this->getCurrentProperty($this->request->getPost('translation'), 'name');

            if (!empty($input['id'])) {
                if ($service->update($this->request->getAll())) {
                    $this->flashBag->set('success', 'The element has been updated successfully');

                    // Save in the history
                    $historyService->write('Slider', 'Slider "%s" has been updated', $name);
                    return '1';
                }

            } else {
                if ($service->add($this->request->getAll())) {
                    $this->flashBag->set('success', 'The element has been created successfully');

                    // Save in the history
                    $historyService->write('Slider', 'Slider "%s" has been uploaded', $name);
                    return $service->getLastId();
                }
            }

        } else {
            return $formValidator->getErrors();
        }
    }
}
