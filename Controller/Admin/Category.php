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

final class Category extends AbstractController
{
    /**
     * Returns category manager
     * 
     * @return \Slider\Service\CategoryManager
     */
    private function getCategoryManager()
    {
        return $this->getModuleService('categoryManager');
    }

    /**
     * Creates a form
     * 
     * @param \Krystal\Stdlib\VirtualEntity $category
     * @param string $title
     * @return string
     */
    private function createForm(VirtualEntity $category, $title)
    {
        // Append breadcrumbs
        $this->view->getBreadcrumbBag()->addOne('Slider', 'Slider:Admin:Browser@indexAction')
                                       ->addOne($title);

        return $this->view->render('category.form', array(
            'category' => $category
        ));
    }

    /**
     * Renders empty form
     * 
     * @return string
     */
    public function addAction()
    {
        return $this->createForm(new VirtualEntity(), 'Add a category');
    }

    /**
     * Renders edit form
     * 
     * @param string $id
     * @return string
     */
    public function editAction($id)
    {
        $category = $this->getCategoryManager()->fetchById($id);

        if ($category !== false) {
            return $this->createForm($category, 'Edit the category');
        } else {
            return false;
        }
    }

    /**
     * Deletes a category by its associated id
     * 
     * @param string $id
     * @return string
     */
    public function deleteAction($id)
    {
        // Remove all images associated with provided category id
        if ($this->getModuleService('imageManager')->deleteAllByCategoryId($id) && $this->getCategoryManager()->deleteById($id)) {
            $this->flashBag->set('success', 'The category has been removed successfully');
            return '1';
        }
    }

    /**
     * Persists a category
     * 
     * @return string
     */
    public function saveAction()
    {
        $input = $this->request->getPost('category');

        $formValidator = $this->createValidator(array(
            'input' => array(
                'source' => $input,
                'definition' => array(
                    'name' => new Pattern\Name(),
                    'width' => new Pattern\Width(),
                    'height' => new Pattern\Height(),
                    'class' => new Pattern\ClassName()
                )
            )
        ));

        if ($formValidator->isValid()) {
            $service = $this->getModuleService('categoryManager');

            if (!empty($input['id'])) {
                if ($service->update($input)) {
                    $this->flashBag->set('success', 'The element has been updated successfully');
                    return '1';
                }

            } else {
                if ($service->add($input)) {
                    $this->flashBag->set('success', 'The element has been created successfully');
                    return $service->getLastId();
                }
            }

        } else {
            return $formValidator->getErrors();
        }
    }
}
