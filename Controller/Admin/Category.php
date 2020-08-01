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
        $category = new VirtualEntity();
        $category->setQuality(75); // Medium quality by default

        return $this->createForm($category, 'Add a category');
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
            return $this->createForm($category, $this->translator->translate('Edit the category "%s"', $category->getName()));
        } else {
            return false;
        }
    }

    /**
     * Deletes a category by its associated id
     * 
     * @param string $id Category id
     * @return int
     */
    public function deleteAction($id)
    {
        $category = $this->getCategoryManager()->fetchById($id);

        // Delete a category and attached images
        $this->getCategoryManager()->deleteById($id);
        $this->getModuleService('imageManager')->deleteAllByCategoryId($id);

        // Save in the history
        $historyService = $this->getService('Cms', 'historyManager');
        $historyService->write('Slider', 'Category "%s" has been removed', $category->getName());

        $this->flashBag->set('success', 'The category has been removed successfully');
        return 1;
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
            // Save in the history
            $historyService = $this->getService('Cms', 'historyManager');
            $service = $this->getModuleService('categoryManager');

            if (!empty($input['id'])) {
                if ($service->update($input)) {
                    $this->flashBag->set('success', 'The element has been updated successfully');

                    $historyService->write('Slider', 'Category "%s" has been updated', $input['name']);
                    return '1';
                }

            } else {
                if ($service->add($input)) {
                    $this->flashBag->set('success', 'The element has been created successfully');

                    $historyService->write('Slider', 'Category "%s" has been added', $input['name']);
                    return $service->getLastId();
                }
            }

        } else {
            return $formValidator->getErrors();
        }
    }
}
